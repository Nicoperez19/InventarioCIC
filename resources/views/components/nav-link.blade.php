@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-light-cyan text-sm font-medium leading-5 text-white focus:outline-none focus:border-light-cyan transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-light-gray hover:text-white hover:border-light-gray/30 focus:outline-none focus:text-white focus:border-light-gray/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
