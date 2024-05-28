<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id' => 'nullable|numeric|exists:stores,id',
            'currency_id' => 'nullable|numeric|exists:currencies,id',
            'amount' => 'nullable|numeric|min:0',
            'bought_at' => 'nullable|date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg'
        ];
    }
}
