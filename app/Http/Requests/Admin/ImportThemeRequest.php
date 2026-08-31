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
            'theme_file' => [
                'required',
                'file',
                'max:20480',
                function ($attribute, $value, $fail) {
                    if ($value && $value->isValid()) {
                        $ext = strtolower($value->getClientOriginalExtension());
                        if (! in_array($ext, ['json', 'zip', 'txt'])) {
                            $fail('الملف يجب أن يكون بصيغة JSON أو ZIP فقط.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'theme_file.required' => 'يرجى اختيار ملف القالب أولاً.',
            'theme_file.file'     => 'الملف المرفوع غير صالح.',
            'theme_file.max'      => 'حجم الملف لا يتجاوز 20 ميغابايت.',
        ];
    }
}
