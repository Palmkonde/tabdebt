<?php

use App\Models\Tag;
use App\Models\Website;

use function Livewire\Volt\{computed, state};

state([
    'search' => '',
    'rating' => '',
    'groupId' => '',
    'tagId' => '',
    'sortBy' => '',
])->url();

$groups = computed(function () {
    return auth()->user()->groups()->orderBy('name')->get();
});

$tags = computed(function () {
    $groupIds = auth()->user()->groups()->pluck('id');

    return Tag::where(function ($query) use ($groupIds) {
        $query->whereHas('websites', fn ($q) => $q->whereIn('group_id', $groupIds))
            ->orWhereHas('groups', fn ($q) => $q->whereIn('groups.id', $groupIds));
    })->orderBy('name')->get();
});

$websites = computed(function () {
    $groupIds = auth()->user()->groups()->pluck('id');

    $query = Website::with('tags')
        ->whereIn('group_id', $groupIds);

    if ($this->search !== '') {
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('url', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->rating !== '') {
        $query->where('rating', $this->rating);
    }

    if ($this->groupId !== '') {
        $query->where('group_id', $this->groupId);
    }

    if ($this->tagId !== '') {
        $query->whereHas('tags', fn ($q) => $q->where('tags.id', $this->tagId));
    }

    match ($this->sortBy) {
        'oldest' => $query->oldest(),
        'name_asc' => $query->orderBy('name'),
        'name_desc' => $query->orderBy('name', 'desc'),
        'rating_best' => $query->orderByRaw("CASE rating WHEN 'good' THEN 1 WHEN 'average' THEN 2 WHEN 'bad' THEN 3 END"),
        'rating_worst' => $query->orderByRaw("CASE rating WHEN 'bad' THEN 1 WHEN 'average' THEN 2 WHEN 'good' THEN 3 END"),
        default => $query->latest(),
    };

    return $query->get();
});

$resetFilters = function () {
    $this->reset(['search', 'rating', 'groupId', 'tagId', 'sortBy']);
};

$hasFilters = computed(function () {
    return $this->search !== '' || $this->rating !== '' || $this->groupId !== '' || $this->tagId !== '' || $this->sortBy !== '';
});

?>

<div>
    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or URL..."
                    class="w-full py-2 px-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                />
            </div>

            {{-- Rating --}}
            <div>
                <select
                    wire:model.live="rating"
                    class="px-2 py-2 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                >
                    <option value="">All Ratings</option>
                    <option value="good">Good</option>
                    <option value="average">Average</option>
                    <option value="bad">Bad</option>
                </select>
            </div>

            {{-- Group --}}
            <div>
                <select
                    wire:model.live="groupId"
                    class="px-2 py-2 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                >
                    <option value="">All Groups</option>
                    @foreach ($this->groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tag --}}
            <div>
                <select
                    wire:model.live="tagId"
                    class="px-2 py-2 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                >
                    <option value="">All Tags</option>
                    @foreach ($this->tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div>
                <select
                    wire:model.live="sortBy"
                    class="px-2 py-2 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                >
                    <option value="">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name_asc">Name A–Z</option>
                    <option value="name_desc">Name Z–A</option>
                    <option value="rating_best">Rating: Best</option>
                    <option value="rating_worst">Rating: Worst</option>
                </select>
            </div>
        </div>

        {{-- Active filters indicator + reset --}}
        @if ($this->hasFilters)
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $this->websites->count() }} {{ Str::plural('website', $this->websites->count()) }}
                </p>
                <button
                    wire:click="resetFilters"
                    class="text-sm text-amber-600 dark:text-amber-400 hover:underline"
                >
                    Clear filters
                </button>
            </div>
        @endif
    </div>

    {{-- Results --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($this->websites as $website)
            <x-website-card :website="$website" />
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No websites found.</p>
                @if ($this->hasFilters)
                    <button
                        wire:click="resetFilters"
                        class="mt-2 text-sm text-amber-600 dark:text-amber-400 hover:underline"
                    >
                        Clear filters
                    </button>
                @endif
            </div>
        @endforelse
    </div>
</div>
