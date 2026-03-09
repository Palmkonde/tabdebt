<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
    $this->actingAs($this->user);
});

it('renders the website filter component', function () {
    Volt::test('website-filter')
        ->assertSuccessful();
});

it('shows all user websites by default', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tailwind']);

    Volt::test('website-filter')
        ->assertSee('Laravel')
        ->assertSee('Tailwind');
});

it('filters websites by search term', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tailwind']);

    Volt::test('website-filter')
        ->set('search', 'Laravel')
        ->assertSee('Laravel')
        ->assertDontSee('Tailwind');
});

it('filters websites by url', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Site A', 'url' => 'https://laravel.com']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Site B', 'url' => 'https://tailwindcss.com']);

    Volt::test('website-filter')
        ->set('search', 'tailwind')
        ->assertDontSee('Site A')
        ->assertSee('Site B');
});

it('filters websites by rating', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Good Site', 'rating' => 'good']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Bad Site', 'rating' => 'bad']);

    Volt::test('website-filter')
        ->set('rating', 'good')
        ->assertSee('Good Site')
        ->assertDontSee('Bad Site');
});

it('filters websites by group', function () {
    $secondGroup = Group::factory()->create(['user_id' => $this->user->id, 'name' => 'Dev Tools']);

    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'In Other']);
    Website::factory()->create(['group_id' => $secondGroup->id, 'name' => 'In Dev Tools']);

    Volt::test('website-filter')
        ->set('groupId', $secondGroup->id)
        ->assertDontSee('In Other')
        ->assertSee('In Dev Tools');
});

it('filters websites by tag', function () {
    $tag = Tag::factory()->create(['name' => 'php']);
    $tagged = Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tagged']);
    $tagged->tags()->attach($tag);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Untagged']);

    Volt::test('website-filter')
        ->set('tagId', $tag->id)
        ->assertSee('Tagged')
        ->assertDontSee('Untagged');
});

it('resets all filters', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tailwind']);

    Volt::test('website-filter')
        ->set('search', 'Laravel')
        ->assertDontSee('Tailwind')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('rating', '')
        ->assertSet('groupId', '')
        ->assertSet('tagId', '');
});

it('shows empty state when no results', function () {
    Volt::test('website-filter')
        ->set('search', 'nonexistent')
        ->assertSee('No websites found.');
});
