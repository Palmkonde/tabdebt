<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Websites</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage all your saved websites.</p>
            </div>
            <a href="{{ route('websites.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                + Add Website
            </a>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($websites as $website)
                <x-website-card :website="$website" />
            @endforeach
        </div>

    </div>
</div>
</x-app-layout>