<?php

namespace App\Http\Requests\Admin;

class UpdateThemeRequest extends StoreThemeRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = ['sometimes', 'required', 'string', 'max:120'];

        return $rules;
    }
}
