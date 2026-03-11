<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <x-back-link :href="route('tags.index')" label="Back to Tags" />

        {{-- Tag hero header --}}
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden mb-10">
            {{-- Colored accent bar --}}
            <div class="h-1.5 w-full" style="background-color: {{ $tag->color }};"></div>

            <div class="p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-5">
                        {{-- Large color swatch --}}
                        <div class="w-12 h-12 rounded-xl shrink-0 shadow-inner ring-1 ring-black/5 dark:ring-white/10"
                             style="background-color: {{ $tag->color }};"></div>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $tag->name }}</h1>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-xs font-mono text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700/60 px-2 py-0.5 rounded">{{ $tag->color }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">&middot;</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $websites->count() }} {{ Str::plural('website', $websites->count()) }},
                                    {{ $groups->count() }} {{ Str::plural('group', $groups->count()) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('tags.edit', $tag) }}"
                           class="inline-flex items-center gap-1.5 text-sm font-medium text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-sm font-medium text-red-500 dark:text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab navigation --}}
        <div class="flex gap-1 mb-6 border-b border-gray-200 dark:border-gray-700" id="tag-tabs">
            <button data-tab="websites"
                    class="tab-btn active relative px-5 py-2.5 text-sm font-semibold text-amber-600 dark:text-amber-400 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    Websites
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">{{ $websites->count() }}</span>
                </span>
                <span class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-amber-500 rounded-t transition-all"></span>
            </button>
            <button data-tab="groups"
                    class="tab-btn relative px-5 py-2.5 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Groups
                    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $groups->count() }}</span>
                </span>
                <span class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-amber-500 rounded-t transition-all opacity-0"></span>
            </button>
        </div>

        {{-- Websites panel --}}
        <div id="panel-websites" class="tab-panel">
            @if ($websites->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No websites with this tag yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($websites as $website)
                        <x-website-card :website="$website" />
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Groups panel --}}
        <div id="panel-groups" class="tab-panel hidden">
            @if ($groups->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No groups with this tag yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($groups as $group)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-md transition-shadow">
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $group->name }}</h3>
                                    <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 rounded-full">
                                        {{ $group->websites_count }} {{ Str::plural('website', $group->websites_count) }}
                                    </span>
                                </div>

                                @if ($group->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $group->description }}</p>
                                @endif

                                {{-- Tags on this group --}}
                                @if ($group->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach ($group->tags as $groupTag)
                                            <x-tag-pill :tag="$groupTag" />
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 px-5 py-3 border-t border-gray-100 dark:border-gray-700/60">
                                <a href="{{ route('groups.edit', $group) }}"
                                   class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Tab switching --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.dataset.tab;

            tabs.forEach(function (t) {
                const isActive = t.dataset.tab === target;
                t.classList.toggle('text-amber-600', isActive);
                t.classList.toggle('dark:text-amber-400', isActive);
                t.classList.toggle('text-gray-500', !isActive);
                t.classList.toggle('dark:text-gray-400', !isActive);
                t.querySelector('.tab-indicator').style.opacity = isActive ? '1' : '0';
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('hidden', panel.id !== 'panel-' + target);
            });
        });
    });
});
</script>

</x-app-layout>
