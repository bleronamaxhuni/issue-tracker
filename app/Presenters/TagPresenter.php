<?php

namespace App\Presenters;

use App\Models\Tag;

class TagPresenter
{
    public function displayColor(Tag $tag): string
    {
        return $tag->color ?? '#78716c';
    }
}
