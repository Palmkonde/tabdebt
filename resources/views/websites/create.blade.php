<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <x-back-link :href="route('websites.index')" label="Back to Websites" />

        {{-- Form card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-10">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Add New Website</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Save a website to your collection.</p>
            </div>

            <form action="{{ route('websites.store') }}" method="POST">
                @csrf

                <x-website-form :groups="$groups" :tags="$tags" />

                <x-form-actions :cancel-href="route('websites.index')" submit-label="Add Website" />
            </form>

        </div>
    </div>
</div>
</x-app-layout>
