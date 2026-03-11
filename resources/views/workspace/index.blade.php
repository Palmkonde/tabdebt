<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $name }}'s Workspace</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome to your workspace, {{ $name }}! Here you can manage your websites and view your status.</p>

        {{-- Stats --}}
        <section class="mt-8 grid grid-cols-3 gap-4">
            <x-status countNum="{{ $websiteCount }}" label="Websites" />
            <x-status countNum="{{ $groupCount }}" label="Groups" />
            <x-status countNum="{{ $tagCount }}" label="Tags" />
        </section>

        {{-- Recent Websites --}}
        <section class="mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Added Websites</h2>
                <a href="/websites" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">View all</a>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($recentWebsites as $website)
                    <x-website-card :website="$website" />
                @endforeach
            </div>
        </section>

        {{-- Groups & Tags --}}
        <section class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Groups --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Groups</h2>
                    <a href="{{ route('groups.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">View all</a>
                </div>
                <ul class="mt-3 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($groups as $group)
                        <li class="flex items-center justify-between py-2">
                            <span class="text-gray-700 dark:text-gray-300">{{ $group->name }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $group->websites_count }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Tags --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tags</h2>
                    <a href="{{ route('tags.index') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">View all</a>
                </div>
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach ($tags as $tag)
                        <x-tag-pill :tag="$tag" />
                    @endforeach
                </div>
            </div>

        </section>

    </div>
</div>
</x-app-layout>
