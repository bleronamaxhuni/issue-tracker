<?php

namespace App\Models;

use Database\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param  Builder<Issue>  $query
     */
    public function scopeForUser(Builder $query, User $user): void
    {
        $query->whereHas('project', fn (Builder $projectQuery) => $projectQuery->where('user_id', $user->id));
    }

    /**
     * @param  Builder<Issue>  $query
     * @param  array<string, mixed>  $filters
     */
    public function scopeFiltered(Builder $query, array $filters): void
    {
        $query
            ->when($filters['status'] ?? null, fn (Builder $issueQuery, string $status) => $issueQuery->where('status', $status))
            ->when($filters['priority'] ?? null, fn (Builder $issueQuery, string $priority) => $issueQuery->where('priority', $priority))
            ->when($filters['tag'] ?? null, fn (Builder $issueQuery, string $tag) => $issueQuery->whereHas(
                'tags',
                fn (Builder $tagQuery) => $tagQuery->where('tags.id', $tag)
            ))
            ->when($filters['search'] ?? null, function (Builder $issueQuery, string $search) {
                $term = '%'.$search.'%';

                $issueQuery->where(function (Builder $searchQuery) use ($term) {
                    $searchQuery
                        ->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            });
    }
}
