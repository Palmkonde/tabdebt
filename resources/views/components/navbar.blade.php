<nav class="bg-white dark:bg-gray-800 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <a href="{{ route('workspace.index') }}" class="text-xl font-bold text-gray-900 dark:text-white">
            TabDebt
        </a>

        <ul class="flex gap-6">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['url'] }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        {{ $link['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
