@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full ps-3 pe-4 py-2 border-l-4 border-secondary-500 text-start text-base font-medium text-white bg-secondary-500/20 focus:outline-none focus:text-white focus:bg-secondary-500/30 focus:border-secondary-500 transition duration-200 ease-in-out'
            : 'flex items-center w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-primary-700 hover:text-primary-900 hover:bg-secondary-100 hover:border-secondary-300 focus:outline-none focus:text-primary-900 focus:bg-secondary-100 focus:border-secondary-300 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
