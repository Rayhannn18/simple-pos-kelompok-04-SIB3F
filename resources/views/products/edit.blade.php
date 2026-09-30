@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<h1 class="text-lg font-semibold mb-4">Edit Produk</h1>

<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="name" class="block mb-1">Nama Produk</label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name) }}"
            class="border rounded px-3 py-2 w-full"
        >

        @error('name')
            <div class="text-red-600 mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="category_id" class="block mb-1">Kategori</label>

        <select
            id="category_id"
            name="category_id"
            class="border rounded px-3 py-2 w-full"
        >
            <option value="">-- Pilih Kategori --</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected(old('category_id', $product->category_id) == $category->id)
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
            <div class="text-red-600 mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="price" class="block mb-1">Harga</label>

        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $product->price) }}"
            class="border rounded px-3 py-2 w-full"
        >

        @error('price')
            <div class="text-red-600 mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="stock" class="block mb-1">Stok</label>

        <input
            type="number"
            id="stock"
            name="stock"
            value="{{ old('stock', $product->stock) }}"
            class="border rounded px-3 py-2 w-full"
        >

        @error('stock')
            <div class="text-red-600 mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button
        type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded"
    >
        Simpan Perubahan
    </button>
</form>
@endsection