@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full ps-3 pe-4 py-2 border-l-4 border-light-cyan text-start text-base font-medium text-white bg-light-cyan/10 focus:outline-none focus:text-white focus:bg-light-cyan/20 focus:border-light-cyan transition duration-150 ease-in-out'
            : 'flex items-center w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-light-gray hover:text-white hover:bg-dark-teal/20 hover:border-light-gray/30 focus:outline-none focus:text-white focus:bg-dark-teal/20 focus:border-light-gray/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
