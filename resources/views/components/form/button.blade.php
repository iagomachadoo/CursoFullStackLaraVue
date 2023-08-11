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
    {{-- {{ $attributes->filter(fn (string $value, string $key) => $key == 'type') }} --}}
    
    {{-- {{$name}} --}}

</button>