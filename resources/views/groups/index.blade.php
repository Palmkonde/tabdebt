<x-app-layout>
    <x-navbar />

    <h1> Groups </h1>
    <p> Here you can manage your groups. </p>

    @foreach ($groups as $group)
        <div class='text-white'>
            <h2> {{ $group->name }} </h2>
            <p> {{ $group->description }} </p>
            <span> Websites in this group: </span>
            @foreach ($group->websites()->get() as $website)
                <ul>
                    <li> {{ $website->name }} </li>
                </ul>   
            @endforeach
        </div>
    @endforeach
    
    <a href="{{ route('groups.create') }}" class="text-blue-500"> Create a new group </a>
</x-app-layout>