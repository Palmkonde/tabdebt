<?php

use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->group = $this->user->groups()->first();
    $this->actingAs($this->user);
});

it('renders the group selector component', function () {
    Volt::test('group-selector', ['selectedGroupId' => ''])
        ->assertSuccessful();
});

it('shows existing groups in dropdown', function () {
    Volt::test('group-selector', ['selectedGroupId' => ''])
        ->assertSee($this->group->name);
});

it('creates a new group inline', function () {
    Volt::test('group-selector', ['selectedGroupId' => ''])
        ->set('creatingNew', true)
        ->set('newGroupName', 'My New Group')
        ->call('createGroup')
        ->assertSet('creatingNew', false)
        ->assertSet('newGroupName', '')
        ->assertSee('My New Group');

    $this->assertDatabaseHas('groups', [
        'name' => 'My New Group',
        'user_id' => $this->user->id,
    ]);
});

it('selects the newly created group', function () {
    $component = Volt::test('group-selector', ['selectedGroupId' => ''])
        ->set('creatingNew', true)
        ->set('newGroupName', 'Fresh Group')
        ->call('createGroup');

    $newGroup = $this->user->groups()->where('name', 'Fresh Group')->first();

    $component->assertSet('selectedGroupId', $newGroup->id);
});

it('validates new group name is required', function () {
    Volt::test('group-selector', ['selectedGroupId' => ''])
        ->set('creatingNew', true)
        ->set('newGroupName', '')
        ->call('createGroup')
        ->assertHasErrors(['newGroupName' => 'required']);
});

it('cancels group creation', function () {
    Volt::test('group-selector', ['selectedGroupId' => ''])
        ->set('creatingNew', true)
        ->set('newGroupName', 'Something')
        ->call('cancelCreate')
        ->assertSet('creatingNew', false)
        ->assertSet('newGroupName', '');
});

it('preserves selected group id from props', function () {
    Volt::test('group-selector', ['selectedGroupId' => $this->group->id])
        ->assertSet('selectedGroupId', $this->group->id);
});
