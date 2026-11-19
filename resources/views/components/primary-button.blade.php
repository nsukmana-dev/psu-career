<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
