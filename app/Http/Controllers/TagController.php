<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tags.index');
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
        $validated = $this->validateRequest($request);

        $tag = Tag::create($validated);

        // Return JSON for inline Tom Select AJAX creation, otherwise redirect.
        if ($request->expectsJson()) {
            return response()->json($tag);
        }

        return redirect()->route('tags.index')->with('success', 'Tag created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();
        $tag = $this->findAuthorizedTag($id);

        $websites = $tag->websites()->forUser($user)
            ->with('tags')
            ->get();

        $groups = $tag->groups()->whereIn('groups.id', $user->groups()->pluck('id'))
            ->withCount('websites')
            ->with('tags')
            ->get();

        return view('tags.show', [
            'tag' => $tag,
            'websites' => $websites,
            'groups' => $groups,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = $this->findAuthorizedTag($id);

        return view('tags.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tag = $this->findAuthorizedTag($id);
        $validated = $this->validateRequest($request);

        $tag->update($validated);

        return redirect()->route('tags.show', $tag)->with('success', 'Tag updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = $this->findAuthorizedTag($id);

        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Tag deleted.');
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'sometimes|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
    }

    private function findAuthorizedTag($id)
    {
        return Tag::visibleToUser(auth()->user())->findOrFail($id);
    }
}
