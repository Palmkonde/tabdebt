<x-navbar />
<div>
    <h1>Websites</h1>
    <p> Here you can view and manage all your websites. </p>
    <ul>
        @foreach ($websites as $website)
            <li><a href="{{ $website->url }}" target="_blank">{{ $website->name }}</a></li>
        @endforeach
    </ul>
</div>
