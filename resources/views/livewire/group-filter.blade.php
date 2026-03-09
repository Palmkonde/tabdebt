<?php

use App\Models\Tag;

use function Livewire\Volt\{computed, state};

state([
    'search' => '',
    'tagId' => '',
    'sortBy' => '',
])->url();

$tags = computed(function () {
    $groupIds = auth()->user()->groups()->pluck('id');

    return Tag::whereHas('groups', fn ($q) => $q->whereIn('groups.id', $groupIds))
        ->orderBy('name')
        ->get();
});

$groups = computed(function () {
    $query = auth()->user()->groups()->with(['tags', 'websites.tags']);

    if ($this->search !== '') {
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        });
    }

    if ($this->tagId !== '') {
        $query->whereHas('tags', fn ($q) => $q->where('tags.id', $this->tagId));
    }

    match ($this->sortBy) {
        'oldest' => $query->oldest(),
        'name_asc' => $query->orderBy('name'),
        'name_desc' => $query->orderBy('name', 'desc'),
        'most_sites' => $query->withCount('websites')->orderByDesc('websites_count'),
        'fewest_sites' => $query->withCount('websites')->orderBy('websites_count'),
        default => $query->latest(),
    };

    return $query->get();
});

$resetFilters = function () {
    $this->reset(['search', 'tagId', 'sortBy']);
};

$hasFilters = computed(function () {
    return $this->search !== '' || $this->tagId !== '' || $this->sortBy !== '';
});

?>

<div>
    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or description..."
                    class="w-full py-2 px-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                />
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
                    <option value="most_sites">Most Websites</option>
                    <option value="fewest_sites">Fewest Websites</option>
                </select>
            </div>
        </div>

        {{-- Active filters indicator + reset --}}
        @if ($this->hasFilters)
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $this->groups->count() }} {{ Str::plural('group', $this->groups->count()) }}
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
    <div class="mt-6 space-y-6">
        @forelse ($this->groups as $group)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

                {{-- Group header --}}
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $group->name }}</h2>
                        @if ($group->description)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $group->description }}</p>
                        @endif
                        @if ($group->tags->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach ($group->tags as $tag)
                                    <x-tag-pill :tag="$tag" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ $group->websites->count() }} {{ Str::plural('site', $group->websites->count()) }}
                        </span>

                        @if ($group->name !== 'Other')
                            <a href="{{ route('groups.edit', $group) }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                        @endif

                        <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')

                            @if ($group->name !== 'Other')
                                <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Websites inside this group --}}
                @if ($group->websites->isEmpty())
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No websites in this group yet.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($group->websites as $website)
                            <x-website-card :website="$website" :group="$group" />
                        @endforeach
                    </div>
                @endif

            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No groups found.</p>
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
