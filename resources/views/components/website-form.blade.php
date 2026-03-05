<div>
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $website->name ?? '') }}" required>
</div>

<div>
    <label for="url">URL</label>
    <input type="url" id="url" name="url" value="{{ old('url', $website->url ?? '') }}" required>
</div>

<div>
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $website->description ?? '') }}</textarea>
</div>

<div>
    <label for="rating">Rating</label>
    <select id="rating" name="rating">
        @foreach (['average', 'bad', 'good'] as $rating)
            <option value="{{ $rating }}" @selected(old('rating', $website->rating ?? 'average') === $rating)>
                {{ ucfirst($rating) }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="group_id">Group</label>
    <select id="group_id" name="group_id" required>
        <option value="" disabled>Select a group</option>
        @foreach ($groups as $group)
            <option value="{{ $group->id }}" @selected(old('group_id', $website->group_id ?? '') == $group->id)>
                {{ $group->name }}
            </option>
        @endforeach
    </select>
</div>
