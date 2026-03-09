<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Tag;
use App\Models\Website;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $groups = $user->groups()->with(['tags', 'websites.tags'])->get();

        return view('groups.index', [
            'groups' => $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.create', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validate = $this->validateGroup($request);

        $group = Group::create(
            collect($validate)->except('tags')->merge(['user_id' => $user->id])->toArray());

        $group->tags()->sync($request->input('tags', []));

        return redirect()->route('groups.index');
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
        $group = $this->findAuthorizedGroup($id);
        $defaultGroup = $this->findDefaultGroup();
        $group->load('tags');
        
        if($group->id === $defaultGroup->id) {
            abort(403, 'Unable to edit the default group.');
            return redirect()->route('groups.index');
        }

        return view('groups.edit', [
            'group' => $group,
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $group = $this->findAuthorizedGroup($id);
        $validate = $this->validateGroup($request);
        $defaultGroup = $this->findDefaultGroup();

        // Update other data in "Other" group
        $group->update(collect($validate)->except('tags')->toArray());

        // Prevent to update tags in "Other" group
        if ($group->id === $defaultGroup->id) {
            abort(403, 'Unable to update tags into the default group.');

            return redirect()->route('groups.index');
        }

        $group->tags()->sync($request->input('tags', []));

        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $group = $this->findAuthorizedGroup($id);

        // Delete the group and move all its websites to "Other" group
        $defaultGroup = $this->findDefaultGroup();

        // Prevent deletion of the default group
        if ($group->id === $defaultGroup->id) {
            abort(403, 'Unable to delete the default group.');

            return redirect()->route('groups.index');
        }

        foreach ($group->websites as $website) {
            $website->update(['group_id' => $defaultGroup->id]);
        }

        $group->delete();

        return redirect()->route('groups.index');
    }

    public function removeWebsite(Group $group, Website $website)
    {
        // Remove from current group and change it to "Other"
        $user = auth()->user();
        $defaultGroup = $this->findDefaultGroup();
        $website->update(['group_id' => $defaultGroup->id]);

        return redirect()->route('groups.index');
    }

    private function validateGroup(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);
    }

    private function findAuthorizedGroup(string $id)
    {
        $user = auth()->user();

        return Group::where('id', $id)->where('user_id', $user->id)->firstOrFail();

    }

    private function findDefaultGroup()
    {
        return auth()->user()->groups()->where('name', 'Other')->firstOrFail();
    }
}
