<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TryoutRequest extends FormRequest
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
            'batch_id' => 'required|exists:batch_tryouts,id'
        ];
    }

    public function messages(): array
    {
        return [
            'batch_id.required' => 'Batch harus dipilih',
            'batch_id.exists' => 'Batch tidak tersedia',
        ];
    }
}
