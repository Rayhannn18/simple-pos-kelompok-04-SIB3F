<nav class="bg-slate-900 text-white px-4 py-3 flex gap-4 items-center">
    <span class="font-semibold">Simple POS</span>

    <a
        href="{{ route('pos.create') }}"
        class="{{ request()->routeIs('pos.create') ? 'bg-slate-700 px-3 py-1 rounded' : 'hover:underline' }}"
    >
        Kasir
    </a>

    <a
        href="{{ route('transactions.index') }}"
        class="{{ request()->routeIs('transactions.index') ? 'bg-slate-700 px-3 py-1 rounded' : 'hover:underline' }}"
    >
        Transaksi
    </a>
</nav>