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

        <div class="mt-8">
            <livewire:group-filter />
        </div>

    </div>
</div>
</x-app-layout>
