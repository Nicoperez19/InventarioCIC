<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-light-cyan to-dark-teal border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-dark-teal hover:to-navy-blue focus:bg-navy-blue active:bg-navy-blue focus:outline-none focus:ring-2 focus:ring-light-cyan focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02]']) }}>
    {{ $slot }}
</button>
