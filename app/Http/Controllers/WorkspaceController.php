<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\Tag;

class WorkspaceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tags = Tag::whereHas('groups', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orWhereHas('websites', function ($query) use ($user) {
            $query->whereIn('group_id', $user->group()->pluck('id'));
        })->get();

        return view('workspace.index', [
            'name' => $user->name,
            'websiteCount' => Website::whereIn('group_id', $user->group()->pluck('id'))->count(),
            'groupCount' => $user->group()->count(),
            'recentWebsites' => Website::whereIn('group_id', $user->group()->pluck('id'))
                ->latest()
                ->take(3)
                ->get(),
            'groups' => $user->group()->withCount('websites')->get(),
            'tags' => $tags,
        ]);
    }
}
