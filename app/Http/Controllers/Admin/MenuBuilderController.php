<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuBuilderController extends Controller
{
    /**
     * Display the visual menu builder interface
     */
    public function index()
    {
        $menus = Menu::with('children')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('admin.menu-builder.index', compact('menus'));
    }

    /**
     * Get menu tree structure as JSON (for AJAX)
     */
    public function getMenuTree()
    {
        $menus = Menu::with('children:id,label,url,order,is_active,is_external,parent_id')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->map(function ($menu) {
                return $this->formatMenuNode($menu);
            });

        return response()->json(['menus' => $menus]);
    }

    /**
     * Format menu node for tree display
     */
    private function formatMenuNode($menu)
    {
        return [
            'id' => $menu->id,
            'label' => $menu->label,
            'url' => $menu->url,
            'order' => $menu->order,
            'is_active' => $menu->is_active,
            'is_external' => $menu->is_external,
            'children' => $menu->children->map(fn($child) => $this->formatMenuNode($child))->values(),
        ];
    }

    /**
     * Update entire menu tree structure
     */
    public function updateMenuTree(Request $request)
    {
        $validated = $request->validate([
            'menus' => 'required|array',
        ]);

        try {
            $this->processMenuTree($validated['menus'], null);

            return response()->json([
                'success' => true,
                'message' => 'Menu structure updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu structure: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Recursively process menu tree
     */
    private function processMenuTree($menus, $parentId = null, $order = 0)
    {
        foreach ($menus as $menuData) {
            $menu = Menu::find($menuData['id']);
            
            if ($menu) {
                $menu->update([
                    'order' => $order,
                    'parent_id' => $parentId,
                ]);

                if (!empty($menuData['children'])) {
                    $this->processMenuTree($menuData['children'], $menu->id, 0);
                }
            }

            $order++;
        }
    }
}
