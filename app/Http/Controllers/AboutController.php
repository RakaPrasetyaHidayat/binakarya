<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function index()
    {
        $managers = Manager::where('is_active', true)->ordered()->get();
        $teamMembers = TeamMember::active()->ordered()->get();
        
        return view('public.about', compact('managers', 'teamMembers'));
    }
}
