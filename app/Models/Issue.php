<?php

namespace App\Models;

use Database\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['project_id', 'title', 'description', 'status', 'priority', 'due_date'])]
class Issue extends Model
{
    /** @use HasFactory<IssueFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public const STATUSES = ['open', 'in_progress', 'closed'];

    public const PRIORITIES = ['low', 'medium', 'high'];

    public function editModalName(): string
    {
        return 'edit-issue-'.$this->id;
    }

    public function deleteModalName(): string
    {
        return 'delete-issue-'.$this->id;
    }

    public function statusLabel(): string
    {
        return str_replace('_', ' ', $this->status);
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->status !== 'closed'
            && $this->due_date->isPast();
    }
}
