<x-app-layout>
<x-navbar />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Back link --}}
        <a href="{{ route('groups.index') }}"
           class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors mb-8 group">
            <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Groups
        </a>

        {{-- Form card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 sm:p-10">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Create New Group</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Add a new group to organize your websites.</p>
            </div>

            <form action="{{ route('groups.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1 tracking-wide uppercase">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                                  class="w-full bg-transparent border-0 border-b-2 border-gray-200 dark:border-gray-700 px-0 py-3 text-lg text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-gray-600 focus:border-amber-500 focus:ring-0 transition-colors duration-300 resize-none">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-10 flex items-center justify-end gap-4">
                    <a href="{{ route('groups.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Create Group
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
</x-app-layout>
