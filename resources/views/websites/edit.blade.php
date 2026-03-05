<x-navbar />
<div>
    <form action="{{ route('websites.update', $website->id) }}" method="POST">
        @method("PUT")
        @csrf

        <h1> Update new website </h1>
        
        <div class="mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $website->name }}" required>
        </div>

        <div class="mb-3">
            <label for="url">URL</label>
            <input type="url" id="url" name="url" value="{{ $website->url }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea type="text" id="description" name="description" rows="4">{{ $website->description }}</textarea>
        </div>
        
        <div>
            <label for="rating">Rating</label>
            <select id="rating" name="rating">
                <option value="average" {{ $website->rating == 'average' ? 'selected' : '' }}>Average</option>
                <option value="bad" {{ $website->rating == 'bad' ? 'selected' : '' }}>Bad</option>
                <option value="good" {{ $website->rating == 'good' ? 'selected' : '' }}>Good</option>
            </select>
        </div>
        
        <div>
            <label for="group_id">Group</label>
            <select id="group_id" name="group_id" required>
                <option value="" disabled>Select a group</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ $website->group_id == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Website</button>
    </form>
</div>
