{{--
    Shared TinyMCE dark-mode CSS — include via @include('admin.partials.tinymce-styles')
    inside @section('styles') of any admin form that uses TinyMCE.
--}}
<style>
.tox-tinymce { border-radius: 0.5rem !important; }
html.dark .tox,
html.dark .tox-editor-header,
html.dark .tox-statusbar { background-color: rgb(31,41,55) !important; border-color: rgb(55,65,81) !important; }
html.dark .tox-toolbar__primary { background-color: rgb(31,41,55) !important; background-image: none !important; }
html.dark .tox-statusbar__text-container { color: rgb(156,163,175) !important; }
html.dark .tox-edit-area__iframe { background-color: rgb(31,41,55) !important; }
html.dark .tox-mbtn__select-label,
html.dark .tox-tbtn { color: rgb(209,213,219) !important; }
html.dark .tox-tbtn:hover,
html.dark .tox-tbtn--enabled { background-color: rgb(55,65,81) !important; }
html.dark .tox-split-button:hover { box-shadow: 0 0 0 1px rgb(55,65,81) inset !important; }
html.dark .tox-collection--list .tox-collection__item--active,
html.dark .tox-collection--toolbar .tox-collection__item--enabled,
html.dark .tox-collection--toolbar .tox-collection__item--active { background-color: rgb(55,65,81) !important; }
html.dark .tox-collection--list .tox-collection__item--enabled { background-color: rgb(55,65,81) !important; color: rgb(59,130,246) !important; }
html.dark .tox-menu,
html.dark .tox-dialog,
html.dark .tox-dialog__header,
html.dark .tox-dialog__body,
html.dark .tox-dialog__footer { background-color: rgb(31,41,55) !important; border-color: rgb(55,65,81) !important; }
html.dark .tox-dialog__title { color: rgb(243,244,246) !important; }
html.dark .tox-label { color: rgb(209,213,219) !important; }
html.dark .tox-textfield,
html.dark .tox-listboxfield .tox-listbox--select { background-color: rgb(55,65,81) !important; border-color: rgb(75,85,99) !important; color: rgb(243,244,246) !important; }
html.dark .tox-textfield:focus { border-color: rgb(59,130,246) !important; }
html.dark .tox-button--naked { color: rgb(209,213,219) !important; }
html.dark .tox-button--naked:hover { background-color: rgb(55,65,81) !important; }
html.dark .tox-button--secondary { background-color: rgb(55,65,81) !important; border-color: rgb(75,85,99) !important; color: rgb(243,244,246) !important; }
html.dark .tox-button--secondary:hover { background-color: rgb(75,85,99) !important; }
html.dark .tox-collection__group-heading { background-color: rgb(55,65,81) !important; color: rgb(156,163,175) !important; }
html.dark .tox-collection__item-label { color: rgb(209,213,219) !important; }
html.dark .tox-collection__item-accessory { color: rgb(107,114,128) !important; }
html.dark .tox-collection__item--active .tox-collection__item-label { color: rgb(255,255,255) !important; }
html.dark .tox-collection__item-caret svg,
html.dark .tox-tbtn svg,
html.dark .tox-swatches__picker-btn svg { fill: rgb(209,213,219) !important; }
html.dark .tox-tbtn--enabled svg { fill: rgb(59,130,246) !important; }
html.dark .tox-tbtn:hover svg { fill: rgb(255,255,255) !important; }
</style>
