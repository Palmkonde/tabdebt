<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $user = auth()->user();
        $websites = Website::whereIn('group_id', $user->group()->pluck('id'))->get();

        return view('websites.index', [
            'websites' => $websites
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = auth()->user()->group()->get();
        return view('websites.create', [
            'groups' => $groups
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'nullable|string',
            'rating' => 'required|in:bad,average,good',
            'group_id' => 'required|exists:groups,id'
        ]);
        
        if(!auth()->user()->isOwnerOfGroup($request->group_id)) {
            abort(403, 'Unauthorized action.');
        }

        Website::create([
            'name' => $request->name,
            'url' => $request->url,
            'description' => $request->description,
            'rating' => $request->rating,
            'group_id' => $request->group_id
        ]);
        
        
        return redirect()->route('websites.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $website = Website::findOrFail($id);
        $user = auth()->user();

        if(!$user->isOwnerOfGroup($website->group_id)) {
            abort(403, 'Unauthorized action.');
        }
        
        if(!$website) {
            alert('Website not found');
            return redirect()->route('websites.index');
        }
        
        return view('websites.edit', [
            'website' => $website,
            'groups' => $user->group()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'nullable|string',
            'rating' => 'required|in:bad,average,good',
            'group_id' => 'required|exists:groups,id'
        ]);

        $website = Website::findOrFail($id);
        $user = auth()->user();

        if(!$user->isOwnerOfGroup($website->group_id)) {
            abort(403, 'Unauthorized action.');
        }

        $website->update([
            'name' => $request->name,
            'url' => $request->url,
            'description' => $request->description,
            'rating' => $request->rating,
            'group_id' => $request->group_id
        ]);

        return redirect()->route('websites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
