@extends('layouts.admin')

@section('title', 'Visual Menu Builder')

@section('content')
<div class="space-y-6" x-data="menuBuilder()" x-init="loadMenus()">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Visual Menu Builder</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Drag and drop to organize menus and create hierarchies</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.menus.index') }}" class="px-4 py-2.5 text-gray-700 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-slate-700 transition inline-flex items-center gap-2">
                <ion-icon name="list-outline"></ion-icon>
                Table View
            </a>
            <button @click="saveMenuStructure()" class="px-6 py-2.5 text-white bg-primary-600 hover:bg-primary-700 rounded-xl font-semibold shadow-lg shadow-primary-500/20 transition-all inline-flex items-center gap-2">
                <ion-icon name="save-outline"></ion-icon>
                Save Changes
            </button>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-2xl p-4 flex items-gap-3">
        <ion-icon name="information-circle" class="text-blue-600 dark:text-blue-400 text-xl flex-shrink-0 mt-0.5"></ion-icon>
        <div class="text-sm text-blue-700 dark:text-blue-300">
            <strong>Tip:</strong> Drag menu items to reorder them or to nest them as sub-menus. Drop onto another menu to make it a child.
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Menu Tree (Left) --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6">
                <h2 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Menu Structure</h2>
                <div id="menuTree" class="space-y-2">
                    <template x-if="menus.length === 0">
                        <div class="text-center py-12 text-gray-400">
                            <ion-icon name="layers-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                            <p>No menus yet. <a href="{{ route('admin.menus.index') }}" class="text-primary-600">Create one</a></p>
                        </div>
                    </template>

                    <template x-for="menu in menus" :key="menu.id">
                        <div class="menu-item" :data-id="menu.id">
                            <div class="bg-gray-50 dark:bg-slate-800/50 rounded-lg p-4 mb-2 hover:shadow-md transition cursor-move group"
                                 draggable="true" @dragstart="dragStart($event, menu)" @dragend="dragEnd($event)">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-grow">
                                        <ion-icon name="reorder-three" class="text-gray-400 group-hover:text-primary-600"></ion-icon>
                                        <div class="flex-grow">
                                            <h3 class="font-semibold text-gray-900 dark:text-white" x-text="menu.label"></h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                <code x-text="menu.url"></code>
                                                <template x-if="menu.is_external">
                                                    <span class="ml-2 text-blue-600 dark:text-blue-400">🔗 External</span>
                                                </template>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click="toggleMenuStatus(menu)"
                                                :class="menu.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
                                                class="px-2 py-1 rounded text-xs font-medium transition-all">
                                            <template x-if="menu.is_active">
                                                <span>Active</span>
                                            </template>
                                            <template x-if="!menu.is_active">
                                                <span>Inactive</span>
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Children (Sub-items) --}}
                            <template x-if="menu.children.length > 0">
                                <div class="ml-4 space-y-2 pb-4 border-l-2 border-gray-200 dark:border-slate-700 pl-4">
                                    <template x-for="child in menu.children" :key="child.id">
                                        <div class="menu-item" :data-id="child.id" :data-parent-id="menu.id">
                                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 rounded-lg p-3 hover:shadow-md transition cursor-move group"
                                                 draggable="true" @dragstart="dragStart($event, child)" @dragend="dragEnd($event)">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3 flex-grow">
                                                        <ion-icon name="arrow-forward" class="text-primary-600 opacity-50 flex-shrink-0"></ion-icon>
                                                        <div class="flex-grow">
                                                            <h4 class="font-medium text-gray-900 dark:text-white text-sm" x-text="child.label"></h4>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <code x-text="child.url"></code>
                                                                <template x-if="child.is_external">
                                                                    <span class="ml-2 text-blue-600 dark:text-blue-400">🔗</span>
                                                                </template>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <button @click="toggleMenuStatus(child)"
                                                            :class="child.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'"
                                                            class="px-2 py-1 rounded text-xs font-medium transition-all flex-shrink-0">
                                                        <template x-if="child.is_active">
                                                            <span>Active</span>
                                                        </template>
                                                        <template x-if="!child.is_active">
                                                            <span>Inactive</span>
                                                        </template>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Sidebar (Right) --}}
        <div class="space-y-6">
            {{-- Statistics --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-slate-400">Total Menus</span>
                        <span class="font-bold text-xl text-primary-600" x-text="getTotalMenus()"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-slate-400">Active</span>
                        <span class="font-bold text-lg text-green-600" x-text="getActiveCount()"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-slate-400">With Children</span>
                        <span class="font-bold text-lg text-blue-600" x-text="getMenusWithChildren()"></span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.menus.index') }}" class="block w-full px-4 py-2.5 text-center text-primary-600 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/30 rounded-xl font-medium transition-all">
                        + Add New Menu
                    </a>
                    <button @click="saveMenuStructure()" class="w-full px-4 py-2.5 text-white bg-green-600 hover:bg-green-700 rounded-xl font-medium transition-all">
                        💾 Save All Changes
                    </button>
                </div>
            </div>

            {{-- Legend --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-soft border border-gray-100 dark:border-slate-800 p-6">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Legend</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-50 dark:bg-slate-800/50 rounded border-2 border-gray-200"></div>
                        <span class="text-gray-600 dark:text-slate-400">Main Menu</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded border-2 border-blue-100 dark:border-blue-800/30"></div>
                        <span class="text-gray-600 dark:text-slate-400">Sub Menu</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Active</span>
                        <span class="text-gray-600 dark:text-slate-400">Visible</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">Inactive</span>
                        <span class="text-gray-600 dark:text-slate-400">Hidden</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function menuBuilder() {
        return {
            menus: [],
            draggedItem: null,

            async loadMenus() {
                try {
                    const response = await fetch('{{ route("admin.menu-builder.tree") }}');
                    const data = await response.json();
                    this.menus = data.menus;
                } catch (error) {
                    console.error('Error loading menus:', error);
                }
            },

            dragStart(e, menu) {
                this.draggedItem = menu;
                e.dataTransfer.effectAllowed = 'move';
                e.target.classList.add('opacity-50');
            },

            dragEnd(e) {
                e.target.classList.remove('opacity-50');
            },

            toggleMenuStatus(menu) {
                menu.is_active = !menu.is_active;
            },

            getTotalMenus() {
                let count = this.menus.length;
                this.menus.forEach(menu => {
                    count += menu.children.length;
                });
                return count;
            },

            getActiveCount() {
                let count = this.menus.filter(m => m.is_active).length;
                this.menus.forEach(menu => {
                    count += menu.children.filter(c => c.is_active).length;
                });
                return count;
            },

            getMenusWithChildren() {
                return this.menus.filter(m => m.children.length > 0).length;
            },

            async saveMenuStructure() {
                const btn = event.target.closest('button');
                btn.disabled = true;
                btn.innerHTML = '<ion-icon name="hourglass-outline"></ion-icon> Saving...';

                try {
                    const response = await fetch('{{ route("admin.menu-builder.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ menus: this.menus })
                    });

                    const data = await response.json();

                    if (data.success) {
                        btn.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Saved!';
                        setTimeout(() => {
                            btn.disabled = false;
                            btn.innerHTML = '<ion-icon name="save-outline"></ion-icon> Save Changes';
                        }, 2000);
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = '<ion-icon name="save-outline"></ion-icon> Save Changes';
                    }
                } catch (error) {
                    alert('Error saving: ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = '<ion-icon name="save-outline"></ion-icon> Save Changes';
                }
            }
        }
    }
</script>

@endsection
