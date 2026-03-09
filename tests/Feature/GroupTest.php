<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->defaultGroup = $this->user->groups()->where('name', 'Other')->first();
});

// -- Authentication --

it('requires authentication for all routes', function (string $method, string $uri) {
    $this->$method($uri)->assertRedirect('/login');
})->with([
    'index' => ['get', '/groups'],
    'create' => ['get', '/groups/create'],
    'store' => ['post', '/groups'],
    'edit' => ['get', '/groups/1/edit'],
    'update' => ['put', '/groups/1'],
    'destroy' => ['delete', '/groups/1'],
]);

// -- Index --

it('displays the groups index', function () {
    $this->actingAs($this->user)
        ->get('/groups')
        ->assertSuccessful();
});

it('lists only the authenticated user groups', function () {
    Group::factory()->create(['user_id' => $this->user->id, 'name' => 'My Group']);

    $otherUser = User::factory()->create();
    Group::factory()->create(['user_id' => $otherUser->id, 'name' => 'Hidden Group']);

    $this->actingAs($this->user)
        ->get('/groups')
        ->assertSee('My Group')
        ->assertDontSee('Hidden Group');
});

// -- Create --

it('displays the create group form', function () {
    $this->actingAs($this->user)
        ->get('/groups/create')
        ->assertSuccessful();
});

// -- Store --

it('stores a new group', function () {
    $this->actingAs($this->user)
        ->post('/groups', [
            'name' => 'Dev Tools',
            'description' => 'Development tools',
        ])
        ->assertRedirect(route('groups.index'));

    $this->assertDatabaseHas('groups', [
        'name' => 'Dev Tools',
        'user_id' => $this->user->id,
    ]);
});

it('stores a group with tags', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->post('/groups', [
            'name' => 'Tagged Group',
            'tags' => [$tag->id],
        ]);

    $group = Group::where('name', 'Tagged Group')->first();
    expect($group->tags)->toHaveCount(1);
});

it('validates required fields when storing', function () {
    $this->actingAs($this->user)
        ->post('/groups', [])
        ->assertSessionHasErrors('name');
});

it('validates name max length', function () {
    $this->actingAs($this->user)
        ->post('/groups', [
            'name' => str_repeat('a', 256),
        ])
        ->assertSessionHasErrors('name');
});

it('validates description max length', function () {
    $this->actingAs($this->user)
        ->post('/groups', [
            'name' => 'Valid',
            'description' => str_repeat('a', 501),
        ])
        ->assertSessionHasErrors('description');
});

// -- Edit --

it('displays the edit group form', function () {
    $group = Group::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Edit Me',
    ]);

    $this->actingAs($this->user)
        ->get("/groups/{$group->id}/edit")
        ->assertSuccessful()
        ->assertSee('Edit Me');
});

it('prevents editing the default Other group', function () {
    $this->actingAs($this->user)
        ->get("/groups/{$this->defaultGroup->id}/edit")
        ->assertForbidden();
});

it('prevents editing another user group', function () {
    $otherUser = User::factory()->create();
    $group = Group::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($this->user)
        ->get("/groups/{$group->id}/edit")
        ->assertNotFound();
});

// -- Update --

it('updates a group', function () {
    $group = Group::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Name',
    ]);

    $this->actingAs($this->user)
        ->put("/groups/{$group->id}", [
            'name' => 'New Name',
            'description' => 'Updated',
        ])
        ->assertRedirect(route('groups.index'));

    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'name' => 'New Name',
    ]);
});

it('syncs tags when updating a group', function () {
    $group = Group::factory()->create(['user_id' => $this->user->id]);
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->put("/groups/{$group->id}", [
            'name' => 'Tagged',
            'tags' => [$tag->id],
        ]);

    expect($group->fresh()->tags)->toHaveCount(1);
});

it('prevents updating tags on the default Other group', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->put("/groups/{$this->defaultGroup->id}", [
            'name' => 'Other',
            'tags' => [$tag->id],
        ])
        ->assertForbidden();
});

// -- Destroy --

it('deletes a group and moves websites to Other', function () {
    $group = Group::factory()->create(['user_id' => $this->user->id]);
    $website = Website::factory()->create(['group_id' => $group->id]);

    $this->actingAs($this->user)
        ->delete("/groups/{$group->id}")
        ->assertRedirect(route('groups.index'));

    $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    expect($website->fresh()->group_id)->toBe($this->defaultGroup->id);
});

it('prevents deleting the default Other group', function () {
    $this->actingAs($this->user)
        ->delete("/groups/{$this->defaultGroup->id}")
        ->assertForbidden();
});

it('prevents deleting another user group', function () {
    $otherUser = User::factory()->create();
    $group = Group::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($this->user)
        ->delete("/groups/{$group->id}")
        ->assertNotFound();
});
