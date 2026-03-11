<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <x-back-link :href="route('tags.show', $tag)" label="Back to Tag" />

        {{-- Form card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-10">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Tag</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Update details for <span class="text-gray-700 dark:text-gray-200 font-medium">{{ $tag->name }}</span>.</p>
            </div>

            <form action="{{ route('tags.update', $tag->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}" required
                               placeholder="e.g. laravel"
                               class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Color --}}
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Color</label>
                        <div class="flex items-center gap-4 py-3">
                            <input type="color" id="color" name="color" value="{{ old('color', $tag->color) }}"
                                   class="h-10 w-14 cursor-pointer rounded border-0 bg-transparent p-0" />
                            <span id="color-hex" class="text-sm font-mono text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700/60 px-2 py-0.5 rounded">{{ old('color', $tag->color) }}</span>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Preview --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 tracking-wide uppercase">Preview</label>
                        <div id="tag-preview" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-white"
                             style="background-color: {{ old('color', $tag->color) }};">
                            <span id="preview-name">{{ old('name', $tag->name) }}</span>
                        </div>
                    </div>
                </div>

                <x-form-actions :cancel-href="route('tags.show', $tag)" submit-label="Update Tag" />
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const colorInput = document.getElementById('color');
    const colorHex = document.getElementById('color-hex');
    const nameInput = document.getElementById('name');
    const preview = document.getElementById('tag-preview');
    const previewName = document.getElementById('preview-name');

    colorInput.addEventListener('input', function () {
        colorHex.textContent = colorInput.value;
        preview.style.backgroundColor = colorInput.value;
    });

    nameInput.addEventListener('input', function () {
        previewName.textContent = nameInput.value || 'tag';
    });
});
</script>

</x-app-layout>
