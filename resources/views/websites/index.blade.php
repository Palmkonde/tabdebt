<x-navbar />
<div>
    <h1>Websites</h1>
    <p> Here you can view and manage all your websites. </p>
    <ul>
        @foreach ($websites as $website)
            <li>
                <a href="{{ $website->url }}" target="_blank">{{ $website->name }}</a>
                <span><a href="{{ route('websites.edit', $website->id) }}" class="btn btn-sm btn-secondary">Edit</a></span>
                <span><a href="{{ route('websites.destroy', $website->id) }}" class="btn btn-sm btn-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $website->id }}').submit();">Delete</a></span>
            </li>
            
        @endforeach
    </ul>
    
    <a href="{{ route('websites.create') }}" class="btn btn-primary">Add New Website</a>
</div>
