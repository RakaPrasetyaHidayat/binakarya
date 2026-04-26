<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::ordered()->get();
        return view('admin.team-members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'linkedin' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team-photos', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        TeamMember::create($validated);

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'linkedin' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('photo')) {
            if ($teamMember->photo) {
                Storage::disk('public')->delete($teamMember->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team-photos', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $teamMember->update($validated);

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->photo) {
            Storage::disk('public')->delete($teamMember->photo);
        }
        $teamMember->delete();
        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
