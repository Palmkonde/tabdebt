<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
});

it('searches groups by name', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools', 'description' => 'Coding utilities']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Design', 'description' => 'Art resources']);

    $results = Group::search('Dev')->pluck('name')->all();

    expect($results)->toContain('Dev Tools')->not->toContain('Design');
});

it('searches groups by description', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Group A', 'description' => 'Development resources']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Group B', 'description' => 'Design inspiration']);

    $results = Group::search('Development')->pluck('name')->all();

    expect($results)->toContain('Group A')->not->toContain('Group B');
});

it('returns all groups when search is empty', function () {
    Group::factory()->create(['user_id' => $this->user->id]);

    $results = Group::search('')->get();

    expect($results->count())->toBeGreaterThanOrEqual(2);
});

it('filters groups by tag', function () {
    $tag = Tag::factory()->create();
    $tagged = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Tagged']);
    $tagged->tags()->attach($tag);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Untagged']);

    $results = Group::filterByTag((string) $tag->id)->pluck('name')->all();

    expect($results)->toContain('Tagged')->not->toContain('Untagged');
});

it('skips tag filter when empty', function () {
    Group::factory()->create(['user_id' => $this->user->id]);

    expect(Group::filterByTag('')->get()->count())->toBeGreaterThanOrEqual(2);
});

it('sorts groups by name ascending', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Zeta Group']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Alpha Group']);

    $results = Group::where('user_id', $this->user->id)
        ->whereIn('name', ['Alpha Group', 'Zeta Group'])
        ->sorted('name_asc')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['Alpha Group', 'Zeta Group']);
});

it('sorts groups by most websites', function () {
    $groupA = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Few Sites']);
    $groupB = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Many Sites']);

    \App\Models\Website::factory()->create(['group_id' => $groupA->id]);
    \App\Models\Website::factory()->count(3)->create(['group_id' => $groupB->id]);

    $results = Group::where('user_id', $this->user->id)
        ->whereIn('name', ['Few Sites', 'Many Sites'])
        ->sorted('most_sites')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['Many Sites', 'Few Sites']);
});

it('defaults to latest when sort is empty', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Old Group', 'created_at' => now()->subDay()]);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'New Group', 'created_at' => now()]);

    $results = Group::where('user_id', $this->user->id)
        ->whereIn('name', ['Old Group', 'New Group'])
        ->sorted('')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['New Group', 'Old Group']);
});

it('chains search and sort scopes', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Zeta Dev', 'description' => 'Zeta']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Alpha Dev', 'description' => 'Alpha']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'No Match', 'description' => 'Nothing here']);

    $results = Group::search('Dev')
        ->sorted('name_asc')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['Alpha Dev', 'Zeta Dev']);
});
