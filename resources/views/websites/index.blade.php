<x-app-layout>
<x-navbar />
<div>
    <h1>Websites</h1>
    <p> Here you can view and manage all your websites. </p>
    <ul>
        @foreach ($websites as $website)
            <li>
                <a href="{{ $website->url }}" target="_blank">{{ $website->name }}</a>
                <span><a href="{{ route('websites.edit', $website->id) }}" class="btn btn-sm btn-secondary">Edit</a></span>
                
                <form action="{{ route('websites.destroy', $website->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </li>
            
        @endforeach
    </ul>
    
    <a href="{{ route('websites.create') }}" class="btn btn-primary">Add New Website</a>
</div>
</x-app-layout>