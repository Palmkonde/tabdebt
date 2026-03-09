<a href="{{ route('tags.show', $tag) }}"
   class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-amber-900 hover:text-amber-700 dark:hover:text-amber-300 transition-colors">
    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $tag->color }};"></span>
    {{ $tag->name }}
</a>
