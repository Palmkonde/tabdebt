<x-navbar />
<div>
    <form action="{{ route('websites.store') }}" method="POST">
        @csrf
        <h1> Add new website </h1>
        
        <div class="mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="url">URL</label>
            <input type="url" id="url" name="url" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea type="text" id="description" name="description" rows="4"></textarea>
        </div>
        
        <div>
            <label for="rating">Rating</label>
            <select id="rating" name="rating">
                <option value="average" selected>Average</option>
                <option value="bad">Bad</option>
                <option value="good">Good</option>
            </select>
        </div>
        
        <div>
            <label for="group_id">Group</label>
            <select id="group_id" name="group_id" required>
                <option value="" disabled>Select a group</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Add Website</button>
    </form>
</div>
