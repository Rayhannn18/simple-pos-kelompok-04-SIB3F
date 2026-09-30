@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container">
    <h1>Tambah Produk</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div>
            <label for="name">Nama Produk</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
            >

            @error('name')
                <div style="color: red;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">-- Pilih Kategori --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <div style="color: red;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label for="price">Harga</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price') }}"
            >

            @error('price')
                <div style="color: red;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label for="stock">Stok</label>
            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock') }}"
            >

            @error('stock')
                <div style="color: red;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit">
            Simpan
        </button>
    </form>
</div>
@endsection