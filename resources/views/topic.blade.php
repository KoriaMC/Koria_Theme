@extends('koriaforum::_layout')

@php($render = [\Azuriom\Plugin\KoriaForum\Models\Post::class, 'render'])
@php($canModerate = auth()->user()?->can('koriaforum.moderate'))

@section('title', $topic->title)

@section('forum')
    <nav class="kf-crumbs">
        <a href="{{ route('koriaforum.index') }}">Forum</a> ›
        <a href="{{ route('koriaforum.category', $topic->category) }}">{{ $topic->category->name }}</a>
    </nav>
    <h1 class="k-title mb-3">{{ $topic->title }} @include('koriaforum::_status')</h1>

    @if($topic->isReport())
        <section class="kf-card">
            <dl class="kf-report">
                <dt>Accusé</dt><dd><strong>{{ $topic->traitor }}</strong></dd>
                <dt>Faction</dt><dd>{{ $topic->faction }}</dd>
                <dt>Date</dt><dd>{{ $topic->betrayal_date?->format('d/m/Y') }}</dd>
                <dt>Signalé par</dt><dd>{{ $topic->author->name }}</dd>
                <dt>Pertes</dt><dd>{{ $render($topic->losses) }}</dd>
                <dt>Preuves</dt><dd>{{ $render($topic->proofs) }}</dd>
            </dl>

            @if($topic->status !== 'pending' && $topic->staff_note)
                <div class="alert alert-{{ $topic->statusColor() }} mt-3 mb-0">
                    <strong>Décision de {{ $topic->staff?->name ?? 'staff' }} :</strong><br>
                    {{ $render($topic->staff_note) }}
                </div>
            @endif
        </section>

        @if($canModerate)
            <form method="POST" action="{{ route('koriaforum.verdict', $topic) }}" class="kf-card kf-staff">
                @csrf
                <h2 class="h5">Décision du staff</h2>
                <p class="kf-muted small">
                    Le ban et le remboursement se font en jeu (ex. <code>/ban {{ $topic->traitor }}</code>).
                    Ici tu enregistres la décision, visible par tout le monde.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-3">
                    @foreach(\Azuriom\Plugin\KoriaForum\Models\Topic::STATUSES as $key => [$label])
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status-{{ $key }}" value="{{ $key }}" @checked(old('status', $topic->status) === $key)>
                            <label class="form-check-label" for="status-{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="mb-3">
                    <label class="form-label" for="staff_note">Explication (obligatoire sauf pour « en attente »)</label>
                    <textarea class="form-control" id="staff_note" name="staff_note" rows="3">{{ old('staff_note', $topic->staff_note) }}</textarea>
                </div>
                <button type="submit" class="k-btn k-btn-wood">Enregistrer</button>
            </form>
        @endif
    @endif

    @foreach($posts as $post)
        <article @class(['kf-card', 'kf-post', 'kf-verdict' => $post->is_verdict]) id="message-{{ $post->id }}">
            <header class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ $post->author->getAvatar() }}" alt="" class="rounded" width="32" height="32">
                <strong>{{ $post->author->name }}</strong>
                <span class="kf-muted small">{{ format_date($post->created_at, true) }}</span>
                @if($canModerate)
                    <form method="POST" action="{{ route('koriaforum.post.destroy', $post) }}" class="ms-auto" onsubmit="return confirm('Supprimer ce message ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-link btn-sm text-danger p-0">Supprimer</button>
                    </form>
                @endif
            </header>
            <div class="kf-body">{{ $render($post->content) }}</div>
        </article>
    @endforeach

    {{ $posts->links() }}

    @auth
        <form method="POST" action="{{ route('koriaforum.reply', $topic) }}" class="kf-card">
            @csrf
            <label class="form-label" for="reply">Répondre</label>
            <textarea class="form-control mb-3" id="reply" name="content" rows="4" required>{{ old('content') }}</textarea>
            <button type="submit" class="k-btn">Envoyer</button>
        </form>
    @else
        <p class="kf-muted"><a href="{{ route('login') }}">Connecte-toi</a> pour répondre.</p>
    @endauth

    @if($canModerate)
        <form method="POST" action="{{ route('koriaforum.topic.destroy', $topic) }}" onsubmit="return confirm('Supprimer ce sujet définitivement ?')">
            @csrf @method('DELETE')
            <button class="btn btn-link text-danger p-0">Supprimer le sujet (staff)</button>
        </form>
    @endif
@endsection
