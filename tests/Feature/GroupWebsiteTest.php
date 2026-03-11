<?php

use App\Models\Group;
use App\Models\User;
use App\Models\Website;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->defaultGroup = $this->user->groups()->where('name', 'Other')->first();
});

it('requires authentication', function () {
    $this->delete('/groups/1/websites/1')->assertRedirect('/login');
});

it('removes a website from a group by moving it to Other', function () {
    $group = Group::factory()->create(['user_id' => $this->user->id]);
    $website = Website::factory()->create(['group_id' => $group->id]);

    $this->actingAs($this->user)
        ->delete("/groups/{$group->id}/websites/{$website->id}")
        ->assertRedirect(route('groups.index'))
        ->assertSessionHas('success', 'Website removed from group.');

    expect($website->fresh()->group_id)->toBe($this->defaultGroup->id);
});
