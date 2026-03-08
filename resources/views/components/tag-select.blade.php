@php
    $selectedIds = old('tags', $selected);
@endphp

<div>
    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 tracking-wide uppercase">Tags</label>

    @if ($tags->isEmpty())
        <p class="text-sm text-gray-400 dark:text-gray-500 italic">No tags available.</p>
    @else
        <div class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                @php
                    $isChecked = in_array($tag->id, $selectedIds);
                @endphp
                <label class="group relative cursor-pointer">
                    <input type="checkbox"
                           name="tags[]"
                           value="{{ $tag->id }}"
                           @checked($isChecked)
                           class="peer sr-only" />

                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium
                                 border-2 transition-all duration-200
                                 border-gray-200 dark:border-gray-600
                                 text-gray-500 dark:text-gray-400
                                 hover:border-gray-300 dark:hover:border-gray-500
                                 peer-checked:border-transparent peer-checked:text-white peer-checked:shadow-md
                                 peer-checked:scale-105 peer-focus-visible:ring-2 peer-focus-visible:ring-amber-400 peer-focus-visible:ring-offset-2 dark:peer-focus-visible:ring-offset-gray-800"
                          data-color="{{ $tag->color }}"
                          @if($isChecked) style="background-color: {{ $tag->color }};" @endif>

                        {{-- Color dot (visible when unchecked) --}}
                        <span class="w-2.5 h-2.5 rounded-full shrink-0 transition-opacity duration-200"
                              style="background-color: {{ $tag->color }}; {{ $isChecked ? 'opacity: 0;' : '' }}"></span>

                        {{-- Checkmark (visible when checked) --}}
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
                             style="{{ $isChecked ? '' : 'display: none;' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>

                        {{ $tag->name }}
                    </span>
                </label>
            @endforeach
        </div>
    @endif

    @error('tags')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[name="tags[]"]').forEach(function (checkbox) {
            const pill = checkbox.nextElementSibling;
            const color = pill.dataset.color;
            const dot = pill.querySelector('span');
            const check = pill.querySelector('svg');

            function update() {
                if (checkbox.checked) {
                    pill.style.backgroundColor = color;
                    dot.style.opacity = '0';
                    check.style.display = 'inline';
                } else {
                    pill.style.backgroundColor = '';
                    dot.style.opacity = '1';
                    check.style.display = 'none';
                }
            }

            checkbox.addEventListener('change', update);
        });
    });
</script>
