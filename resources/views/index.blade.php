@extends('koriaforum::_layout')

@section('title', 'Forum')

@section('forum')
    <h1 class="k-title mb-3">Forum des factions</h1>

    <div class="kf-rules k-cream-field mb-4">
        Quand tu recrutes, tu peux annoncer la règle <strong>« forum trahison = ban + remboursement »</strong>.
        Si un membre pille ta base, poste un signalement avec tes preuves dans
        <a href="{{ route('koriaforum.category', 'trahison') }}">Signalements de trahison</a>.
        <strong>Rien n'est automatique :</strong> le staff vérifie les preuves, puis bannit le traître et rembourse les victimes, ou refuse.
    </div>

    <div class="d-grid gap-3">
        @foreach($categories as $category)
            <a href="{{ route('koriaforum.category', $category) }}" class="kf-cat">
                <div>
                    <h2 class="h5 mb-1">{{ $category->name }}</h2>
                    <p class="mb-0">{{ $category->description }}</p>
                </div>
                <div class="kf-meta">
                    {{ $category->topics_count }} sujet(s)
                    @if($category->is_reports && $category->pending_count > 0)
                        <br><span class="badge rounded-pill text-bg-warning">{{ $category->pending_count }} en attente</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endsection
