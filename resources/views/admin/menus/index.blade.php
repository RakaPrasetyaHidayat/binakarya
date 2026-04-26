@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@section('content')
<div class="space-y-6" x-data="menuManager()">
    {{-- Add Menu Item form --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6 transition-colors duration-300">
        <h2 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Tambah Menu Navigasi</h2>
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Label</label>
                <input type="text" name="label" required placeholder="Contoh: Beranda" 
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">URL / Route</label>
                <input type="text" name="url" required placeholder="Contoh: /blog atau https://..." 
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Urutan</label>
                <input type="number" name="order" value="0" required
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Target</label>
                <select name="target" class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                    <option value="_self">Halaman Sama (_self)</option>
                    <option value="_blank">Tab Baru (_blank)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Icon</label>
                <input type="text" name="icon" placeholder="Contoh: shield-outline" 
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Subtitle</label>
                <input type="text" name="subtitle" placeholder="Deskripsi singkat menu" 
                       class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Deskripsi</label>
                <textarea name="description" rows="1" placeholder="Teks pendek untuk dropdown menu" class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" 
                       class="w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition">
            </div>
            <div class="lg:col-span-1 flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_external" value="1" class="rounded text-primary-600 focus:ring-primary-500 dark:bg-slate-800 dark:border-slate-700">
                    <span class="text-sm text-gray-600 dark:text-slate-400">Link Eksternal</span>
                </label>
            </div>
            <div class="lg:col-span-2 flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
                    <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>

    {{-- Menu List --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors duration-300">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
            <h2 class="font-bold text-gray-900 dark:text-white text-lg">Daftar Menu Navigasi ({{ $menus->count() }})</h2>
            <div class="flex gap-2 text-xs text-gray-500">
                <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-1 rounded">Aktif: {{ $menus->where('is_active', true)->count() }}</span>
                <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400 px-2 py-1 rounded">Nonaktif: {{ $menus->where('is_active', false)->count() }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="menuTable">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold w-12">
                            <ion-icon name="swap-vertical" class="text-gray-400"></ion-icon>
                        </th>
                        <th class="px-6 py-4 font-semibold">Label</th>
                        <th class="px-6 py-4 font-semibold">URL</th>
                        <th class="px-6 py-4 font-semibold text-center w-20">Urutan</th>
                        <th class="px-6 py-4 font-semibold text-center w-24">Status</th>
                        <th class="px-6 py-4 font-semibold text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800" id="menuTableBody">
                    @forelse($menus as $menu)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors" data-menu-id="{{ $menu->id }}" data-icon="{{ $menu->icon }}" data-subtitle="{{ $menu->subtitle }}" data-description="{{ $menu->description }}" data-thumbnail="{{ $menu->thumbnail }}" data-parent="{{ $menu->parent_id }}" data-target="{{ $menu->target }}" data-is-external="{{ $menu->is_external ? 1 : 0 }}">
                        <td class="px-6 py-4 text-gray-400 cursor-grab hover:cursor-grabbing">
                            <ion-icon name="reorder-three" class="text-lg"></ion-icon>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900 dark:text-slate-200 pl-{{ $menu->parent_id ? '4' : '0' }}">
                                    @if($menu->parent_id)
                                        <span class="text-gray-400 mr-2">↳</span>
                                    @endif
                                    {{ $menu->label }}
                                </span>
                                @if($menu->parent)
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Sub-menu of: {{ $menu->parent->label }}</span>
                                @endif
                                @if($menu->children->count() > 0)
                                    <span class="text-[10px] text-primary-600 dark:text-primary-400 uppercase tracking-wider">{{ $menu->children->count() }} sub-item(s)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-xs bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded text-primary-600 dark:text-primary-400">
                                {{ Str::limit($menu->url, 40) }}
                                @if($menu->is_external)
                                    <span class="ml-1 text-blue-600 dark:text-blue-400" title="External Link">🔗</span>
                                @endif
                            </code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-gray-600 dark:text-slate-400 font-medium">{{ $menu->order }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" 
                                    @click="toggleMenuActive({{ $menu->id }}, $event)"
                                    class="px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 inline-flex items-center gap-1"
                                    :class="isActive($event) ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 hover:bg-green-200' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200'"
                                    :data-active="{{ $menu->is_active ? 'true' : 'false' }}">
                                <ion-icon name="{{ $menu->is_active ? 'checkmark-circle' : 'close-circle' }}" class="text-sm"></ion-icon>
                                <span>{{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                        @click="showEditModal({{ $menu->id }})"
                                        title="Edit Menu" 
                                        class="p-2 text-gray-400 hover:text-primary-600 transition rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </button>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
                                        <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-slate-500">
                            <ion-icon name="list-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                            <p>Belum ada item menu navigasi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editMenuModal" class="hidden fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="closeEditModal()"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full p-8 transition-colors duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Menu</h2>
                <button type="button" onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <ion-icon name="close" class="text-2xl"></ion-icon>
                </button>
            </div>
            
            <form id="editMenuForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Label *</label>
                        <input type="text" name="label" id="editLabel" required
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">URL / Route *</label>
                        <input type="text" name="url" id="editUrl" required
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Urutan</label>
                        <input type="number" name="order" id="editOrder" required
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Target</label>
                        <select name="target" id="editTarget"
                                class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                            <option value="_self">Halaman Sama (_self)</option>
                            <option value="_blank">Tab Baru (_blank)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Parent Menu</label>
                        <select name="parent_id" id="editParentId"
                                class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                            <option value="">Tanpa Parent</option>
                            @foreach($parentMenus as $p)
                                <option value="{{ $p->id }}">{{ $p->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Icon</label>
                        <input type="text" name="icon" id="editIcon" placeholder="Contoh: shield-outline"
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" id="editSubtitle" placeholder="Deskripsi singkat menu"
                               class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Deskripsi</label>
                    <textarea name="description" id="editDescription" rows="2" placeholder="Teks pendek untuk dropdown menu"
                              class="w-full rounded-xl border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-4 py-2.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Thumbnail</label>
                    <input type="file" name="thumbnail" id="editThumbnail" accept="image/*"
                           class="w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition">
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-6 py-2.5 text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-slate-700 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 text-white bg-primary-600 hover:bg-primary-700 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all flex items-center gap-2">
                        <ion-icon name="checkmark-outline"></ion-icon>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    let editingMenuId = null;

    function menuManager() {
        return {
            init() {
                this.initSortable();
            },

            initSortable() {
                const tbody = document.getElementById('menuTableBody');
                if (!tbody) return;

                Sortable.create(tbody, {
                    handle: 'ion-icon[name="reorder-three"]',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => this.saveMenuOrder()
                });
            },

            saveMenuOrder() {
                const items = [];
                document.querySelectorAll('#menuTableBody tr').forEach(row => {
                    const id = row.dataset.menuId;
                    if (id) items.push(parseInt(id));
                });

                fetch('{{ route("admin.menus.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ items })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        console.log('Menu order updated');
                    }
                })
                .catch(err => console.error('Error reordering:', err));
            }
        }
    }

    function showEditModal(menuId) {
        editingMenuId = menuId;
        const form = document.getElementById('editMenuForm');
        const row = document.querySelector(`tr[data-menu-id="${menuId}"]`);
        
        if (!row) return;

        // Extract data from table row
        const cells = row.querySelectorAll('td');
        const label = cells[1].querySelector('.font-medium').textContent.replace('↳', '').trim();
        const url = cells[2].querySelector('code').textContent.trim().split('🔗')[0].trim();
        const order = cells[3].textContent.trim();
        const isActive = cells[4].querySelector('button').dataset.active === 'true';

        // Populate form
        document.getElementById('editLabel').value = label;
        document.getElementById('editUrl').value = url;
        document.getElementById('editOrder').value = order;
        document.getElementById('editIsActive').checked = isActive;
        document.getElementById('editIsExternal').checked = url.includes('http');

        // Set form action
        form.action = `{{ url('cendikiaByRidwanullah/menus') }}/${menuId}`;
        form.style.display = 'block';

        // Show modal
        document.getElementById('editMenuModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editMenuModal').classList.add('hidden');
        editingMenuId = null;
    }

    function toggleMenuActive(menuId, event) {
        const button = event.target.closest('button');
        
        fetch(`{{ url('cendikiaByRidwanullah/menus') }}/${menuId}/toggle-active`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const isNowActive = data.is_active;
                button.dataset.active = isNowActive;
                
                // Update button appearance
                const icon = button.querySelector('ion-icon');
                const text = button.querySelector('span');
                
                if (isNowActive) {
                    button.classList.remove('bg-gray-100', 'dark:bg-gray-800', 'text-gray-600', 'dark:text-gray-400');
                    button.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-700', 'dark:text-green-400');
                    icon.setAttribute('name', 'checkmark-circle');
                    text.textContent = 'Aktif';
                } else {
                    button.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-700', 'dark:text-green-400');
                    button.classList.add('bg-gray-100', 'dark:bg-gray-800', 'text-gray-600', 'dark:text-gray-400');
                    icon.setAttribute('name', 'close-circle');
                    text.textContent = 'Nonaktif';
                }
            }
        })
        .catch(err => console.error('Error toggling status:', err));
    }

    function isActive(event) {
        return event.target.closest('button').dataset.active === 'true';
    }

    // Close modal when clicking outside
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('editMenuModal');
        if (e.target === modal) {
            closeEditModal();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        const manager = menuManager();
        manager.init();
    });

    // Keyboard shortcut: Esc to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('editMenuModal').classList.contains('hidden')) {
            closeEditModal();
        }
    });
</script>

@endsection
