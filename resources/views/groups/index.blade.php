<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Groups</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Organize your websites into groups.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1"><span class="font-medium text-gray-500 dark:text-gray-400">Note:</span> "Delete" removes the website permanently. "Remove from Group" moves it to "Other".</p>
            </div>
            <a href="{{ route('groups.create') }}"
               class="px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition">
                + New Group
            </a>
        </div>

        <div class="mt-8 space-y-6">
            @foreach ($groups as $group)
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
                                        <a href="{{ route('tags.show', $tag) }}"
                                           class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-amber-900 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">
                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $tag->color }};"></span>
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                                {{ $group->websites->count() }} {{ Str::plural('site', $group->websites->count()) }}
                            </span>
                            <a href="{{ route('groups.edit', $group) }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                            <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                
                                @if($group->name !== 'Other')
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
            @endforeach
        </div>

    </div>
</div>
</x-app-layout>
