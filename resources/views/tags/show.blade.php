<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Back link --}}
        <a href="{{ route('tags.index') }}"
           class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors mb-8 group">
            <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tags
        </a>

        {{-- Tag header card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="w-6 h-6 rounded-full shrink-0 ring-4 ring-offset-2 ring-offset-white dark:ring-offset-gray-800"
                          style="background-color: {{ $tag->color }}; --tw-ring-color: {{ $tag->color }}33;">
                    </span>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tag->name }}</h1>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500">{{ $tag->color }}</span>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                                {{ $websites->count() }} {{ Str::plural('website', $websites->count()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('tags.edit', $tag) }}"
                       class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Websites grid --}}
        @if ($websites->isEmpty())
            <div class="text-center py-16">
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
</div>
</x-app-layout>
