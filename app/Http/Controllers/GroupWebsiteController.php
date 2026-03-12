<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Website;

class GroupWebsiteController extends Controller
{
    public function removeWebsite(Group $group, Website $website)
    {
        $user = auth()->user();

        if (! $user->isOwnerOfGroup($group->id) || ! $user->isOwnerOfGroup($website->group_id)) {
            abort(403, 'Unauthorized action.');
        }

        $defaultGroup = $this->findDefaultGroup();
        $website->update(['group_id' => $defaultGroup->id]);

        return redirect()->route('groups.index')->with('success', 'Website removed from group.');
    }

    private function findDefaultGroup()
    {
        $user = auth()->user();

        return $user->groups()->where('name', 'Other')->firstOrFail();
    }
}
