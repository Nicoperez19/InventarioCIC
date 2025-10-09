@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 focus:border-light-cyan focus:ring-light-cyan rounded-xl shadow-sm transition-colors']) }}>
