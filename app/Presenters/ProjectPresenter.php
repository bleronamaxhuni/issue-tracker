<?php

namespace App\Presenters;

use App\Models\Project;
use Illuminate\Support\Str;

class ProjectPresenter
{
    public function excerpt(Project $project, int $length = 140): ?string
    {
        return $project->description
            ? Str::limit($project->description, $length)
            : null;
    }

    public function deadlineTextClass(Project $project): string
    {
        return $project->isDeadlineOverdue() ? 'text-red-600' : '';
    }

    /**
     * @return array{label: string, class: string}|null
     */
    public function deadlineBadge(Project $project): ?array
    {
        if ($project->isDeadlineOverdue()) {
            return [
                'label' => __('Overdue'),
                'class' => 'text-red-600',
            ];
        }

        if ($project->isDeadlineSoon()) {
            return [
                'label' => __('Due soon'),
                'class' => 'text-amber-700',
            ];
        }

        return null;
    }
}
