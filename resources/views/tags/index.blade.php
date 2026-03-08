<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tags</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Browse and manage your tags.</p>
            </div>
        </div>

        @if ($tags->isEmpty())
            <div class="mt-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h2 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No tags yet</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first tag to start organizing websites.</p>
                <a href="{{ route('tags.create') }}"
                   class="inline-block mt-6 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    Create a Tag
                </a>
            </div>
        @else
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($tags as $tag)
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
                               class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:underline">View Websites</a>
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
                @endforeach
            </div>
        @endif

    </div>
</div>
</x-app-layout>
