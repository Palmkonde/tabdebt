<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Website;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
});

it('shows tags visible to user via groups', function () {
    $tag = Tag::factory()->create(['name' => 'visible-tag']);
    $this->group->tags()->attach($tag);

    $otherUser = User::factory()->create();
    $otherTag = Tag::factory()->create(['name' => 'other-tag']);
    $otherUser->groups()->first()->tags()->attach($otherTag);

    $results = Tag::visibleToUser($this->user)->pluck('name')->all();

    expect($results)->toContain('visible-tag')->not->toContain('other-tag');
});

it('shows tags visible to user via websites', function () {
    $tag = Tag::factory()->create(['name' => 'website-tag']);
    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $website->tags()->attach($tag);

    $results = Tag::visibleToUser($this->user)->pluck('name')->all();

    expect($results)->toContain('website-tag');
});

it('excludes website tags when includeWebsiteTags is false', function () {
    $websiteTag = Tag::factory()->create(['name' => 'website-only']);
    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $website->tags()->attach($websiteTag);

    $groupTag = Tag::factory()->create(['name' => 'group-tag']);
    $this->group->tags()->attach($groupTag);

    $results = Tag::visibleToUser($this->user, includeWebsiteTags: false)->pluck('name')->all();

    expect($results)->toContain('group-tag')->not->toContain('website-only');
});

it('adds user-scoped counts', function () {
    $tag = Tag::factory()->create(['name' => 'counted']);
    $this->group->tags()->attach($tag);

    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $website->tags()->attach($tag);

    $otherUser = User::factory()->create();
    $otherWebsite = Website::factory()->create(['group_id' => $otherUser->groups()->first()->id]);
    $otherWebsite->tags()->attach($tag);
    $otherUser->groups()->first()->tags()->attach($tag);

    $result = Tag::where('id', $tag->id)
        ->withUserScopedCounts($this->user)
        ->first();

    expect($result->websites_count)->toBe(1)
        ->and($result->groups_count)->toBe(1);
});

it('searches tags by name', function () {
    Tag::factory()->create(['name' => 'laravel']);
    Tag::factory()->create(['name' => 'react']);

    $results = Tag::search('laravel')->pluck('name')->all();

    expect($results)->toContain('laravel')->not->toContain('react');
});

it('returns all tags when search is empty', function () {
    Tag::factory()->create(['name' => 'alpha']);
    Tag::factory()->create(['name' => 'bravo']);

    expect(Tag::search('')->get()->count())->toBeGreaterThanOrEqual(2);
});

it('sorts tags by name ascending', function () {
    Tag::factory()->create(['name' => 'zeta-tag']);
    Tag::factory()->create(['name' => 'alpha-tag']);

    $results = Tag::whereIn('name', ['alpha-tag', 'zeta-tag'])
        ->sorted('name_asc')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['alpha-tag', 'zeta-tag']);
});

it('defaults to latest when sort is empty', function () {
    Tag::factory()->create(['name' => 'old-tag', 'created_at' => now()->subDay()]);
    Tag::factory()->create(['name' => 'new-tag', 'created_at' => now()]);

    $results = Tag::whereIn('name', ['old-tag', 'new-tag'])
        ->sorted('')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['new-tag', 'old-tag']);
});

it('chains visibleToUser, search, and sorted scopes', function () {
    $tag = Tag::factory()->create(['name' => 'findme']);
    $this->group->tags()->attach($tag);

    Tag::factory()->create(['name' => 'hidden']);

    $results = Tag::visibleToUser($this->user)
        ->search('findme')
        ->sorted('name_asc')
        ->pluck('name')
        ->all();

    expect($results)->toBe(['findme']);
});
