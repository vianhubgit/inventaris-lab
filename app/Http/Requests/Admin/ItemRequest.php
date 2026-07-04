<?php

namespace App\Http\Requests\Admin;

use App\Enums\ItemStatus;
use App\Models\LabTable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'lab_id' => ['required', 'exists:labs,id'],
            'lab_table_id' => ['nullable', 'exists:lab_tables,id'],
            'jumlah_total' => ['required', 'integer', 'min:0', 'max:100000'],
            'status' => ['required', Rule::enum(ItemStatus::class)],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /** Pastikan meja yang dipilih benar-benar milik lab yang dipilih. */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $tableId = $this->input('lab_table_id');
            if (! $tableId) {
                return;
            }

            $belongs = LabTable::where('id', $tableId)
                ->whereHas('group', fn ($q) => $q->where('lab_id', $this->input('lab_id')))
                ->exists();

            if (! $belongs) {
                $validator->errors()->add('lab_table_id', 'Meja yang dipilih tidak berada di laboratorium tersebut.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama barang',
            'category_id' => 'kategori',
            'lab_id' => 'lokasi (lab)',
            'lab_table_id' => 'meja',
            'jumlah_total' => 'jumlah total',
        ];
    }
}
