@if($topic->isReport())
    <span class="badge rounded-pill text-bg-{{ $topic->statusColor() }}">{{ $topic->statusLabel() }}</span>
@endif
