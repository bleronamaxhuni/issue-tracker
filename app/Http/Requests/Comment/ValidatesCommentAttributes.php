<?php

namespace App\Http\Requests\Comment;

trait ValidatesCommentAttributes
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function commentRules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }
}
