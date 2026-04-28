@extends('layouts.admin')

@section('title', isset($service) ? 'Edit Layanan' : 'Tambah Layanan')

@section('styles')
@include('admin.partials.tinymce-styles')
@endsection

@section('content')
@php
    $existingPlans = old('plans', isset($service) ? $service->plans->map(function ($plan) {
        return [
            'name' => $plan->name,
            'subtitle' => $plan->subtitle,
            'price' => (string) $plan->price,
            'features_text' => is_array($plan->features) ? implode("\n", $plan->features) : '',
            'is_popular' => (bool) $plan->is_popular,
            'order' => (int) $plan->order,
            'is_active' => (bool) $plan->is_active,
        ];
    })->values()->all() : []);
@endphp
<div class="max-w-3xl" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.services.index') }}" :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-500 hover:text-gray-700'" class="transition">← Kembali</a>
        <h2 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-lg font-semibold">{{ isset($service) ? 'Edit Layanan' : 'Tambah Layanan' }}</h2>
    </div>

    <form method="POST"
          action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}"
          :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'"
          class="rounded-xl shadow-sm border p-6 space-y-5 transition-colors">
        @csrf
        @if(isset($service)) @method('PUT') @endif

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Judul <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}"
                   :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                   class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('title') border-red-400 @enderror">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-2">Icon <span class="text-gray-500 text-xs">(Emoji atau Unicode)</span></label>
                <div class="flex gap-2 items-center">
                    <input type="text" name="icon" id="icon-input" value="{{ old('icon', $service->icon ?? '') }}"
                           placeholder="🎯"
                           :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                           class="flex-1 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 text-center text-2xl transition @error('icon') border-red-400 @enderror">
                    <button type="button" :class="darkMode ? 'bg-slate-700 hover:bg-gray-700 text-gray-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'" class="px-3 py-2.5 rounded-lg text-sm font-medium transition" id="icon-picker-btn" title="Pilih Icon">
                        🎨
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Gunakan emoji, Font Awesome icons, atau Ionic icons</p>
                @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $service->order ?? 0) }}" min="0"
                       :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                       class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('order') border-red-400 @enderror">
                @error('order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Ringkasan</label>
            <textarea name="excerpt" rows="2"
                      :class="darkMode ? 'bg-slate-700 border-slate-600 text-gray-100 focus:ring-blue-600' : 'bg-white border-gray-300 text-gray-900 focus:ring-blue-500'"
                      class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 transition @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $service->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="block text-sm font-medium mb-1">Konten Detail</label>
            <textarea name="body" id="body-editor" class="@error('body') border-red-400 @enderror">{{ old('body', $service->body ?? '') }}</textarea>
            @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}
                       :class="darkMode ? 'bg-slate-700 border-slate-600' : ''"
                       class="w-4 h-4 text-blue-600 rounded">
                <span :class="darkMode ? 'text-gray-300' : 'text-gray-700'" class="text-sm font-medium">Aktif</span>
            </label>
        </div>

        <div :class="darkMode ? 'bg-slate-900/60 border-slate-700' : 'bg-blue-50/40 border-blue-100'" class="rounded-xl border p-4 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h3 :class="darkMode ? 'text-gray-100' : 'text-gray-800'" class="text-sm font-semibold">Paket Harga (Dinamis CMS)</h3>
                <button type="button" id="add-plan-btn" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 transition">
                    + Tambah Paket
                </button>
            </div>
            <p :class="darkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs">Setiap baris fitur dipisah Enter. Paket aktif akan tampil di halaman detail layanan.</p>
            <div id="plans-container" class="space-y-4"></div>
            <template id="plan-template">
                <div class="plan-item rounded-xl border border-gray-200 dark:border-slate-700 p-4 space-y-3 bg-white dark:bg-slate-800/70">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Paket <span class="plan-number"></span></p>
                        <button type="button" class="remove-plan-btn text-xs text-red-500 hover:text-red-600">Hapus</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input type="text" data-field="name" placeholder="Nama paket"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-gray-100">
                        <input type="text" data-field="subtitle" placeholder="Subjudul (contoh: / A5)"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-gray-100">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="number" min="0" step="1" data-field="price" placeholder="Harga"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-gray-100">
                        <input type="number" min="0" data-field="order" placeholder="Urutan"
                            class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-gray-100">
                        <label class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300">
                            <input type="checkbox" data-field="is_popular" class="rounded border-gray-300 dark:border-slate-600">
                            Tampilkan label Terbaik
                        </label>
                    </div>
                    <textarea rows="4" data-field="features_text" placeholder="Fitur paket, satu baris satu fitur"
                        class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-gray-900 dark:text-gray-100"></textarea>
                    <label class="flex items-center gap-2 text-xs text-gray-700 dark:text-gray-300">
                        <input type="checkbox" data-field="is_active" class="rounded border-gray-300 dark:border-slate-600" checked>
                        Paket aktif
                    </label>
                </div>
            </template>
        </div>

        <div :class="darkMode ? 'border-slate-700' : 'border-gray-100'" class="flex gap-3 pt-2 border-t">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                {{ isset($service) ? 'Simpan Perubahan' : 'Tambah Layanan' }}
            </button>
            <a href="{{ route('admin.services.index') }}" 
               :class="darkMode ? 'border-slate-600 text-gray-300 hover:bg-slate-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
               class="border px-6 py-2.5 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Icon Picker Modal -->
<div id="icon-picker-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl max-h-96 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Pilih Icon</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" id="close-picker">✕</button>
        </div>
        
        <div class="mb-4">
            <label class="block text-xs font-medium text-gray-600 mb-2">Cari (emoji, Font Awesome, Ionic Icons):</label>
            <input type="text" id="icon-search" placeholder="Cari icon..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div id="icon-picker-content" class="overflow-y-auto flex-1 grid grid-cols-6 gap-2 p-4 bg-gray-50 rounded-lg">
            <!-- Icons akan dimuat di sini -->
        </div>

        <div class="mt-4 text-xs text-gray-500">
            <p><strong>Tips:</strong> Anda bisa copy-paste emoji dari <a href="https://ionic.io/ionicons" target="_blank" class="text-blue-600 hover:underline">Ionic Icons</a> atau situs emoji lainnya</p>
        </div>
</div>

@include('admin.partials.tinymce-init', [
    'editors' => [
        ['selector' => '#body-editor', 'height' => 500, 'toolbar' => 'full'],
    ]
])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialPlans = @json($existingPlans);

    // Icon picker
    const iconInput = document.getElementById('icon-input');
    const pickerBtn = document.getElementById('icon-picker-btn');
    const pickerModal = document.getElementById('icon-picker-modal');
    const closeBtn = document.getElementById('close-picker');
    const pickerContent = document.getElementById('icon-picker-content');
    const searchInput = document.getElementById('icon-search');

    const ionicIconsList = [
        'academic-cap', 'accessibility', 'add', 'airplane', 'alarm', 'albums', 'alert-circle', 'analytics',
        'archive', 'at', 'award', 'balloon', 'bar-chart', 'barbell', 'basket', 'beaker', 'bed', 'beer',
        'bicycle', 'bluetooth', 'boat', 'book', 'bookmark', 'briefcase', 'brush', 'build', 'bulb', 'bus',
        'business', 'cafe', 'calculator', 'calendar', 'call', 'camera', 'car', 'card', 'cart', 'cash',
        'chatbubble', 'checkbox', 'checkmark-circle', 'chevron-forward', 'cloud-upload', 'code-working',
        'cog', 'color-palette', 'compass', 'construct', 'contract', 'cube', 'cut', 'desktop', 'diamond',
        'document-text', 'earth', 'easel', 'education', 'egg', 'extension-puzzle', 'eye', 'file-tray-full',
        'film', 'filter', 'finger-print', 'fitness', 'flag', 'flask', 'folder', 'football', 'funnel',
        'game-controller', 'gift', 'git-branch', 'git-commit', 'git-compare', 'git-merge', 'git-pull-request',
        'glasses', 'globe', 'grid', 'hammer', 'hand-left', 'happy', 'headset', 'heart', 'help-circle',
        'home', 'hourglass', 'ice-cream', 'image', 'infinite', 'information-circle', 'journal', 'key',
        'keypad', 'language', 'laptop', 'layers', 'leaf', 'library', 'list', 'locate', 'location',
        'lock-closed', 'log-in', 'magnet', 'mail', 'male', 'man', 'map', 'medal', 'medical', 'megaphone',
        'menu', 'mic', 'moon', 'musical-notes', 'newspaper', 'notifications', 'nutrition', 'options',
        'paper-plane', 'partly-sunny', 'pause', 'paw', 'pencil', 'people', 'person', 'phone-portrait',
        'pie-chart', 'pin', 'pint', 'pizza', 'planet', 'play', 'podium', 'power', 'pricetag', 'print',
        'pulse', 'push', 'qr-code', 'radio', 'rainy', 'reader', 'receipt', 'recording', 'refresh',
        'remove', 'reorder-three', 'repeat', 'resize', 'restaurant', 'rocket', 'rose', 'save',
        'scan', 'school', 'search', 'send', 'settings', 'shapes', 'share', 'shield-checkmark',
        'shirt', 'shuffle', 'skull', 'snow', 'speedometer', 'star', 'stats-chart', 'stopwatch',
        'sunny', 'swimmer', 'tablet-landscape', 'tag', 'telescope', 'terminal', 'thermometer',
        'thumbs-up', 'thunderstorm', 'ticket', 'time', 'timer', 'today', 'trash', 'trophy',
        'tv', 'umbrella', 'videocam', 'wallet', 'watch', 'water', 'wifi', 'wine', 'woman'
    ];

    function loadIconPicker() {
        const query = searchInput.value.toLowerCase();
        const filtered = ionicIconsList.filter(icon => icon.includes(query));
        
        pickerContent.innerHTML = filtered.map(icon => `
            <button type="button" class="p-3 hover:bg-blue-50 rounded-lg flex flex-col items-center gap-2 border border-transparent hover:border-blue-100 transition" data-icon="${icon}">
                <ion-icon name="${icon}" class="text-3xl text-gray-700"></ion-icon>
                <span class="text-[10px] text-gray-400 truncate w-full text-center">${icon}</span>
            </button>
        `).join('');

        pickerContent.querySelectorAll('[data-icon]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                iconInput.value = this.dataset.icon;
                pickerModal.classList.add('hidden');
                iconInput.focus();
            });
        });
    }

    pickerBtn.addEventListener('click', function(e) {
        e.preventDefault();
        pickerModal.classList.remove('hidden');
        searchInput.focus();
        loadIconPicker();
    });

    closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        pickerModal.classList.add('hidden');
    });

    searchInput.addEventListener('input', function() {
        loadIconPicker();
    });

    pickerModal.addEventListener('click', function(e) {
        if (e.target === this) {
            pickerModal.classList.add('hidden');
        }
    });

    // Dynamic service plans builder
    const plansContainer = document.getElementById('plans-container');
    const planTemplate = document.getElementById('plan-template');
    const addPlanBtn = document.getElementById('add-plan-btn');

    function renderPlanIndexes() {
        plansContainer.querySelectorAll('.plan-item').forEach((item, index) => {
            item.querySelector('.plan-number').textContent = index + 1;
            item.querySelectorAll('[data-field]').forEach((fieldEl) => {
                const field = fieldEl.dataset.field;
                if (fieldEl.type === 'checkbox') {
                    fieldEl.name = `plans[${index}][${field}]`;
                    if (field === 'is_active') {
                        const hidden = item.querySelector('.is-active-hidden');
                        hidden.name = `plans[${index}][${field}]`;
                    }
                    if (field === 'is_popular') {
                        const hidden = item.querySelector('.is-popular-hidden');
                        hidden.name = `plans[${index}][${field}]`;
                    }
                } else {
                    fieldEl.name = `plans[${index}][${field}]`;
                }
            });
        });
    }

    function addPlan(planData = {}) {
        const fragment = planTemplate.content.cloneNode(true);
        const planItem = fragment.querySelector('.plan-item');
        const activeCheckbox = planItem.querySelector('[data-field="is_active"]');

        const hiddenIsActive = document.createElement('input');
        hiddenIsActive.type = 'hidden';
        hiddenIsActive.value = '0';
        hiddenIsActive.className = 'is-active-hidden';
        planItem.appendChild(hiddenIsActive);

        planItem.querySelectorAll('[data-field]').forEach((el) => {
            const field = el.dataset.field;
            if (el.type === 'checkbox') {
                el.checked = Boolean(planData[field] ?? (field === 'is_active'));
                if (field === 'is_popular') {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.value = '0';
                    planItem.appendChild(hidden);
                    hidden.className = 'is-popular-hidden';
                }
            } else {
                el.value = planData[field] ?? '';
            }
        });

        planItem.querySelector('.remove-plan-btn').addEventListener('click', () => {
            planItem.remove();
            renderPlanIndexes();
        });

        activeCheckbox.addEventListener('change', () => {
            hiddenIsActive.value = activeCheckbox.checked ? '1' : '0';
        });
        hiddenIsActive.value = activeCheckbox.checked ? '1' : '0';

        const popularCheckbox = planItem.querySelector('[data-field="is_popular"]');
        const hiddenIsPopular = planItem.querySelector('.is-popular-hidden');
        popularCheckbox.addEventListener('change', () => {
            hiddenIsPopular.value = popularCheckbox.checked ? '1' : '0';
        });
        hiddenIsPopular.value = popularCheckbox.checked ? '1' : '0';

        plansContainer.appendChild(planItem);
        renderPlanIndexes();
    }

    addPlanBtn.addEventListener('click', () => addPlan());
    if (Array.isArray(initialPlans) && initialPlans.length > 0) {
        initialPlans.forEach((plan) => addPlan(plan));
    } else {
        addPlan();
    }
});
</script>

<style>
#icon-picker-modal { animation: fadeIn 0.2s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
#icon-picker-content button:hover { transform: scale(1.2); }
</style>
@endsection
