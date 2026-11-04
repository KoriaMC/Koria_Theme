{{--
    Champ de la page de configuration Koria.
    Variables : $key (clé avec points, ex. steps.0.title), $label, $type, et optionnellement
    $help, $icon, $placeholder, $options (select), $images (image), $max, $col, $preview.
--}}
@php
    $type ??= 'text';
    $name = collect(explode('.', $key))->map(fn ($part, $i) => $i === 0 ? $part : '['.$part.']')->implode('');
    $id = 'k_'.str_replace('.', '_', $key);
    $value = old($key, $k->get($key));
    $preview ??= $key;
@endphp

<div class="{{ $col ?? 'col-12' }}">
    @if($type === 'switch')
        <div class="k-switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" role="switch" id="{{ $id }}" name="{{ $name }}" value="1"
                       data-preview="{{ $preview }}" @checked(filter_var($value, FILTER_VALIDATE_BOOLEAN))>
                <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
            </div>
            @isset($help)<div class="form-text">{{ $help }}</div>@endisset
        </div>
    @else
        <label class="form-label" for="{{ $id }}">{{ $label }}</label>

        @if($type === 'textarea')
            <textarea class="form-control @error($key) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" rows="{{ $rows ?? 3 }}"
                      @isset($max) maxlength="{{ $max }}" @endisset data-preview="{{ $preview }}"
                      @isset($placeholder) placeholder="{{ $placeholder }}" @endisset>{{ $value }}</textarea>

        @elseif($type === 'color')
            <div class="k-color">
                <input type="color" class="form-control form-control-color" id="{{ $id }}" value="{{ $k->color($key) }}"
                       data-color-for="{{ $id }}_hex">
                <input type="text" class="form-control @error($key) is-invalid @enderror" id="{{ $id }}_hex" name="{{ $name }}"
                       value="{{ $value }}" maxlength="7" pattern="#[0-9a-fA-F]{6}" data-preview="{{ $preview }}"
                       data-default="{{ $default ?? '' }}">
                <button type="button" class="btn btn-outline-secondary" data-color-reset="{{ $id }}" title="Couleur d'origine">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>

        @elseif($type === 'image')
            <div class="k-image-field">
                <div class="input-group">
                    <a class="btn btn-outline-success" href="{{ route('admin.images.create') }}" target="_blank" rel="noopener noreferrer" title="Envoyer une image">
                        <i class="bi bi-upload"></i>
                    </a>
                    <select class="form-select @error($key) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" data-preview="{{ $preview }}"
                            data-image-preview="{{ $id }}_img" data-default-src="{{ $defaultSrc ?? '' }}">
                        <option value="">{{ $emptyLabel ?? 'Image par défaut du thème' }}</option>
                        @foreach($images as $image)
                            <option value="{{ $image->file }}" data-src="{{ image_url($image->file) }}" @selected($image->file === $value)>
                                {{ $image->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="k-image-thumb">
                    <img id="{{ $id }}_img" src="{{ $value ? image_url($value) : ($defaultSrc ?? '') }}" alt="" @if(! $value && empty($defaultSrc)) hidden @endif>
                </div>
            </div>

        @elseif($type === 'select')
            <select class="form-select @error($key) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" data-preview="{{ $preview }}">
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $optionValue === (string) $value)>{{ $optionLabel }}</option>
                @endforeach
            </select>

        @elseif($type === 'icon')
            <div class="input-group">
                <span class="input-group-text k-icon-preview"><i class="bi {{ $value }}" data-icon-preview="{{ $id }}"></i></span>
                <input type="text" class="form-control @error($key) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
                       placeholder="bi-star-fill" data-icon-input="{{ $id }}" data-preview="{{ $preview }}">
            </div>

        @else
            <div class="input-group">
                @isset($icon)<span class="input-group-text"><i class="bi {{ $icon }}"></i></span>@endisset
                <input type="{{ $type }}" class="form-control @error($key) is-invalid @enderror" id="{{ $id }}" name="{{ $name }}"
                       value="{{ $value }}" data-preview="{{ $preview }}"
                       @isset($max) maxlength="{{ $max }}" @endisset
                       @isset($min) min="{{ $min }}" @endisset
                       @isset($placeholder) placeholder="{{ $placeholder }}" @endisset>
            </div>
        @endif

        @error($key)
            <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
        @enderror
        @isset($help)<div class="form-text">{{ $help }}</div>@endisset
    @endif
</div>
