<?php

use App\Models\Group;
use App\Models\Tag;
use App\Models\User;
use App\Models\Website;

it('requires authentication', function () {
    $this->get('/workspace')->assertRedirect('/login');
});

it('displays the workspace page for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSuccessful();
});

it('shows the user name', function () {
    $user = User::factory()->create(['name' => 'Palm']);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSee("Palm's Workspace", false);
});

it('shows website and group counts', function () {
    $user = User::factory()->create();
    $group = $user->groups()->first();

    Website::factory()->count(3)->create(['group_id' => $group->id]);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSee('3')
        ->assertSee('Websites')
        ->assertSee('Groups');
});

it('shows recent websites', function () {
    $user = User::factory()->create();
    $group = $user->groups()->first();

    Website::factory()->create([
        'group_id' => $group->id,
        'name' => 'My Test Website',
    ]);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSee('My Test Website');
});

it('shows user groups', function () {
    $user = User::factory()->create();
    Group::factory()->create([
        'user_id' => $user->id,
        'name' => 'Dev Tools',
    ]);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSee('Dev Tools');
});

it('shows tags associated with user content', function () {
    $user = User::factory()->create();
    $group = $user->groups()->first();
    $tag = Tag::factory()->create(['name' => 'laravel']);

    $group->tags()->attach($tag);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertSee('laravel');
});

it('does not show other users data', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $otherGroup = Group::factory()->create([
        'user_id' => $otherUser->id,
        'name' => 'Secret Group',
    ]);
    Website::factory()->create([
        'group_id' => $otherGroup->id,
        'name' => 'Secret Website',
    ]);

    $this->actingAs($user)
        ->get('/workspace')
        ->assertDontSee('Secret Website')
        ->assertDontSee('Secret Group');
});
