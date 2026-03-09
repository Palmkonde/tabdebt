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

it('sorts websites by name ascending', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Zeta']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Alpha']);

    Volt::test('website-filter')
        ->set('sortBy', 'name_asc')
        ->assertSeeInOrder(['Alpha', 'Zeta']);
});

it('sorts websites by name descending', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Alpha']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Zeta']);

    Volt::test('website-filter')
        ->set('sortBy', 'name_desc')
        ->assertSeeInOrder(['Zeta', 'Alpha']);
});

it('sorts websites by oldest first', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'First', 'created_at' => now()->subDay()]);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Second', 'created_at' => now()]);

    Volt::test('website-filter')
        ->set('sortBy', 'oldest')
        ->assertSeeInOrder(['First', 'Second']);
});

it('sorts websites by rating best first', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Bad Site', 'rating' => 'bad']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Good Site', 'rating' => 'good']);

    Volt::test('website-filter')
        ->set('sortBy', 'rating_best')
        ->assertSeeInOrder(['Good Site', 'Bad Site']);
});

it('resets all filters including sort', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tailwind']);

    Volt::test('website-filter')
        ->set('search', 'Laravel')
        ->assertDontSee('Tailwind')
        ->set('sortBy', 'name_asc')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('rating', '')
        ->assertSet('groupId', '')
        ->assertSet('tagId', '')
        ->assertSet('sortBy', '');
});

it('shows empty state when no results', function () {
    Volt::test('website-filter')
        ->set('search', 'nonexistent')
        ->assertSee('No websites found.');
});
