<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $colorRule = ['nullable', 'string', 'regex:/^#?[0-9A-Fa-f]{3,8}$/'];

        return [
            'name'              => ['required', 'string', 'max:120'],
            'description'       => ['nullable', 'string', 'max:500'],
            'mode'              => ['nullable', Rule::in(config('theme.modes', ['light', 'dark', 'both']))],
            'status'            => ['nullable', Rule::in(config('theme.statuses', ['draft', 'published']))],
            'colors'            => ['nullable', 'array'],
            'colors.*'          => $colorRule,
            'overrides'         => ['nullable', 'array'],
            'overrides.*'       => ['boolean'],
            'auto_generate'     => ['nullable', 'boolean'],
            'preview_mode'      => ['nullable', Rule::in(['light', 'dark'])],
        ];
    }
}
