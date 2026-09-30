<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; //
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'], //
            'items.*.product_id' => ['required', 'exists:products,id'], //
            'items.*.qty' => [
                'required', 
                'integer', 
                'min:1', //
                function ($attribute, $value, $fail) {
                    // Mendapatkan index dari items.*.qty (misal: items.0.qty -> 0)
                    $index = explode('.', $attribute)[1] ?? null;
                    $productId = $this->input("items.{$index}.product_id");

                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product && $value > $product->stock) {
                            $fail("Stok untuk produk {$product->name} tidak mencukupi (Tersisa: {$product->stock}).");
                        }
                    }
                }
            ],
        ];
    }
}