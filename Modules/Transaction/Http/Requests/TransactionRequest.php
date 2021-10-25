<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'value' => 'required|numeric',
            'payer' => 'required|numeric',
            'payee' => 'required|numeric',
        ];
    }

    /**
     * Override validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'value.required' => 'Transaction value is required',
            'payer.required' => 'A payer id is required',
            'payee.required' => 'A payee id is required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
