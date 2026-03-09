<?php

use function Livewire\Volt\{computed, state};

state([
    'selectedGroupId' => '',
    'creatingNew' => false,
    'newGroupName' => '',
]);

$groups = computed(function () {
    return auth()->user()->groups()->orderBy('name')->get();
});

$createGroup = function () {
    $this->validate([
        'newGroupName' => ['required', 'string', 'max:255'],
    ]);

    $group = auth()->user()->groups()->create([
        'name' => $this->newGroupName,
    ]);

    $this->selectedGroupId = $group->id;
    $this->newGroupName = '';
    $this->creatingNew = false;
};

$cancelCreate = function () {
    $this->creatingNew = false;
    $this->newGroupName = '';
};

?>

<div>
    {{-- Hidden input for parent form submission --}}
    <input type="hidden" name="group_id" value="{{ $selectedGroupId }}" />

    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Group</label>

    @if (! $creatingNew)
        {{-- Group select --}}
        <div class="flex items-end gap-2">
            <select
                wire:model.live="selectedGroupId"
                required
                class="flex-1 bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white focus:border-amber-500 focus:ring-0 transition-colors duration-300 cursor-pointer"
            >
                <option value="" disabled class="bg-white dark:bg-gray-800">Select a group</option>
                @foreach ($this->groups as $group)
                    <option value="{{ $group->id }}" class="bg-white dark:bg-gray-800">
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>

            <button
                type="button"
                wire:click="$set('creatingNew', true)"
                class="shrink-0 mb-1 inline-flex items-center gap-1 text-sm font-medium text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New
            </button>
        </div>
    @else
        {{-- Create new group inline --}}
        <div class="flex items-end gap-2">
            <input
                type="text"
                wire:model="newGroupName"
                wire:keydown.enter.prevent="createGroup"
                placeholder="New group name..."
                autofocus
                class="flex-1 bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300"
            />
            <button
                type="button"
                wire:click="createGroup"
                class="shrink-0 mb-1 inline-flex items-center gap-1 text-sm font-medium text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save
            </button>
            <button
                type="button"
                wire:click="cancelCreate"
                class="shrink-0 mb-1 text-sm text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
            >
                Cancel
            </button>
        </div>
        @error('newGroupName')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    @endif

    @error('group_id')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
