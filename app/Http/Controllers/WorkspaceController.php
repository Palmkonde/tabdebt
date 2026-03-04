<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;

class WorkspaceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('workspace.index', [
            'name' => $user->name,
            'websiteCount' => Website::whereIn('group_id', $user->group()->pluck('id'))->count(),
            'groupCount' => $user->group()->count(),
        ]);
    }
}
