<?php

namespace App\Presenters;

use App\Models\Issue;
use Illuminate\Support\Str;

class IssuePresenter
{
    public function labelForStatus(string $status): string
    {
        return str_replace('_', ' ', $status);
    }

    /**
     * @return array<string, string>
     */
    public function statusLabels(): array
    {
        return collect(Issue::STATUSES)
            ->mapWithKeys(fn (string $status) => [$status => $this->labelForStatus($status)])
            ->all();
    }

    public function statusLabel(Issue $issue): string
    {
        return $this->labelForStatus($issue->status);
    }

    public function excerpt(Issue $issue, int $length = 140): ?string
    {
        return $issue->description
            ? Str::limit($issue->description, $length)
            : null;
    }

    public function dueDateTextClass(Issue $issue): string
    {
        return $issue->isOverdue() ? 'font-medium text-red-600' : 'text-stone-500';
    }

    public function dueDateDisplayClass(Issue $issue): string
    {
        return $issue->isOverdue() ? 'font-medium text-red-600' : 'text-stone-900';
    }
}
