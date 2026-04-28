@php
    $data = $block['data'] ?? [];
@endphp

<section class="max-w-none">
    {!! $data['html'] ?? '' !!}
</section>
