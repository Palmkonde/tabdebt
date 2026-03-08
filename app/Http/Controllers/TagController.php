<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        $tags = Tag::whereHas('websites', function ($query) use ($user) {
           $query->whereIn('group_id', $user->groups()->pluck('id'));
        })->get();

        return view('tags.index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $tag = Tag::where('id', $id)->whereHas('websites', function ($query) use ($user) {
            $query->whereIn('group_id', $user->groups()->pluck('id'));
        })->firstOrFail();

        $websites = $tag->websites()->whereHas('group', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('tags.show', [
            'tag' => $tag,
            'websites' => $websites,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
