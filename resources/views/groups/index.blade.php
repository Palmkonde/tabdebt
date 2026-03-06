<x-app-layout>
<x-navbar />

<div>
    <div>
        <h1>Groups</h1>
        <p>Organize your websites into groups.</p>
        <a href="{{ route('groups.create') }}">+ New Group</a>
    </div>

    @foreach ($groups as $group)
        <div>
            <div>
                <h2>{{ $group->name }}</h2>
                @if ($group->description)
                    <p>{{ $group->description }}</p>
                @endif
                <span>{{ $group->websites->count() }} {{ Str::plural('site', $group->websites->count()) }}</span>
                <a href="{{ route('groups.edit', $group) }}">Edit</a>
                <a href="{{ route('groups.destroy', $group) }}" onclick="return confirm('Are you sure?')">Delete</a>
            </div>

            @if ($group->websites->isEmpty())
                <p>No websites in this group yet.</p>
            @else
                <div>
                    @foreach ($group->websites as $website)
                        <x-website-card :website="$website" />
                        <span> Delete from group </span>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
</x-app-layout>
