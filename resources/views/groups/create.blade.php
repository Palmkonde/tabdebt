<x-app-layout>
<x-navbar />

<div>
    <a href="{{ route('groups.index') }}">Back to Groups</a>

    <div>
        <h1>Create New Group</h1>
        <p>Add a new group to organize your websites.</p>

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf

            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <p>{{ $message }}</p>
                @enderror
            </div>

            <div>
                <a href="{{ route('groups.index') }}">Cancel</a>
                <button type="submit">Create Group</button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
