<a href="{{ route('tags.show', $tag) }}"
   class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full text-white hover:opacity-80 transition-opacity"
   style="background-color: {{ $tag->color }};">
    {{ $tag->name }}
</a>
