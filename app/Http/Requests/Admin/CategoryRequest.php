<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;

        return [
            'nama' => ['required', 'string', 'max:100', Rule::unique('categories', 'nama')->ignore($id)],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ];
    }
}
