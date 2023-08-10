{{-- Mesclagem de classes --}}
{{-- <button {{ $attributes->merge(['class' => 'btn btn-'. $variant]) }}>
    {{$name}}
</button> --}}

{{-- Mesclagem de classes condicional --}}
<button {{ $attributes->class(['btn', 'btn-danger' => $isRed])->merge(['id' => 'btn-'.$variant, 'type' => $type]) }}>
    {{$name}}
</button>