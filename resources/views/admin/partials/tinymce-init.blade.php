{{--
    Reusable TinyMCE initializer.

    Usage:
        @include('admin.partials.tinymce-init', [
            'editors' => [
                ['selector' => '#body-editor',    'height' => 500, 'toolbar' => 'full'],
                ['selector' => '#abstract-editor','height' => 320, 'toolbar' => 'minimal'],
            ]
        ])

    toolbar presets:
        'full'    — artikel / halaman / layanan (semua fitur + source code)
        'minimal' — abstrak / bio (bold, italic, list, link)
--}}

@php
    $tinymceApiKey = \App\Models\Setting::get('tinymce_api_key', '');
    // Jika ada API key → gunakan TinyMCE Cloud (semua fitur premium)
    // Jika tidak → gunakan unpkg self-hosted (gratis, tidak butuh API key)
    $tinymceSrc = $tinymceApiKey
        ? 'https://cdn.tiny.cloud/1/' . $tinymceApiKey . '/tinymce/7/tinymce.min.js'
        : 'https://unpkg.com/tinymce@7/tinymce.min.js';
    $editors = $editors ?? [];
@endphp

<script src="{{ $tinymceSrc }}" referrerpolicy="origin"></script>
<script>
(function () {
    const isDark = document.documentElement.classList.contains('dark');

    // Jika menggunakan self-hosted (unpkg), set base_url agar skin & plugin bisa dimuat
    const isCloud = {{ $tinymceApiKey ? 'true' : 'false' }};
    if (!isCloud && window.tinymce) {
        tinymce.baseURL = 'https://unpkg.com/tinymce@7';
        tinymce.suffix = '.min';
    }

    const contentStyleDark =
        'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.7;color:#e5e7eb;background-color:#1f2937;padding:14px 16px;}' +
        'h1,h2,h3,h4,h5,h6{color:#f3f4f6;margin-top:1.5rem;margin-bottom:.5rem;}' +
        'p{margin-bottom:1rem;}' +
        'blockquote{border-left:4px solid #7c3aed;background:rgba(124,58,237,.1);padding:1rem 1.5rem;margin:1rem 0;border-radius:.25rem;}' +
        'a{color:#60a5fa;}' +
        'img{max-width:100%;height:auto;border-radius:.5rem;}' +
        'pre,code{background:#374151;color:#e5e7eb;border-radius:.375rem;padding:.2em .4em;font-size:.875em;}' +
        'pre{padding:1rem;overflow-x:auto;}' +
        'table{border-collapse:collapse;width:100%;}th,td{border:1px solid #4b5563;padding:.5rem;}th{background:#374151;}';

    const contentStyleLight =
        'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.7;color:#374151;padding:14px 16px;}' +
        'h1,h2,h3,h4,h5,h6{color:#111827;margin-top:1.5rem;margin-bottom:.5rem;}' +
        'p{margin-bottom:1rem;}' +
        'blockquote{border-left:4px solid #2563eb;background:rgba(37,99,235,.06);padding:1rem 1.5rem;margin:1rem 0;border-radius:.25rem;}' +
        'img{max-width:100%;height:auto;border-radius:.5rem;}' +
        'pre,code{background:#f3f4f6;color:#374151;border-radius:.375rem;padding:.2em .4em;font-size:.875em;}' +
        'pre{padding:1rem;overflow-x:auto;}' +
        'table{border-collapse:collapse;width:100%;}th,td{border:1px solid #e5e7eb;padding:.5rem;}th{background:#f9fafb;}';

    // Full toolbar: visual + source code toggle
    const toolbarFull =
        'undo redo | blocks fontfamily fontsize | ' +
        'bold italic underline strikethrough | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | ' +
        'link image media table codesample | ' +
        'forecolor backcolor | emoticons charmap | ' +
        'code removeformat | help';

    // Minimal toolbar: untuk abstrak / bio
    const toolbarMinimal =
        'undo redo | bold italic underline | bullist numlist | link | code | removeformat';

    const pluginsFull = [
        'advlist','autolink','lists','link','image','charmap','preview',
        'anchor','searchreplace','visualblocks','code','fullscreen',
        'insertdatetime','media','table','help','wordcount','codesample',
        'emoticons','nonbreaking','directionality'
    ];

    const pluginsMinimal = [
        'advlist','autolink','lists','link','charmap','searchreplace','wordcount','code'
    ];

    const baseConfig = {
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',
        content_style: isDark ? contentStyleDark : contentStyleLight,
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        entity_encoding: 'raw',
        valid_elements: '*[*]',
        extended_valid_elements: 'script[src|async|defer|type|charset],style[type]',
        toolbar_mode: 'sliding',
        resize: true,
        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
        font_family_formats: 'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif;',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre;',
        link_default_target: '_blank',
        link_assume_external_targets: 'https',
        table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
        table_default_attributes: { 'border': '1' },
        codesample_languages: [
            { text: 'HTML/XML', value: 'markup' },
            { text: 'JavaScript', value: 'javascript' },
            { text: 'CSS', value: 'css' },
            { text: 'PHP', value: 'php' },
            { text: 'Python', value: 'python' },
            { text: 'SQL', value: 'sql' },
            { text: 'Bash', value: 'bash' },
        ],
    };

    const editors = @json($editors);

    function initEditors() {
        if (!window.tinymce) {
            console.warn('TinyMCE not loaded yet, retrying...');
            setTimeout(initEditors, 300);
            return;
        }

        editors.forEach(function (cfg) {
            const el = document.querySelector(cfg.selector);
            if (!el) return;

            // Jangan init ulang jika sudah ada instance
            if (tinymce.get(cfg.selector.replace('#', ''))) return;

            const isMinimal = cfg.toolbar === 'minimal';

            tinymce.init(Object.assign({}, baseConfig, {
                selector: cfg.selector,
                height: cfg.height || (isMinimal ? 300 : 500),
                min_height: isMinimal ? 200 : 400,
                max_height: isMinimal ? 500 : 900,
                menubar: !isMinimal,
                plugins: isMinimal ? pluginsMinimal : pluginsFull,
                toolbar: isMinimal ? toolbarMinimal : toolbarFull,
            }));
        });

        // Sync semua instance ke textarea saat form submit
        const form = document.querySelector('form');
        if (form && !form.dataset.tinymceBound) {
            form.dataset.tinymceBound = '1';
            form.addEventListener('submit', function () {
                tinymce.triggerSave();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditors);
    } else {
        initEditors();
    }
})();
</script>
