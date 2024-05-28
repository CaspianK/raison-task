<?php

namespace App\Http\Requests\Purchases;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'store_id' => 'required|numeric|exists:stores,id',
            'currency_id' => 'required|numeric|exists:currencies,id',
            'amount' => 'required|numeric|min:0',
            'bought_at' => 'required|date',
            'document' => 'required|file|mimes:pdf,jpg,jpeg'
        ];
    }
}
