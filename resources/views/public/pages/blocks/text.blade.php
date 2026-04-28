@php
    $data = $block['data'] ?? [];
@endphp

<section class="prose prose-lg dark:prose-invert max-w-none">
    {!! $data['content'] ?? '' !!}
</section>
