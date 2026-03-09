<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Website;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
});

// -- Authentication --

it('requires authentication for all routes', function (string $method, string $uri) {
    $this->$method($uri)->assertRedirect('/login');
})->with([
    'index' => ['get', '/tags'],
    'show' => ['get', '/tags/1'],
    'destroy' => ['delete', '/tags/1'],
]);

// -- Index --

it('displays the tags index', function () {
    $this->actingAs($this->user)
        ->get('/tags')
        ->assertSuccessful();
});

it('lists only tags associated with user content', function () {
    $myTag = Tag::factory()->create(['name' => 'my-tag']);
    $this->group->tags()->attach($myTag);

    $otherTag = Tag::factory()->create(['name' => 'other-tag']);
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $otherGroup->tags()->attach($otherTag);

    $this->actingAs($this->user)
        ->get('/tags')
        ->assertSee('my-tag')
        ->assertDontSee('other-tag');
});

it('shows tags attached via websites', function () {
    $tag = Tag::factory()->create(['name' => 'website-tag']);
    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $website->tags()->attach($tag);

    $this->actingAs($this->user)
        ->get('/tags')
        ->assertSee('website-tag');
});

// -- Show --

it('displays a tag with its websites and groups', function () {
    $tag = Tag::factory()->create(['name' => 'show-me']);
    $website = Website::factory()->create([
        'group_id' => $this->group->id,
        'name' => 'Tagged Website',
    ]);
    $website->tags()->attach($tag);
    $this->group->tags()->attach($tag);

    $this->actingAs($this->user)
        ->get("/tags/{$tag->id}")
        ->assertSuccessful()
        ->assertSee('show-me')
        ->assertSee('Tagged Website');
});

it('returns 404 for a tag not associated with user', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->get("/tags/{$tag->id}")
        ->assertNotFound();
});

it('does not show other user websites under a shared tag', function () {
    $tag = Tag::factory()->create(['name' => 'shared']);

    $myWebsite = Website::factory()->create([
        'group_id' => $this->group->id,
        'name' => 'My Website',
    ]);
    $myWebsite->tags()->attach($tag);

    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $otherWebsite = Website::factory()->create([
        'group_id' => $otherGroup->id,
        'name' => 'Other Website',
    ]);
    $otherWebsite->tags()->attach($tag);

    $this->actingAs($this->user)
        ->get("/tags/{$tag->id}")
        ->assertSee('My Website')
        ->assertDontSee('Other Website');
});

// -- Destroy --

it('deletes a tag', function () {
    $tag = Tag::factory()->create();
    $this->group->tags()->attach($tag);

    $this->actingAs($this->user)
        ->delete("/tags/{$tag->id}")
        ->assertRedirect(route('tags.index'));

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

it('prevents deleting a tag not associated with user', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->delete("/tags/{$tag->id}")
        ->assertNotFound();
});
