@extends('layouts.app')

@section('title', $page->title)
@section('description', $page->description)

@php
    // Icône du panneau selon le nom de la page (règlement, CGV, mentions…)
    $koriaSlug = \Illuminate\Support\Str::slug($page->slug ?? $page->title);
    $koriaIcon = match (true) {
        str_contains($koriaSlug, 'regle') => 'bi-shield-check',
        str_contains($koriaSlug, 'cgv') || str_contains($koriaSlug, 'vente') => 'bi-bag-check-fill',
        str_contains($koriaSlug, 'mention') || str_contains($koriaSlug, 'legal') => 'bi-bank2',
        str_contains($koriaSlug, 'confidential') || str_contains($koriaSlug, 'privacy') || str_contains($koriaSlug, 'donnee') => 'bi-lock-fill',
        default => 'bi-file-earmark-text-fill',
    };
@endphp

@push('footer-scripts')
    <script>
        (function () {
            // Sommaire automatique à partir des titres de la page
            var content = document.getElementById('koriaPageContent');
            var toc = document.getElementById('koriaToc');
            if (!content || !toc) return;

            var headings = content.querySelectorAll('h2');
            if (headings.length < 3) return;

            var list = toc.querySelector('ol');
            var links = [];
            headings.forEach(function (heading, index) {
                if (!heading.id) {
                    heading.id = 'partie-' + (index + 1);
                }
                var li = document.createElement('li');
                var a = document.createElement('a');
                a.href = '#' + heading.id;
                a.textContent = heading.textContent.replace(/^\s*(article\s*)?\d+[\s.):\-–]*/i, '').trim() || heading.textContent;
                li.appendChild(a);
                list.appendChild(li);
                links.push(a);
            });

            toc.classList.remove('d-none');
            document.getElementById('koriaPageCol').classList.replace('col-lg-12', 'col-lg-9');

            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        links.forEach(function (link) {
                            link.classList.toggle('is-active', link.getAttribute('href') === '#' + entry.target.id);
                        });
                    });
                }, { rootMargin: '-120px 0px -65% 0px' });
                headings.forEach(function (heading) { observer.observe(heading); });
            }

            var top = document.getElementById('koriaToTop');
            window.addEventListener('scroll', function () {
                top.classList.toggle('is-visible', window.scrollY > 700);
            }, { passive: true });
        })();
    </script>
@endpush

@section('content')
    @include('elements.page-head', [
        'title' => $page->title,
        'icon' => $koriaIcon,
        'subtitle' => $page->description,
    ])

    <div class="row g-4 flex-lg-row-reverse">
        <div class="col-lg-3 d-none" id="koriaToc">
            <nav class="k-panel k-wood k-toc" aria-label="Sommaire">
                <p class="k-panel-title"><i class="bi bi-list-ol"></i> Sommaire</p>
                <ol></ol>
            </nav>
        </div>

        <div class="col-lg-12" id="koriaPageCol">
            <article class="k-box">
                <div class="k-box-body k-prose p-lg-5" id="koriaPageContent">
                    {!! $page->content !!}
                </div>
            </article>

            @if($page->updated_at)
                <p class="text-center fw-semibold" style="color: var(--k-text-soft)">
                    <i class="bi bi-calendar-check"></i> Dernière mise à jour : {{ format_date($page->updated_at) }}
                </p>
            @endif
        </div>
    </div>

    <a href="#app" class="k-btn k-btn-wood k-btn-square k-to-top" id="koriaToTop" aria-label="Revenir en haut">
        <i class="bi bi-arrow-up"></i>
    </a>
@endsection
