<nav>
    <div>
        <a href="{{ route('workspace.index') }}">
            <strong>TabDebt</strong>
        </a>

        <ul>
            @foreach ($links as $link)
                <li><a href="{{ $link['url'] }}">{{ $link['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
</nav>
