@props(['website', 'group' => null])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-col justify-between">
    <div>
        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $website->name }}</h4>
        <p class="text-sm text-amber-600 dark:text-amber-400 truncate mt-1">
            <a href="{{ $website->url }}" target="_blank" class="hover:underline">{{ $website->url }}</a>
        </p>
        @if($website->description)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $website->description }}</p>
        @endif
        <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full mt-2
            @if($website->rating === 'good') bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
            @elseif($website->rating === 'bad') bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300
            @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
            @endif">{{ ucfirst($website->rating) }}</span>
        @if ($website->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1 mt-2">
                @foreach ($website->tags as $tag)
                    <x-tag-pill :tag="$tag" />
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('websites.edit', $website->id) }}"
           class="text-sm text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
        <form action="{{ route('websites.destroy', $website->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-sm text-red-600 dark:text-red-400 hover:underline">Delete</button>
        </form>
        
        @if($group)
            <form action="{{ route('groups.websites.remove', ['group' => $group->id, 'website' => $website->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-sm text-orange-600 dark:text-orange-400 hover:underline">Remove from Group</button>
            </form>
        @endif
    </div>
</div>
