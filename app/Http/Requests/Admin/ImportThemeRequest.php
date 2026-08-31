<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImportThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme_file' => ['required', 'file', 'mimes:json,txt,zip', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'theme_file.mimes' => 'الملف يجب أن يكون بصيغة JSON أو ZIP فقط.',
            'theme_file.max'   => 'حجم الملف لا يتجاوز 2 ميغابايت.',
        ];
    }
}
