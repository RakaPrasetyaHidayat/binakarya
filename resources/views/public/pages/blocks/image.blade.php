@php
    $data = $block['data'] ?? [];
    $url = $data['url'] ?? '';
@endphp

@if($url)
<figure class="space-y-2">
    <img src="{{ $url }}" alt="{{ $data['alt'] ?? '' }}" class="w-full rounded-xl border border-gray-200 dark:border-slate-700">
    @if(!empty($data['caption']))
        <figcaption class="text-sm text-gray-500 dark:text-slate-400 text-center">{{ $data['caption'] }}</figcaption>
    @endif
</figure>
@endif
