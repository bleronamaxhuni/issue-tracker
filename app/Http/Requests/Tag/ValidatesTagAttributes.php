<?php

namespace App\Http\Requests\Tag;

trait ValidatesTagAttributes
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function tagRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'color' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }
}
