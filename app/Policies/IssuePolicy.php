<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;

class IssuePolicy
{
    public function view(User $user, Issue $issue): bool
    {
        return $this->ownsProject($user, $issue->project)
            || $this->isAssignee($user, $issue);
    }

    public function update(User $user, Issue $issue): bool
    {
        return $this->ownsProject($user, $issue->project);
    }

    public function delete(User $user, Issue $issue): bool
    {
        return $this->ownsProject($user, $issue->project);
    }

    private function isAssignee(User $user, Issue $issue): bool
    {
        return $issue->assignees()->where('users.id', $user->id)->exists();
    }

    private function ownsProject(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
