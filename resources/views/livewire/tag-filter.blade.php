<?php

use App\Models\Tag;

use function Livewire\Volt\{computed, state, updated, usesPagination};

usesPagination();

state([
    'search' => '',
    'sortBy' => '',
])->url();

$resetPageOnFilterChange = fn () => $this->resetPage();

updated([
    'search' => $resetPageOnFilterChange,
    'sortBy' => $resetPageOnFilterChange,
]);

$tags = computed(function () {
    $groupIds = auth()->user()->groups()->pluck('id');

    $query = Tag::where(function ($q) use ($groupIds) {
        $q->whereHas('websites', fn ($w) => $w->whereIn('group_id', $groupIds))
            ->orWhereHas('groups', fn ($g) => $g->whereIn('groups.id', $groupIds));
    })
        ->withCount([
            'websites' => fn ($q) => $q->whereIn('group_id', $groupIds),
            'groups' => fn ($q) => $q->whereIn('groups.id', $groupIds),
        ]);

    if ($this->search !== '') {
        $query->where('name', 'like', '%' . $this->search . '%');
    }

    match ($this->sortBy) {
        'oldest' => $query->oldest(),
        'name_asc' => $query->orderBy('name'),
        'name_desc' => $query->orderBy('name', 'desc'),
        'most_websites' => $query->orderByDesc('websites_count'),
        'fewest_websites' => $query->orderBy('websites_count'),
        'most_groups' => $query->orderByDesc('groups_count'),
        'fewest_groups' => $query->orderBy('groups_count'),
        default => $query->latest(),
    };

    return $query->paginate(12);
});

$resetFilters = function () {
    $this->reset(['search', 'sortBy']);
    $this->resetPage();
};

$hasFilters = computed(function () {
    return $this->search !== '' || $this->sortBy !== '';
});

?>

<div>
    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by tag name..."
                    class="w-full py-2 px-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-amber-500 focus:ring-amber-500"
                />
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
                    <option value="most_websites">Most Websites</option>
                    <option value="fewest_websites">Fewest Websites</option>
                    <option value="most_groups">Most Groups</option>
                    <option value="fewest_groups">Fewest Groups</option>
                </select>
            </div>
        </div>

        {{-- Active filters indicator + reset --}}
        @if ($this->hasFilters)
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $this->tags->total() }} {{ Str::plural('tag', $this->tags->total()) }}
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
        @forelse ($this->tags as $tag)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex flex-col justify-between">

                <div>
                    {{-- Tag color dot + name --}}
                    <div class="flex items-center gap-3">
                        <span class="w-4 h-4 rounded-full shrink-0 ring-2 ring-offset-2 ring-offset-white dark:ring-offset-gray-800"
                              style="background-color: {{ $tag->color }}; ring-color: {{ $tag->color }};">
                        </span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $tag->name }}</h2>
                    </div>

                    {{-- Color hex --}}
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 ml-7 font-mono">{{ $tag->color }}</p>

                    {{-- Counts --}}
                    <div class="flex flex-wrap gap-2 mt-4 ml-7">
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ $tag->websites_count }} {{ Str::plural('website', $tag->websites_count) }}
                        </span>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ $tag->groups_count }} {{ Str::plural('group', $tag->groups_count) }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('tags.show', $tag) }}"
                       class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:underline">View</a>
                    <a href="{{ route('tags.edit', $tag) }}"
                       class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No tags found.</p>
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

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $this->tags->links() }}
    </div>
</div>
