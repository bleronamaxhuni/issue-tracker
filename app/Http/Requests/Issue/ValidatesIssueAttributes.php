<?php

namespace App\Http\Requests\Issue;

trait ValidatesIssueAttributes
{
    /**
     * @return array<string, array<int, string>>
     */
    protected function issueRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:open,in_progress,closed'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
