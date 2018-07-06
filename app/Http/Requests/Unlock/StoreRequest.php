<?php

namespace App\Http\Requests\Unlock;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address' => 'required|exists:players,address',
            'lifetime' => 'required|in:60,1440,10080,43200',
            'timestamp' => 'required',
            'signature' => 'required',
        ];
    }
}
