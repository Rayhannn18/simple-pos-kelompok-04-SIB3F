@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<<<<<<< HEAD
    <h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>
    <div x-data="{
        cart: [],
        addToCart(id, name, price) {
            this.cart.push({ id, name, price });
        },
        subtotal() {
            return this.cart.reduce((sum, item) => sum + item.price, 0);
        }
    }">
        <div class="grid grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="border rounded-md p-3 cursor-pointer relative"
                        @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">

                        @if ($product->stock < 10)
                            <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded bg-amber-100 text-amber-700">
                                Stok Menipis
                            </span>
                        @endif

                        <p class="font-medium">{{ $product->name }}</p>
                        <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
                    </div>
            @endforeach
        </div>

        <div class="mt-4 border-t pt-3">
            <template x-for="item in cart" :key="item.id">
                <p x-text="item.name + ' - Rp ' + item.price"></p>
            </template>
            <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>
        </div>
    </div>
=======
<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

<div class="grid grid-cols-3 gap-4">
    @foreach ($products as $product)
        <div class="border rounded-md p-3">
            <p class="font-medium">{{ $product->name }}</p>
            <p class="text-sm text-slate-500">
                Rp {{ number_format($product->price) }}
            </p>
        </div>
    @endforeach
</div>

{{-- Tambahkan method removeFromCart di x-data --}}
<div x-data="{
    cart: [],
    addToCart(id, name, price) {
        this.cart.push({ id, name, price });
    },
    removeFromCart(index) {
        this.cart.splice(index, 1);
    },
    subtotal() {
        return this.cart.reduce((sum, item) => sum + item.price, 0);
    }
}">

...

{{-- Di area template x-for keranjang, ubah jadi seperti ini: --}}
<div class="mt-4 border-t pt-3">
    <template x-for="(item, index) in cart" :key="index">
        <div class="flex justify-between items-center mb-1">
            <p x-text="item.name + ' - Rp ' + item.price"></p>
            <button @click="removeFromCart(index)" class="text-xs text-red-500 hover:underline">Hapus</button>
        </div>
    </template>
    <p class="font-semibold mt-2">Subtotal: Rp <span x-text="subtotal()"></span></p>
</div>
>>>>>>> main
@endsection

