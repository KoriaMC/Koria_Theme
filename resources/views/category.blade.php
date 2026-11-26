@extends('koriaforum::_layout')

@section('title', $category->name)

@section('forum')
    <nav class="kf-crumbs"><a href="{{ route('koriaforum.index') }}">Forum</a> › {{ $category->name }}</nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-2">
        <h1 class="k-title mb-0">{{ $category->name }}</h1>
        <a href="{{ route('koriaforum.create', $category) }}" class="k-btn">
            {{ $category->is_reports ? 'Signaler une trahison' : 'Nouveau sujet' }}
        </a>
    </div>
    <p class="kf-muted">{{ $category->description }}</p>

    @if($category->is_reports)
        <div class="kf-filters mb-3">
            <a href="{{ route('koriaforum.category', $category) }}" @class(['on' => ! $status])>Tous</a>
            @foreach(\Azuriom\Plugin\KoriaForum\Models\Topic::STATUSES as $key => [$label])
                <a href="{{ route('koriaforum.category', [$category, 'status' => $key]) }}" @class(['on' => $status === $key])>{{ $label }}</a>
            @endforeach
        </div>
    @endif

    @forelse($topics as $topic)
        <div class="kf-topic">
            <a href="{{ route('koriaforum.topic', $topic) }}" class="fw-semibold">{{ $topic->title }}</a>
            @include('koriaforum::_status')
            <div class="kf-muted small">
                par {{ $topic->author->name }} · {{ format_date($topic->created_at) }} · {{ max(0, $topic->posts_count - 1) }} réponse(s)
                @if($topic->isReport())
                    · accusé : <strong>{{ $topic->traitor }}</strong> ({{ $topic->faction }})
                @endif
            </div>
        </div>
    @empty
        <p class="kf-muted text-center py-5">Aucun sujet pour l'instant.</p>
    @endforelse

    {{ $topics->links() }}
@endsection
