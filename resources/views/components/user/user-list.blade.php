<div class="container py-5">
    {{-- {{dd($users)}} --}}
    {{$attributes}}
    @if ($type === 'lista')
        <ul class="list-group mb-5">
            @foreach ($users as $user)
                <li class="list-group-item">{{ $user->name}} - {{ $user->email }}</li>
            @endforeach
        </ul>
    @elseif ($type === 'card')
        @foreach($users as $user)
            <div class="card mb-3 bg-danger text-white" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->id }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $user->name }}</h6>
                    <span class="card-text">{{ $user->email }}</span>
                </div>
            </div>
        @endforeach
    @endif
    
    
</div>