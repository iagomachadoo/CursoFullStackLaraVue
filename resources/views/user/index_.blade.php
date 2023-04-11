<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User</title>
</head>
<body>
    {{-- <h1>Usuários</h1> --}}

    {{-- DIRETIVA INCLUDE --}}

    {{-- @include('heading')  --}}
    {{-- @include('heading2') --}}

    {{-- IncludeIf() só inclui a view caso ela exista, senão existir, nada é mostrado, nem mesmo um erro--}} 
    {{-- @includeIf('heading2') --}}

    {{-- includeWhen() tem como primeiro parâmetro uma condição, que caso retorne true, executa o segundo parâmetro, ou seja, motra a view que foi passada. --}}
    {{-- @includeWhen($quant >= 10, 'heading', [
        'title' => 'Usuários'
    ]) --}}

    {{-- includeUnless() recebe como primeiro parâmetro uma condição, que caso retorn false, o segundo parâmetro é executado, ou seja, mostra a view que foi passada  --}}
    {{-- @includeUnless($quant > 10, 'heading') --}}

    {{-- includeFirst() recebe como parâmetro um array de views, onde fará a renderização da primeira que existir --}}
    {{-- @includeFirst(['heading2', 'heading']) --}}

    {{-- Passando dados para as views através do @include --}}
    {{-- Para isso, devemos passar como último parâmetro, um array contendo o nome da variável e seu valor --}}
    {{-- @include('heading', [
        'title' => 'Usuários'
    ]) --}}

    {{-- Loop e include --}}
    {{-- @forelse ($users as $user)

        @include('user.user', [
            'user' => $user
        ])

    @empty

        <p>A variável user está vazia</p>

    @endforelse --}}

    {{-- Renderizando include dentro de um foreach em apenas uma linha --}}
    {{-- @each('user.user', $users, 'user') --}}



    
    {{-- <p>{{ $user }}</p> --}}
    {{-- <p>{{ $user2 }}</p> --}}
    {{-- <p>{{ $userAdmin }}</p> --}}
    {{-- @foreach ($users as $user)
        <p>{{ $user->name . " (" . $user->email . ")" }}</p>
    @endforeach --}}

    {{-- {{date('d-m-Y')}} --}}

    {{-- Estrutura de decisão blade --}}
    
    {{-- <p>Total de usuários: {{$quant}}</p> --}}

    {{-- Estrutura padrão if else --}}
    {{-- @if ($quant === 1)
        <p>Eu tenho 1 usuário</p>
    @elseif ($quant > 1)    
        <p>Eu tenho vários usuários</p>
    @else
        <p>Eu não tenho usuários</p>
    @endif --}}

    {{-- Estrutura unless - é a negação do if, ou seja, só será executado caso o resultado seja falso (if(!...)) --}}
    {{-- @unless ($quant)
        <p>Eu não tenho usuários</p>
    @else
        <p>Eu tenho usuários</p>
    @endunless --}}

    {{-- Estrutura isset -  tenha o mesmo funcionamento da função isset do php - informa se uma variável foi inicida --}}
    {{-- @isset($users)
        <p>A variável $users foi iniciada</p>
    @else
        <p>A variável $users não foi iniciada</p>
    @endisset --}}

    {{-- Estrutura empty - tenha o mesmo funcionamento da função empty do php - determina se uma variável é vazia --}}
    {{-- @empty($users)
        <p>A variável $users é vazia</p>
    @else
        <p>A variável $users não é vazia</p>
    @endempty --}}

    {{-- Estruturas de loop blade --}}

    {{-- Loop for --}}
    {{-- <h3>Loop for</h3>
    @for ($i = 0; $i < $quant; $i++)
        {{ $i }}
    @endfor

    <br> --}}
    
    {{-- Loop foreach --}}
    {{-- <h3>Loop foreach</h3>
    @foreach ($users as $user)
        <p>{{ $user->name }}</p>        
    @endforeach --}}

    {{-- Loop forelse - funciona igual ao loop foreach, mas carrega consigo uma verificação empty para caso a variável esteja vaiza  --}}
    {{-- <h3>Loop foreach (forelse) com verificação</h3>
    @forelse ($users as $user)
        <p>{{ $user->name }}</p>
    @empty
        <p>Ainda não existe usuários cadastrados</p>
    @endforelse --}}

    {{-- Loop while --}}
    {{-- <h3>Loop while</h3>
    @php
        $j = 0;
    @endphp
    @while ($j < $quant)
        {{$j++}}
    @endwhile --}}

    {{-- Diretivas break e continue --}}
    {{-- <h3>Diretivas break e continue</h3> 
    @forelse ($users as $user) --}}
        {{-- Se o id for igual a 1, o @continue será executado, e o fluxo da execução voltará para o começo do loop, quando o id for diferente de 1, o loop ocorrerá normalmente, então, nesse caso, o usuário com id 1 não será exibido.--}}
        {{-- @if ($user->id === 1)
            @continue
        @endif --}}

        {{-- Podemos encurtar essa instrução passando a condição diretamente na cláusula @continue --}}
        {{-- @continue($user->id === 1) --}}

        {{-- <p>{{$user->id}} - {{$user->name}}</p> --}}
        
        {{-- O loop só ocorrerá ate quando o id for igual a 3, depois disso ele será encerrado por conta do @break, ou seja, só serão mostrados os usuários até o id 3 --}}
        {{-- @if($user->id === 3)
            @break
        @endif --}}

        {{-- Podemos encurtar essa instrução passando a condição diretamente na cláusula @break --}}
        {{-- @break($user->id === 3) --}}
    {{-- @empty
        <p>Não existe usuários</p>        
    @endforelse --}}

    {{-- Variável $loop do foreach --}}
    {{-- <h3>Variável $loop do foreach</h3>
    @forelse ($users as $user) --}}
        {{-- {{dd($loop)}} --}}
        {{-- {{$loop->iteration}} - 

        {{$loop->index}} - 
        
        @if ($loop->first)
            Primeiro do loop -         
        @endif

        @if ($loop->even)
            Elementos pares -         
        @endif

        @if ($loop->odd)
            Elementos ímpares -         
        @endif


        {{$user->id}} - {{$user->name}} <br>
    @empty
        <p>A variável users está vazia</p>
    @endforelse --}}

    {{-- Loop switch --}}
    {{-- <h3>Loop switch</h3>
    @switch($quant)
        @case(1)
            <p>Eu tenho 1 usuário</p>
            @break
        @case(5)
            <p>Eu tenho 5 usuários</p>
            @break
        @case(10)
            <p>Eu tenho 10 usuários</p>
            @break
        @default
            <p>Eu não tenho usuários</p>
    @endswitch --}}

</body>
</html>