<h1>{{ $name }}'s Workspace</h1>
<p> Welcome to your workspace, {{ $name }}! Here you can manage your websites and view your status. </p>

<section>
    <h2> Status </h2>
    <x-status countNum="{{ $websiteCount }}" label="Websites" />
    <x-status countNum="{{ $groupCount }}" label="Groups" />
</section>
