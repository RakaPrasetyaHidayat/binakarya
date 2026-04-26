<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->get();
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('admin.menus.index', compact('menus', 'parentMenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer',
            'target' => 'required|in:_self,_blank',
            'is_external' => 'boolean',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:50',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->uploader->upload($request->file('thumbnail'), 'menu-thumbnails');
        }

        Menu::create($validated);

        return redirect()->back()->with('success', 'Menu item created successfully.');
    }

    public function update(Request $request, Menu $menu)
    {
        $rules = [
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer',
            'target' => 'required|in:_self,_blank',
            'is_external' => 'boolean',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:50',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $validated = $request->validate($rules);
        unset($validated['thumbnail']);

        $validated['is_external'] = $request->has('is_external');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($menu->thumbnail) {
                $this->uploader->delete($menu->thumbnail);
            }
            $validated['thumbnail'] = $this->uploader->upload($request->file('thumbnail'), 'menu-thumbnails');
        }

        $menu->update($validated);

        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->back()->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Toggle menu active status (AJAX)
     */
    public function toggleActive(Menu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $menu->is_active,
            'message' => $menu->is_active ? 'Menu activated' : 'Menu deactivated'
        ]);
    }

    /**
     * Reorder menu items (AJAX)
     */
    public function reorder(Request $request)
    {
        $items = $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:menus,id'
        ]);

        foreach ($items['items'] as $order => $id) {
            Menu::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true, 'message' => 'Menus reordered successfully']);
    }
}
