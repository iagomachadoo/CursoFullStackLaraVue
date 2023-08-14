{{-- Mesclagem de classes --}}
{{-- <button {{ $attributes->merge(['class' => 'btn btn-'. $variant]) }}>
    {{$name}}
</button> --}}

{{-- Mesclagem de classes condicional --}}
<button {{ $attributes->class([
    'btn', 'btn-danger' => $isRed
    ])
    ->merge([
        'id' => 'btn-'.$variant, 
        'type' => $type, 
        'data-url' => 'https://...',
        'user-permision' => $attributes->prepends('salvar')
    ]) 
}}>
    {{-- Filtrando atributo com closure --}}
    {{-- {{ $attributes->filter(fn (string $value, string $key) => $key == 'data-url') }} --}}

    {{-- Recupera todos os atributos cuja chave comece com uma determinada string --}}
    {{-- {{ $attributes->whereStartsWith('user') }} --}}

    {{-- Recupera todos os atributos cuja chave não comece com uma determinada string  --}}
    {{-- {{ $attributes->whereDoesntStartWith('user') }} --}}

    {{-- Adicionando o método first() podemos renderizar o primeiro valor do atributo em um determinado pacote de atributos --}}
    {{-- {{ $attributes->whereStartsWith('data-url')->first() }} --}}

    {{-- Podemos substituir todo ocódigo que recupera o valor de um atributo, ou seja, aquele que utiliza o método first() pelo método get() que ambos retornam o mesmo valor --}}
    {{ $attributes->get('text-color') }}

    {{-- {{$attributes->get('data-url')}} --}}
    {{-- {{$name}} --}}

</button>