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

it('renders the tag filter component', function () {
    Volt::test('tag-filter')
        ->assertSuccessful();
});

it('shows tags associated with user groups', function () {
    $tag = Tag::factory()->create(['name' => 'php']);
    $this->group->tags()->attach($tag);

    Volt::test('tag-filter')
        ->assertSee('php');
});

it('does not show tags from other users', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $tag = Tag::factory()->create(['name' => 'secret-tag']);
    $otherGroup->tags()->attach($tag);

    Volt::test('tag-filter')
        ->assertDontSee('secret-tag');
});

it('filters tags by search term', function () {
    $php = Tag::factory()->create(['name' => 'php']);
    $css = Tag::factory()->create(['name' => 'css']);
    $this->group->tags()->attach([$php->id, $css->id]);

    Volt::test('tag-filter')
        ->set('search', 'php')
        ->assertSee('php')
        ->assertDontSee('css');
});

it('sorts tags by name ascending', function () {
    $zeta = Tag::factory()->create(['name' => 'zeta']);
    $alpha = Tag::factory()->create(['name' => 'alpha']);
    $this->group->tags()->attach([$zeta->id, $alpha->id]);

    Volt::test('tag-filter')
        ->set('sortBy', 'name_asc')
        ->assertSeeInOrder(['alpha', 'zeta']);
});

it('sorts tags by name descending', function () {
    $alpha = Tag::factory()->create(['name' => 'alpha']);
    $zeta = Tag::factory()->create(['name' => 'zeta']);
    $this->group->tags()->attach([$alpha->id, $zeta->id]);

    Volt::test('tag-filter')
        ->set('sortBy', 'name_desc')
        ->assertSeeInOrder(['zeta', 'alpha']);
});

it('sorts tags by most websites', function () {
    $popular = Tag::factory()->create(['name' => 'popular']);
    $unpopular = Tag::factory()->create(['name' => 'unpopular']);
    $this->group->tags()->attach([$popular->id, $unpopular->id]);

    Website::factory()->count(3)->create(['group_id' => $this->group->id])
        ->each(fn ($w) => $w->tags()->attach($popular));
    Website::factory()->create(['group_id' => $this->group->id])
        ->tags()->attach($unpopular);

    Volt::test('tag-filter')
        ->set('sortBy', 'most_websites')
        ->assertSeeInOrder(['popular', 'unpopular']);
});

it('sorts tags by most groups', function () {
    $extra = Group::factory()->create(['user_id' => $this->user->id]);
    $popular = Tag::factory()->create(['name' => 'popular']);
    $unpopular = Tag::factory()->create(['name' => 'unpopular']);

    $this->group->tags()->attach($popular);
    $extra->tags()->attach($popular);
    $this->group->tags()->attach($unpopular);

    Volt::test('tag-filter')
        ->set('sortBy', 'most_groups')
        ->assertSeeInOrder(['popular', 'unpopular']);
});

it('resets all filters', function () {
    $tag = Tag::factory()->create(['name' => 'php']);
    $this->group->tags()->attach($tag);

    Volt::test('tag-filter')
        ->set('search', 'php')
        ->set('sortBy', 'name_asc')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('sortBy', '');
});

it('shows empty state when no results', function () {
    Volt::test('tag-filter')
        ->set('search', 'nonexistent')
        ->assertSee('No tags found.');
});

it('shows tags attached via websites', function () {
    $tag = Tag::factory()->create(['name' => 'website-tag']);
    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $website->tags()->attach($tag);

    Volt::test('tag-filter')
        ->assertSee('website-tag');
});
