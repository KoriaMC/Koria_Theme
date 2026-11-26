<?php

namespace Azuriom\Plugin\KoriaForum\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\KoriaForum\Models\Category;
use Azuriom\Plugin\KoriaForum\Models\Post;
use Azuriom\Plugin\KoriaForum\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ForumController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('position')
            ->withCount('topics')
            ->withCount(['topics as pending_count' => fn ($q) => $q->where('status', 'pending')])
            ->get();

        return view('koriaforum::index', ['categories' => $categories]);
    }

    public function category(Request $request, Category $category)
    {
        $status = $request->query('status');

        $topics = $category->topics()
            ->with('author')
            ->withCount('posts')
            ->when($category->is_reports && array_key_exists($status, Topic::STATUSES), fn ($q) => $q->where('status', $status))
            ->orderByDesc('last_post_at')
            ->paginate(20)
            ->withQueryString();

        return view('koriaforum::category', [
            'category' => $category,
            'topics' => $topics,
            'status' => $status,
        ]);
    }

    public function create(Category $category)
    {
        return view('koriaforum::create', ['category' => $category]);
    }

    public function store(Request $request, Category $category)
    {
        $user = $request->user();

        if ($category->is_reports) {
            $data = $request->validate([
                'traitor' => ['required', 'string', 'regex:/^[A-Za-z0-9_]{3,16}$/'],
                'faction' => ['required', 'string', 'max:40'],
                'betrayal_date' => ['required', 'date', 'before_or_equal:today'],
                'losses' => ['required', 'string', 'max:2000'],
                'proofs' => ['required', 'string', 'max:2000', 'regex:~https?://~i'],
                'content' => ['required', 'string', 'min:10', 'max:10000'],
            ], [
                'traitor.regex' => 'Pseudo Minecraft invalide (3 à 16 caractères : lettres, chiffres, _).',
                'proofs.regex' => 'Mets au moins un lien vers une preuve (screen, vidéo).',
            ]);
            $title = "Trahison de {$data['traitor']} ({$data['faction']})";
        } else {
            $data = $request->validate([
                'title' => ['required', 'string', 'min:4', 'max:120'],
                'content' => ['required', 'string', 'min:10', 'max:10000'],
            ]);
            $title = $data['title'];
        }

        $topic = DB::transaction(function () use ($category, $user, $data, $title) {
            $topic = $category->topics()->create([
                'author_id' => $user->id,
                'title' => $title,
                'traitor' => $data['traitor'] ?? null,
                'faction' => $data['faction'] ?? null,
                'betrayal_date' => $data['betrayal_date'] ?? null,
                'losses' => $data['losses'] ?? null,
                'proofs' => $data['proofs'] ?? null,
                'status' => $category->is_reports ? 'pending' : null,
                'last_post_at' => now(),
            ]);

            $topic->posts()->create(['author_id' => $user->id, 'content' => $data['content']]);

            return $topic;
        });

        return redirect()->route('koriaforum.topic', $topic);
    }

    public function topic(Topic $topic)
    {
        $topic->load(['category', 'author', 'staff']);

        return view('koriaforum::topic', [
            'topic' => $topic,
            'posts' => $topic->posts()->with('author')->orderBy('id')->paginate(30),
        ]);
    }

    public function reply(Request $request, Topic $topic)
    {
        $data = $request->validate(['content' => ['required', 'string', 'min:2', 'max:10000']]);

        $topic->posts()->create(['author_id' => $request->user()->id, 'content' => $data['content']]);
        $topic->update(['last_post_at' => now()]);

        return redirect()->route('koriaforum.topic', ['topic' => $topic, 'page' => $topic->posts()->paginate(30)->lastPage()]);
    }

    public function verdict(Request $request, Topic $topic)
    {
        abort_unless($topic->isReport(), 404);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Topic::STATUSES))],
            'staff_note' => ['required_unless:status,pending', 'nullable', 'string', 'max:2000'],
        ], [
            'staff_note.required_unless' => 'Explique ta décision (ce qui est remboursé, durée du ban, ou raison du refus).',
        ]);

        DB::transaction(function () use ($request, $topic, $data) {
            $topic->update([
                'status' => $data['status'],
                'staff_note' => $data['staff_note'],
                'staff_id' => $request->user()->id,
                'last_post_at' => now(),
            ]);

            // Trace publique de la décision dans le sujet
            $topic->posts()->create([
                'author_id' => $request->user()->id,
                'content' => 'Décision du staff : '.Topic::STATUSES[$data['status']][0]
                    .($data['staff_note'] ? "\n".$data['staff_note'] : ''),
                'is_verdict' => true,
            ]);
        });

        return redirect()->route('koriaforum.topic', $topic)->with('success', 'Décision enregistrée.');
    }

    public function destroyTopic(Topic $topic)
    {
        $slug = $topic->category->slug;
        $topic->delete();

        return redirect()->route('koriaforum.category', $slug)->with('success', 'Sujet supprimé.');
    }

    public function destroyPost(Post $post)
    {
        $topic = $post->topic;

        // Supprimer le premier message = supprimer le sujet
        if ($topic->posts()->orderBy('id')->value('id') === $post->id) {
            return $this->destroyTopic($topic);
        }

        $post->delete();

        return redirect()->route('koriaforum.topic', $topic)->with('success', 'Message supprimé.');
    }
}
