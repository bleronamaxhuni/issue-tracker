<?php

namespace App\Http\Requests\Project;

trait ValidatesProjectAttributes
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function projectRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
