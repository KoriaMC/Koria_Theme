{{-- Île flottante de faction : petit fort en pierre et bannière, dessinée pour le thème Koria --}}
<svg class="k-island" viewBox="-130 -125 260 450" role="img" aria-label="Île de faction avec sa bannière">
    <defs>
        @foreach([
            'grass' => ['#8fd65a', '#a8672f', '#87501f', '#6fbc3c', '#579a2c'],
            'dirt' => ['#b5753d', '#9c5c2b', '#7f4720', null, null],
            'stone' => ['#c2c2c2', '#9d9d9d', '#7f7f7f', null, null],
            'brick' => ['#b9b4ad', '#8f8a84', '#726d68', null, null],
        ] as $id => [$top, $left, $right, $stripL, $stripR])
            <g id="k-{{ $id }}" stroke="rgba(40,20,5,.18)" stroke-width="1" stroke-linejoin="round">
                <polygon points="-40,0 0,23 0,69 -40,46" fill="{{ $left }}"/>
                <polygon points="40,0 0,23 0,69 40,46" fill="{{ $right }}"/>
                @if($stripL)
                    <polygon points="-40,0 0,23 0,35 -40,12" fill="{{ $stripL }}" stroke="none"/>
                    <polygon points="40,0 0,23 0,35 40,12" fill="{{ $stripR }}" stroke="none"/>
                @endif
                @if($id === 'brick')
                    <path d="M-40,23 L0,46 M40,23 L0,46 M-20,11 V34 M20,11 V34" stroke="rgba(40,20,5,.22)" fill="none"/>
                @endif
                <polygon points="0,-23 40,0 0,23 -40,0" fill="{{ $top }}"/>
            </g>
        @endforeach
    </defs>

    <ellipse class="k-island-shadow" cx="0" cy="318" rx="70" ry="10"/>

    <g class="k-island-body">
        @foreach([
            ['dirt', 0, 46],
            ['dirt', -40, 69], ['dirt', 40, 69], ['stone', 0, 138], ['grass', 0, 0],
            ['brick', 0, -46],
            ['stone', 0, 230], ['stone', -40, 161], ['stone', 40, 161], ['dirt', -80, 92], ['dirt', 0, 92], ['dirt', 80, 92], ['grass', -40, 23], ['grass', 40, 23],
            ['stone', 0, 184], ['dirt', -40, 115], ['dirt', 40, 115], ['grass', -80, 46], ['grass', 0, 46], ['grass', 80, 46],
            ['dirt', 0, 138], ['grass', -40, 69], ['grass', 40, 69], ['brick', 80, 0], ['brick', -80, 0],
            ['grass', 0, 92],
        ] as [$block, $x, $y])
            <use href="#k-{{ $block }}" x="{{ $x }}" y="{{ $y }}"/>
        @endforeach

        {{-- Bannière de faction --}}
        <g class="k-banner">
            <rect x="-3.5" y="-112" width="7" height="160" rx="2" fill="#5f3a1b"/>
            <rect x="-6" y="-116" width="12" height="8" rx="2" fill="#e1a83b"/>
            <g class="k-banner-cloth">
                <path d="M3.5,-106 H66 V-38 L35,-50 L3.5,-38 Z" fill="#c9342a"/>
                <path d="M3.5,-106 H66 V-96 H3.5 Z" fill="#a8261f"/>
                <path d="M34.5,-90 L50,-84 V-70 C50,-60 43,-54 34.5,-50 C26,-54 19,-60 19,-70 V-84 Z" fill="#fdf1dd"/>
                <path d="M28,-77 L41,-64 M41,-77 L28,-64" stroke="#c9342a" stroke-width="3.5" stroke-linecap="round"/>
            </g>
        </g>
    </g>
</svg>
