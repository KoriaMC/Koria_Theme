@php($expanded = $breadcrumb->contains('id', $category->id))

<div class="kw-nav-group">
    <button type="button" class="kw-nav-cat @if($expanded) is-open @else collapsed @endif"
            data-bs-toggle="collapse" data-bs-target="#wiki-category-{{ $category->id }}"
            aria-expanded="{{ $expanded ? 'true' : 'false' }}" aria-controls="wiki-category-{{ $category->id }}">
        <i class="{{ $category->icon ?? 'bi bi-book' }}"></i>
        <span>{{ $category->name }}</span>
        <i class="bi bi-chevron-down kw-chevron" aria-hidden="true"></i>
    </button>

    <div class="collapse @if($expanded) show @endif" id="wiki-category-{{ $category->id }}">
        <ul class="kw-nav-pages">
            @foreach($category->pages as $catPage)
                <li>
                    <a href="{{ route('wiki.pages.show', [$category, $catPage]) }}"
                       class="@if($page->is($catPage)) is-active @endif"
                       @if($page->is($catPage)) aria-current="page" @endif>
                        {{ $catPage->title }}
                    </a>
                </li>
            @endforeach

            @foreach($category->categories as $subCategory)
                <li>
                    @include('wiki::partials._sidebar-category', ['category' => $subCategory])
                </li>
            @endforeach
        </ul>
    </div>
</div>
