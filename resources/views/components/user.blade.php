<ul>
    @foreach ($users as $user)
        <li>{{ $user->id }} - {{ $user->name }}</li>
    @endforeach
</ul>