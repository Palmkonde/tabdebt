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
        $user = auth()->user();

        $groupIds = $user->groups()->pluck('id');

        $tags = Tag::where(function ($query) use ($groupIds) {
            $query->whereHas('websites', function ($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })->orWhereHas('groups', function ($q) use ($groupIds) {
                $q->whereIn('groups.id', $groupIds);
            });
        })
            ->withCount([
                'websites' => function ($q) use ($groupIds) {
                    $q->whereIn('group_id', $groupIds);
                },
                'groups' => function ($q) use ($groupIds) {
                    $q->whereIn('groups.id', $groupIds);
                },
            ])
            ->get();

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
        $groupIds = $user->groups()->pluck('id');

        $tag = $this->findAuthorizedTag($id);

        $websites = $tag->websites()->whereIn('group_id', $groupIds)
            ->with('tags')
            ->get();

        $groups = $tag->groups()->whereIn('groups.id', $groupIds)
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

        return redirect()->route('tags.show', $tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = $this->findAuthorizedTag($id);

        $tag->delete();

        return redirect()->route('tags.index');
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
    }

    private function findAuthorizedTag($id)
    {
        $user = auth()->user();
        $groupIds = $user->groups()->pluck('id');

        return Tag::where('id', $id)->where(function ($query) use ($groupIds) {
            $query->whereHas('websites', function ($q) use ($groupIds) {
                $q->whereIn('group_id', $groupIds);
            })->orWhereHas('groups', function ($q) use ($groupIds) {
                $q->whereIn('groups.id', $groupIds);
            });
        })->firstOrFail();
    }
}
