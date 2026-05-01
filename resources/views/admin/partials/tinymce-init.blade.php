{{--
    Reusable TinyMCE initializer — file lokal dari /public/tinymce
    toolbar: 'full' | 'minimal'
--}}
@php $editors = $editors ?? []; @endphp

<script src="{{ asset('tinymce/tinymce.min.js') }}" nonce="{{ $cspNonce ?? '' }}"></script>
<script nonce="{{ $cspNonce ?? '' }}">
(function () {
    const isDark = document.documentElement.classList.contains('dark');

    const contentStyleDark =
        'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.7;color:#e5e7eb;background-color:#1f2937;padding:16px 20px;}' +
        'h1,h2,h3,h4,h5,h6{color:#f3f4f6;margin-top:1.5rem;margin-bottom:.5rem;}p{margin-bottom:1rem;}' +
        'blockquote{border-left:4px solid #7c3aed;background:rgba(124,58,237,.1);padding:1rem 1.5rem;margin:1rem 0;border-radius:.25rem;}' +
        'a{color:#60a5fa;}img{max-width:100%;height:auto;border-radius:.5rem;}' +
        'pre,code{background:#374151;color:#e5e7eb;border-radius:.375rem;padding:.2em .4em;font-size:.875em;}' +
        'pre{padding:1rem;overflow-x:auto;}' +
        'table{border-collapse:collapse;width:100%;}th,td{border:1px solid #4b5563;padding:.5rem;}th{background:#374151;}';

    const contentStyleLight =
        'body{font-family:Inter,sans-serif;font-size:16px;line-height:1.7;color:#374151;padding:16px 20px;}' +
        'h1,h2,h3,h4,h5,h6{color:#111827;margin-top:1.5rem;margin-bottom:.5rem;}p{margin-bottom:1rem;}' +
        'blockquote{border-left:4px solid #2563eb;background:rgba(37,99,235,.06);padding:1rem 1.5rem;margin:1rem 0;border-radius:.25rem;}' +
        'img{max-width:100%;height:auto;border-radius:.5rem;}' +
        'pre,code{background:#f3f4f6;color:#374151;border-radius:.375rem;padding:.2em .4em;font-size:.875em;}' +
        'pre{padding:1rem;overflow-x:auto;}' +
        'table{border-collapse:collapse;width:100%;}th,td{border:1px solid #e5e7eb;padding:.5rem;}th{background:#f9fafb;}';

    const toolbarFull1 = 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat';
    const toolbarFull2 = 'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | subscript superscript | hr pagebreak';
    const toolbarFull3 = 'link image media table codesample | charmap emoticons | anchor | code preview fullscreen | help';
    const toolbarMinimal = 'undo redo | bold italic underline | bullist numlist | link | code | removeformat';

    const pluginsFull = [
        'advlist','autolink','lists','link','image','charmap','preview',
        'anchor','searchreplace','visualblocks','visualchars','code','fullscreen',
        'insertdatetime','media','table','help','wordcount','codesample',
        'emoticons','nonbreaking','directionality','pagebreak','quickbars'
    ];
    const pluginsMinimal = ['advlist','autolink','lists','link','charmap','searchreplace','wordcount','code'];

    const editors = @json($editors);

    function buildConfig(cfg) {
        const isMinimal = cfg.toolbar === 'minimal';
        return {
            selector: cfg.selector,
            height: cfg.height || (isMinimal ? 300 : 550),
            min_height: isMinimal ? 200 : 450,
            base_url: '/tinymce',
            suffix: '.min',
            license_key: 'gpl',
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
            resize: true,
            fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
            font_family_formats: 'Inter=Inter,sans-serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif;',
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre; Blockquote=blockquote;',
            link_default_target: '_blank',
            link_assume_external_targets: 'https',
            table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Python', value: 'python' },
                { text: 'SQL', value: 'sql' },
                { text: 'Bash', value: 'bash' },
            ],
            menubar: isMinimal ? false : 'file edit view insert format tools table help',
            plugins: isMinimal ? pluginsMinimal : pluginsFull,
            toolbar_mode: 'wrap',
            toolbar_sticky: true,
            toolbar_sticky_offset: 60,
            toolbar: isMinimal ? toolbarMinimal : toolbarFull1,
            toolbar2: isMinimal ? '' : toolbarFull2,
            toolbar3: isMinimal ? '' : toolbarFull3,
            quickbars_selection_toolbar: isMinimal ? '' : 'bold italic underline | h2 h3 | blockquote link',
            quickbars_insert_toolbar: false,
            setup: function(editor) {
                editor.on('change', function() { editor.save(); });
            }
        };
    }

    function tryInit(cfg) {
        if (!window.tinymce) return;
        const editorId = cfg.selector.replace('#', '');
        if (tinymce.get(editorId)) return;

        const el = document.querySelector(cfg.selector);
        if (!el) return;

        // Cek apakah element atau parent-nya tersembunyi (x-show / display:none)
        // Jika tersembunyi, tunggu sampai visible
        function isHidden(node) {
            while (node && node !== document.body) {
                if (getComputedStyle(node).display === 'none') return node;
                node = node.parentElement;
            }
            return null;
        }

        const hiddenParent = isHidden(el);
        if (hiddenParent) {
            const obs = new MutationObserver(function() {
                if (!isHidden(el)) {
                    obs.disconnect();
                    setTimeout(function() { tryInit(cfg); }, 100);
                }
            });
            obs.observe(hiddenParent, { attributes: true, attributeFilter: ['style', 'class'] });
            // Juga observe parent dari parent (Alpine x-show menambah style di element itu sendiri)
            if (hiddenParent.parentElement) {
                obs.observe(hiddenParent.parentElement, { attributes: true, attributeFilter: ['style', 'class'] });
            }
            return;
        }

        tinymce.init(buildConfig(cfg));
    }

    function initAll() {
        if (!window.tinymce) {
            setTimeout(initAll, 200);
            return;
        }
        editors.forEach(function(cfg) { tryInit(cfg); });

        // Sync ke textarea saat form submit
        const form = document.querySelector('form');
        if (form && !form.dataset.tinymceBound) {
            form.dataset.tinymceBound = '1';
            form.addEventListener('submit', function() {
                if (window.tinymce) tinymce.triggerSave();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { setTimeout(initAll, 150); });
    } else {
        setTimeout(initAll, 150);
    }
})();
</script>
