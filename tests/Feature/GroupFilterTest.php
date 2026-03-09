<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first(); // "Other" group
    $this->actingAs($this->user);
});

it('renders the group filter component', function () {
    Volt::test('group-filter')
        ->assertSuccessful();
});

it('shows all user groups by default', function () {
    $devTools = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools']);

    Volt::test('group-filter')
        ->assertSee('Other')
        ->assertSee('Dev Tools');
});

it('filters groups by search term on name', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Learning']);

    Volt::test('group-filter')
        ->set('search', 'Dev')
        ->assertSee('Dev Tools')
        ->assertDontSee('Learning');
});

it('filters groups by search term on description', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools', 'description' => 'Developer utilities']);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Learning', 'description' => 'Online courses']);

    Volt::test('group-filter')
        ->set('search', 'courses')
        ->assertDontSee('Dev Tools')
        ->assertSee('Learning');
});

it('filters groups by tag', function () {
    $tag = Tag::factory()->create(['name' => 'php']);
    $tagged = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Tagged Group']);
    $tagged->tags()->attach($tag);
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Untagged Group']);

    Volt::test('group-filter')
        ->set('tagId', $tag->id)
        ->assertSee('Tagged Group')
        ->assertDontSee('Untagged Group');
});

it('resets all filters', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools']);

    Volt::test('group-filter')
        ->set('search', 'Dev')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('tagId', '');
});

it('shows empty state when no results', function () {
    Volt::test('group-filter')
        ->set('search', 'nonexistent')
        ->assertSee('No groups found.');
});

it('shows websites inside each group', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel Docs']);

    Volt::test('group-filter')
        ->assertSee('Laravel Docs');
});
