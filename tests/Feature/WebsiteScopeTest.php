<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
});

it('scopes websites to user groups', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();

    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Mine']);
    Website::factory()->create(['group_id' => $otherGroup->id, 'name' => 'Theirs']);

    $results = Website::forUser($this->user)->pluck('name')->all();

    expect($results)->toContain('Mine')->not->toContain('Theirs');
});

it('searches by name', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Laravel Docs', 'url' => 'https://laravel.com']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tailwind', 'url' => 'https://tailwind.com']);

    $results = Website::search('Laravel')->pluck('name')->all();

    expect($results)->toContain('Laravel Docs')->not->toContain('Tailwind');
});

it('searches by url', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Site A', 'url' => 'https://laravel.com']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Site B', 'url' => 'https://tailwind.com']);

    $results = Website::search('tailwind')->pluck('name')->all();

    expect($results)->toContain('Site B')->not->toContain('Site A');
});

it('returns all websites when search is empty', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Alpha']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Bravo']);

    $results = Website::search('')->get();

    expect($results)->toHaveCount(2);
});

it('filters by rating', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Good', 'rating' => 'good']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Bad', 'rating' => 'bad']);

    $results = Website::filterByRating('good')->pluck('name')->all();

    expect($results)->toContain('Good')->not->toContain('Bad');
});

it('skips rating filter when empty', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'rating' => 'good']);
    Website::factory()->create(['group_id' => $this->group->id, 'rating' => 'bad']);

    expect(Website::filterByRating('')->get())->toHaveCount(2);
});

it('filters by group', function () {
    $secondGroup = Group::factory()->create(['user_id' => $this->user->id]);

    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'In First']);
    Website::factory()->create(['group_id' => $secondGroup->id, 'name' => 'In Second']);

    $results = Website::filterByGroup((string) $secondGroup->id)->pluck('name')->all();

    expect($results)->toContain('In Second')->not->toContain('In First');
});

it('skips group filter when empty', function () {
    $secondGroup = Group::factory()->create(['user_id' => $this->user->id]);

    Website::factory()->create(['group_id' => $this->group->id]);
    Website::factory()->create(['group_id' => $secondGroup->id]);

    expect(Website::filterByGroup('')->get())->toHaveCount(2);
});

it('filters by tag', function () {
    $tag = Tag::factory()->create();

    $tagged = Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Tagged']);
    $tagged->tags()->attach($tag);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Untagged']);

    $results = Website::filterByTag((string) $tag->id)->pluck('name')->all();

    expect($results)->toContain('Tagged')->not->toContain('Untagged');
});

it('skips tag filter when empty', function () {
    Website::factory()->create(['group_id' => $this->group->id]);
    Website::factory()->create(['group_id' => $this->group->id]);

    expect(Website::filterByTag('')->get())->toHaveCount(2);
});

it('sorts by name ascending', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Zeta']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Alpha']);

    $results = Website::sorted('name_asc')->pluck('name')->all();

    expect($results)->toBe(['Alpha', 'Zeta']);
});

it('sorts by name descending', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Alpha']);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Zeta']);

    $results = Website::sorted('name_desc')->pluck('name')->all();

    expect($results)->toBe(['Zeta', 'Alpha']);
});

it('defaults to latest when sort is empty', function () {
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Old', 'created_at' => now()->subDay()]);
    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'New', 'created_at' => now()]);

    $results = Website::sorted('')->pluck('name')->all();

    expect($results)->toBe(['New', 'Old']);
});

it('chains multiple scopes together', function () {
    $tag = Tag::factory()->create();
    $secondGroup = Group::factory()->create(['user_id' => $this->user->id]);

    $match = Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Match', 'rating' => 'good']);
    $match->tags()->attach($tag);

    Website::factory()->create(['group_id' => $this->group->id, 'name' => 'Wrong Rating', 'rating' => 'bad']);
    Website::factory()->create(['group_id' => $secondGroup->id, 'name' => 'Wrong Group', 'rating' => 'good']);

    $results = Website::forUser($this->user)
        ->search('Match')
        ->filterByRating('good')
        ->filterByGroup((string) $this->group->id)
        ->filterByTag((string) $tag->id)
        ->sorted('name_asc')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['Match']);
});
