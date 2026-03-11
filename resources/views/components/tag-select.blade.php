@php
    $selectedIds = old('tags', $selected);
    $uniqueId = 'tag-select-' . uniqid();
@endphp

<div>
    <label for="{{ $uniqueId }}" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 tracking-wide uppercase">Tags</label>

    <select id="{{ $uniqueId }}" name="tags[]" multiple placeholder="Search and select tags...">
        @foreach ($tags as $tag)
            <option value="{{ $tag->id }}"
                    data-color="{{ $tag->color }}"
                    @selected(in_array($tag->id, $selectedIds))>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>

    @error('tags')
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>

<style>
    /* === Tom Select — TabDebt CI overrides === */

    /* Wrapper: underline-style input matching form inputs */
    .ts-wrapper.multi .ts-control {
        background: transparent !important;
        border: none !important;
        border-bottom: 2px solid #e5e7eb !important; /* gray-200 */
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0.5rem 0 !important;
        min-height: 2.75rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.375rem;
        font-family: 'Figtree', sans-serif;
    }

    /* Dark mode wrapper */
    .dark .ts-wrapper.multi .ts-control {
        border-bottom-color: #374151 !important; /* gray-700 */
    }

    /* Focus state: amber underline */
    .ts-wrapper.multi.focus .ts-control {
        border-bottom-color: #f59e0b !important; /* amber-500 */
        box-shadow: none !important;
    }

    /* Input text inside the control */
    .ts-wrapper .ts-control input[autocomplete],
    .ts-wrapper .ts-control > input {
        color: #ffffff !important;
        font-size: 1rem !important;
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        -webkit-text-fill-color: #ffffff !important;
    }

    /* Placeholder */
    .ts-wrapper .ts-control input::placeholder {
        color: #4b5563 !important; /* gray-600 */
        -webkit-text-fill-color: #4b5563 !important;
        opacity: 1 !important;
    }

    /* Dropdown panel */
    .ts-wrapper .ts-dropdown {
        background: #ffffff;
        border: 1px solid #e5e7eb; /* gray-200 */
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        margin-top: 0.5rem;
        font-family: 'Figtree', sans-serif;
    }

    .dark .ts-wrapper .ts-dropdown {
        background: #1f2937; /* gray-800 */
        border-color: #374151; /* gray-700 */
    }

    /* Dropdown options */
    .ts-wrapper .ts-dropdown .option {
        padding: 0.5rem 0.75rem;
        color: #111827; /* gray-900 */
        font-size: 0.875rem;
        cursor: pointer;
    }

    .dark .ts-wrapper .ts-dropdown .option {
        color: #f3f4f6; /* gray-100 */
    }

    /* Option hover / active */
    .ts-wrapper .ts-dropdown .option.active,
    .ts-wrapper .ts-dropdown .option:hover {
        background: #fffbeb !important; /* amber-50 */
        color: #92400e !important; /* amber-800 */
    }

    .dark .ts-wrapper .ts-dropdown .option.active,
    .dark .ts-wrapper .ts-dropdown .option:hover {
        background: #78350f !important; /* amber-900 */
        color: #fcd34d !important; /* amber-300 */
    }

    /* Selected tag items — colored pills with remove button */
    .ts-wrapper.multi .ts-control > .item {
        border: none !important;
        border-radius: 9999px;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #ffffff;
        line-height: 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        cursor: default;
    }

    /* Remove button on pills */
    .ts-wrapper.multi .ts-control > .item .remove {
        color: rgba(255, 255, 255, 0.7) !important;
        border: none !important;
        border-left: none !important;
        font-size: 1rem;
        line-height: 1;
        padding: 0 !important;
        margin-left: 0.125rem;
    }

    .ts-wrapper.multi .ts-control > .item .remove:hover {
        color: #ffffff !important;
        background: transparent !important;
    }

    /* No results message */
    .ts-wrapper .ts-dropdown .no-results {
        padding: 0.75rem;
        color: #9ca3af; /* gray-400 */
        font-size: 0.875rem;
        text-align: center;
        font-style: italic;
    }

    .dark .ts-wrapper .ts-dropdown .no-results {
        color: #6b7280; /* gray-500 */
    }

    /* Hide default Tom Select caret */
    .ts-wrapper .ts-control::after {
        display: none !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#{{ $uniqueId }}', {
            plugins: ['remove_button'],
            create: function (input, callback) {
                var randomColor = '#' + Math.floor(Math.random() * 0xFFFFFF).toString(16).padStart(6, '0');
                fetch('{{ route('tags.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ name: input, color: randomColor }),
                })
                .then(function (response) { return response.json(); })
                .then(function (tag) {
                    callback({ value: tag.id, text: tag.name, color: tag.color });
                });
            },
            maxOptions: null,
            render: {
                option: function (data, escape) {
                    const color = data.color || data.$option?.dataset?.color || '#6b7280';
                    return '<div class="flex items-center gap-2 py-1">'
                        + '<span style="background-color: ' + escape(color) + ';" class="w-3 h-3 rounded-full shrink-0"></span>'
                        + '<span>' + escape(data.text) + '</span>'
                        + '</div>';
                },
                item: function (data, escape) {
                    const color = data.color || data.$option?.dataset?.color || '#6b7280';
                    return '<div style="background-color: ' + escape(color) + ';">'
                        + escape(data.text)
                        + '</div>';
                },
                option_create: function (data, escape) {
                    return '<div class="create flex items-center gap-2 py-1 px-3 text-amber-600 dark:text-amber-400">'
                        + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>'
                        + '<span>Create <strong>' + escape(data.input) + '</strong></span>'
                        + '</div>';
                },
            },
        });
    });
</script>
