<x-navbar />
<h1>{{ $name }}'s Workspace</h1>
<p> Welcome to your workspace, {{ $name }}! Here you can manage your websites and view your status. </p>

<section>
    <h2> Status </h2>
    <x-status countNum="{{ $websiteCount }}" label="Websites" />
    <x-status countNum="{{ $groupCount }}" label="Groups" />
</section>

<section>
    <div>
        <h2> Recent Added Websites </h2>
        <p><a href="#">View all</a></p>
    <div>
    
    <div>
        @foreach($recentWebsites as $website)
            <div>
                <h4> {{ $website->name }} </h4>
                <p> {{ $website->url }} </p>
                <p> {{ $website->rating }} </p>
                <div>
                    @foreach ($website->tags as $tag)
                        <span> {{ $tag->name }} </span>
                    @endforeach
                <div>
            </div>
        @endforeach 
    </div>
</section>

<section>
    <h2> Groups </h2>
    <p><a href="#"> View all </a></p>
    
    <ul>
        @foreach($groups as $group)
            <li>
                <span>{{ $group->name }} </span>
                <span>({{ $group->websites_count }})</span>
            </li>
        @endforeach
    </ul>
</section>

<section>
    <h2> Tags </h2>
    <p><a href="#"> View all </a></p>

    <div>
        @foreach ($tags as $tag)
            <span> {{ $tag->name }} </span>
        @endforeach
    </div>
</section>
    
