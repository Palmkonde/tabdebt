<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <x-back-link :href="route('groups.index')" label="Back to Groups" />

        {{-- Form card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-10">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Group</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Update details for <span class="text-gray-700 dark:text-gray-200 font-medium">{{ $group->name }}</span>.</p>
            </div>

            <form action="{{ route('groups.update', $group->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-group-form :group="$group" :tags="$tags" />

                <x-form-actions :cancel-href="route('groups.index')" submit-label="Update Group" />
            </form>

        </div>
    </div>
</div>
</x-app-layout>
