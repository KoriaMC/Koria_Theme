@extends('koriaforum::_layout')

@section('title', $category->is_reports ? 'Signaler une trahison' : 'Nouveau sujet')

@section('forum')
    <nav class="kf-crumbs">
        <a href="{{ route('koriaforum.index') }}">Forum</a> ›
        <a href="{{ route('koriaforum.category', $category) }}">{{ $category->name }}</a> › Nouveau
    </nav>
    <h1 class="k-title mb-3">{{ $category->is_reports ? 'Signaler une trahison' : 'Nouveau sujet' }}</h1>

    <form method="POST" action="{{ route('koriaforum.store', $category) }}" class="kf-card">
        @csrf

        @if($category->is_reports)
            <p class="kf-muted">
                Ton signalement passe <strong>en attente</strong>. Le staff vérifie les preuves avant tout ban ou remboursement.
                Un faux signalement peut être sanctionné.
            </p>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label" for="traitor">Pseudo du traître</label>
                    <input class="form-control" id="traitor" name="traitor" maxlength="16" required value="{{ old('traitor') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="faction">Ta faction</label>
                    <input class="form-control" id="faction" name="faction" maxlength="40" required value="{{ old('faction') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="betrayal_date">Date de la trahison</label>
                    <input class="form-control" type="date" id="betrayal_date" name="betrayal_date" max="{{ now()->toDateString() }}" required value="{{ old('betrayal_date') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="losses">Ce qui a été volé ou détruit (pour le remboursement)</label>
                <textarea class="form-control" id="losses" name="losses" rows="4" required placeholder="ex : 2 stacks de diamants, 1 plastron netherite Prot IV…">{{ old('losses') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="proofs">Preuves : liens vers screens ou vidéos, un par ligne</label>
                <textarea class="form-control" id="proofs" name="proofs" rows="3" required placeholder="https://imgur.com/…">{{ old('proofs') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="content">Ce qui s'est passé</label>
                <textarea class="form-control" id="content" name="content" rows="6" required placeholder="Quand il a été recruté, la règle « forum trahison = ban + remboursement » était annoncée…">{{ old('content') }}</textarea>
            </div>
        @else
            <div class="mb-3">
                <label class="form-label" for="title">Titre</label>
                <input class="form-control" id="title" name="title" maxlength="120" required value="{{ old('title') }}">
            </div>
            <div class="mb-3">
                <label class="form-label" for="content">Message</label>
                <textarea class="form-control" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
            </div>
        @endif

        <button type="submit" class="k-btn">Publier</button>
    </form>
@endsection
