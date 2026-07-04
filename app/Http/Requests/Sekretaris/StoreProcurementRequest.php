<?php

namespace App\Http\Requests\Sekretaris;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Procurement::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_new_item' => $this->boolean('is_new_item')]);
    }

    public function rules(): array
    {
        return [
            'is_new_item' => ['required', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'item_id' => ['nullable', 'required_if:is_new_item,false', 'exists:items,id'],
            'nama_barang_baru' => ['nullable', 'required_if:is_new_item,true', 'string', 'max:150'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:10000'],
            'alasan' => ['required', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'kategori',
            'item_id' => 'barang',
            'nama_barang_baru' => 'nama barang baru',
            'jumlah' => 'jumlah',
            'alasan' => 'alasan',
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required_if' => 'Silakan pilih barang yang sudah ada.',
            'nama_barang_baru.required_if' => 'Silakan isi nama barang baru.',
        ];
    }
}
