<x-navbar />
<div>
    <form action="{{ route('websites.store') }}" method="POST">
        @csrf
        <h1>Add new website</h1>
        <x-website-form :groups="$groups" />
        <button type="submit">Add Website</button>
    </form>
</div>
