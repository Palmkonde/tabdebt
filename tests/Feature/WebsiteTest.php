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
    'index' => ['get', '/websites'],
    'create' => ['get', '/websites/create'],
    'store' => ['post', '/websites'],
    'edit' => ['get', '/websites/1/edit'],
    'update' => ['put', '/websites/1'],
    'destroy' => ['delete', '/websites/1'],
]);

// -- Index --

it('displays the websites index', function () {
    $this->actingAs($this->user)
        ->get('/websites')
        ->assertSuccessful();
});

it('lists only the authenticated user websites', function () {
    Website::factory()->create([
        'group_id' => $this->group->id,
        'name' => 'My Site',
    ]);

    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    Website::factory()->create([
        'group_id' => $otherGroup->id,
        'name' => 'Other Site',
    ]);

    $this->actingAs($this->user)
        ->get('/websites')
        ->assertSee('My Site')
        ->assertDontSee('Other Site');
});

// -- Create --

it('displays the create website form', function () {
    $this->actingAs($this->user)
        ->get('/websites/create')
        ->assertSuccessful()
        ->assertSee('Other');
});

// -- Store --

it('stores a new website', function () {
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->post('/websites', [
            'name' => 'Laravel',
            'url' => 'https://laravel.com',
            'description' => 'PHP framework',
            'rating' => 'good',
            'group_id' => $this->group->id,
            'tags' => [$tag->id],
        ])
        ->assertRedirect(route('websites.index'));

    $this->assertDatabaseHas('websites', [
        'name' => 'Laravel',
        'url' => 'https://laravel.com',
        'group_id' => $this->group->id,
    ]);
});

it('validates required fields when storing', function (string $field) {
    $data = [
        'name' => 'Laravel',
        'url' => 'https://laravel.com',
        'rating' => 'good',
        'group_id' => $this->group->id,
    ];

    unset($data[$field]);

    $this->actingAs($this->user)
        ->post('/websites', $data)
        ->assertSessionHasErrors($field);
})->with(['name', 'url', 'rating', 'group_id']);

it('validates url format', function () {
    $this->actingAs($this->user)
        ->post('/websites', [
            'name' => 'Bad URL',
            'url' => 'not-a-url',
            'rating' => 'good',
            'group_id' => $this->group->id,
        ])
        ->assertSessionHasErrors('url');
});

it('validates rating values', function () {
    $this->actingAs($this->user)
        ->post('/websites', [
            'name' => 'Test',
            'url' => 'https://example.com',
            'rating' => 'invalid',
            'group_id' => $this->group->id,
        ])
        ->assertSessionHasErrors('rating');
});

it('prevents storing a website in another user group', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();

    $this->actingAs($this->user)
        ->post('/websites', [
            'name' => 'Hack',
            'url' => 'https://example.com',
            'rating' => 'good',
            'group_id' => $otherGroup->id,
        ])
        ->assertForbidden();
});

// -- Edit --

it('displays the edit website form', function () {
    $website = Website::factory()->create([
        'group_id' => $this->group->id,
        'name' => 'Edit Me',
    ]);

    $this->actingAs($this->user)
        ->get("/websites/{$website->id}/edit")
        ->assertSuccessful()
        ->assertSee('Edit Me');
});

it('prevents editing another user website', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $website = Website::factory()->create(['group_id' => $otherGroup->id]);

    $this->actingAs($this->user)
        ->get("/websites/{$website->id}/edit")
        ->assertForbidden();
});

// -- Update --

it('updates a website', function () {
    $website = Website::factory()->create([
        'group_id' => $this->group->id,
        'name' => 'Old Name',
    ]);

    $this->actingAs($this->user)
        ->put("/websites/{$website->id}", [
            'name' => 'New Name',
            'url' => 'https://updated.com',
            'rating' => 'bad',
            'group_id' => $this->group->id,
        ])
        ->assertRedirect(route('websites.index'));

    $this->assertDatabaseHas('websites', [
        'id' => $website->id,
        'name' => 'New Name',
        'url' => 'https://updated.com',
    ]);
});

it('syncs tags when updating', function () {
    $website = Website::factory()->create(['group_id' => $this->group->id]);
    $tag = Tag::factory()->create();

    $this->actingAs($this->user)
        ->put("/websites/{$website->id}", [
            'name' => 'Tagged',
            'url' => 'https://example.com',
            'rating' => 'good',
            'group_id' => $this->group->id,
            'tags' => [$tag->id],
        ]);

    expect($website->fresh()->tags)->toHaveCount(1);
    expect($website->fresh()->tags->first()->id)->toBe($tag->id);
});

it('prevents updating another user website', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $website = Website::factory()->create(['group_id' => $otherGroup->id]);

    $this->actingAs($this->user)
        ->put("/websites/{$website->id}", [
            'name' => 'Hacked',
            'url' => 'https://example.com',
            'rating' => 'good',
            'group_id' => $this->group->id,
        ])
        ->assertForbidden();
});

// -- Destroy --

it('deletes a website', function () {
    $website = Website::factory()->create(['group_id' => $this->group->id]);

    $this->actingAs($this->user)
        ->delete("/websites/{$website->id}")
        ->assertRedirect(route('websites.index'));

    $this->assertDatabaseMissing('websites', ['id' => $website->id]);
});

it('prevents deleting another user website', function () {
    $otherUser = User::factory()->create();
    $otherGroup = $otherUser->groups()->first();
    $website = Website::factory()->create(['group_id' => $otherGroup->id]);

    $this->actingAs($this->user)
        ->delete("/websites/{$website->id}")
        ->assertForbidden();
});
