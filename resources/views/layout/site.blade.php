<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Incluindo código --}}
    @stack('css')

    <title>@yield('title', 'Laravel App')</title>
</head>
<body>
    {{-- A diretiva @section cria uma sessão dentro do layout. Quando queremos criar uma sessão que vai receber conteúdo de outras páginas dentro do layout padrão, finalizamos a diretiva @section com o @show e não @endsection   --}}
    @section('sidbar')

        <ul>
            <li>Menu principal 1</li>
            <li>Menu principal 2</li>
            <li>Menu principal 3</li>
        </ul>

    @show

    {{-- Outra diretiva que serve para inserirmos conteúdo dentro de um layout padrão, é o @yeld. Tanto a @section como o @yield renderizam conteúdos da mesma forma, mas existem algumas diferenças entre eles--}}

    {{-- 1ª diferença - No @yeld não conseguimos abrir um bloco de código, ou seja, escrever html que servirá como conteúdo default --}}
    {{-- 2ª diferença - No @yeld não conseguimos manter o valor default dele e injetar um novo conteúdo--}}

    {{-- O @yeld faz parte do conteúdo principal, aquele que muda em todas as views --}}

    {{-- O @section é utilizado para inserir blocos de códigos, como um sidbar, um header, footer e outros --}}
     
    @yield('conteudo', 'Conteudo padrão do yeld')

    @stack('scripts')

</body>
</html>