<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">

    {{-- Hero Section --}}
    <section class="py-20 px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white">
                TabDebt
            </h1>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                Stop hoarding tabs. Start organizing bookmarks.
            </p>
            <p class="mt-2 text-gray-500 dark:text-gray-500">
                Save, group, and tag your favorite websites in one place.
            </p>

            <div class="mt-8 flex items-center justify-center gap-4">
                @auth
                    <a href="{{ route('workspace.index') }}"
                       class="px-6 py-3 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition">
                        Go to Workspace
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Save Websites</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Bookmark any website with a name, URL, description, and rating.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Organize with Groups</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Create groups like "Dev Tools" or "Learning" to keep things tidy.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tag Everything</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Add tags to websites for quick filtering and discovery.
                </p>
            </div>

        </div>
    </section>

</div>
</x-app-layout>
