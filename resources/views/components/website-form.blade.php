@props(['website' => null, 'groups', 'tags' => collect()])

<div class="space-y-6">

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $website->name ?? '') }}" required
               placeholder="e.g. Laravel Documentation"
               class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300" />
        @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- URL --}}
    <div>
        <label for="url" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">URL</label>
        <input type="url" id="url" name="url" value="{{ old('url', $website->url ?? '') }}" required
               placeholder="https://"
               class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300 font-mono" />
        @error('url')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Description</label>
        <textarea id="description" name="description" rows="3"
                  placeholder="What is this website about?"
                  class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300 resize-none">{{ old('description', $website->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Rating & Group --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        {{-- Rating --}}
        <div>
            <label for="rating" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Rating</label>
            <select id="rating" name="rating"
                    class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white focus:border-amber-500 focus:ring-0 transition-colors duration-300 cursor-pointer">
                @foreach (['average', 'bad', 'good'] as $rating)
                    <option value="{{ $rating }}" @selected(old('rating', $website->rating ?? 'average') === $rating)
                            class="bg-white dark:bg-gray-800">
                        {{ ucfirst($rating) }}
                    </option>
                @endforeach
            </select>
            @error('rating')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Group --}}
        <div>
            <label for="group_id" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Group</label>
            <select id="group_id" name="group_id" required
                    class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white focus:border-amber-500 focus:ring-0 transition-colors duration-300 cursor-pointer">
                <option value="" disabled class="bg-white dark:bg-gray-800">Select a group</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" @selected(old('group_id', $website->group_id ?? '') == $group->id)
                            class="bg-white dark:bg-gray-800">
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
            @error('group_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

    </div>

    {{-- Tags --}}
    @if ($tags->isNotEmpty())
        <div>
            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-3 tracking-wide uppercase">Tags</label>
            <div class="flex flex-wrap gap-3">
                @foreach ($tags as $tag)
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                               @checked(in_array($tag->id, old('tags', isset($website) ? $website->tags->pluck('id')->toArray() : [])))
                               class="rounded border-gray-300 dark:border-gray-600 text-amber-500 focus:ring-amber-500 dark:bg-gray-700" />
                        <span class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $tag->color }};"></span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('tags')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    @endif

</div>
