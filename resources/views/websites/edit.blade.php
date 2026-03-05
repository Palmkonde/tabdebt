<x-navbar />
<div>
    <form action="{{ route('websites.update', $website->id) }}" method="POST">
        @method("PUT")
        @csrf
        <h1>Update website</h1>
        <x-website-form :groups="$groups" :website="$website" />
        <button type="submit">Update Website</button>
    </form>
</div>
