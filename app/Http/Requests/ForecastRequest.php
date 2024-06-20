<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForecastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location' => 'required|string',
            'days' => 'nullable|integer|min:1|max:5',
            'units' => 'nullable|string|in:metric,imperial',
        ];
    }
}
