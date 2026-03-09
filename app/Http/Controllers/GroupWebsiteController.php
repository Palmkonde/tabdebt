<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupWebsiteController extends Controller
{
    public function removeWebsite(Group $group, Website $website)
    {
        // Remove from current group and change it to "Other"
        $user = auth()->user();
        $defaultGroup = $this->findDefaultGroup();
        $website->update(['group_id' => $defaultGroup->id]);

        return redirect()->route('groups.index');
    }
    
    private function findDefaultGroup()
    {
        $user = auth()->user();
        return $user->groups()->where('name', 'Other')->firstOrFail();
    }
}
