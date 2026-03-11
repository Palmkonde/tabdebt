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

        <div class="mt-6">
            <livewire:tag-filter />
        </div>

    </div>
</div>
</x-app-layout>
