<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col justify-between">
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $website->name }}</h4>
        <p class="text-sm text-blue-600 dark:text-blue-400 truncate mt-1">
            <a href="{{ $website->url }}" target="_blank">{{ $website->url }}</a>
        </p>
        @if($website->description)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $website->description }}</p>
        @endif
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $website->rating }}</p>
        <div class="flex flex-wrap gap-1 mt-2">
            @foreach ($website->tags as $tag)
                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full">{{ $tag->name }}</span>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('websites.edit', $website->id) }}"
           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
        <form action="{{ route('websites.destroy', $website->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
        </form>
    </div>
</div>
