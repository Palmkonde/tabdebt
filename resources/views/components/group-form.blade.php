@props(['group' => null, 'tags' => collect()])

<div class="space-y-6">
    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $group->name ?? '') }}" required
               placeholder="e.g. Work Resources"
               class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300" />
        @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Description</label>
        <textarea id="description" name="description" rows="3"
                  placeholder="What kind of websites will this group hold?"
                  class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300 resize-none">{{ old('description', $group->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tags (hidden for "Other" group) --}}
    @if (!isset($group) || $group->name !== 'Other')
        <x-tag-select :tags="$tags" :selected="isset($group) ? $group->tags->pluck('id')->toArray() : []" />
    @endif
</div>
