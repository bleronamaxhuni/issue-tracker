<?php

namespace App\Support;

class FlashStatus
{
  /**
   * @var array<string, string>
   */
  private const MESSAGES = [
    'project-created' => 'Project created successfully.',
    'project-updated' => 'Project updated successfully.',
    'project-deleted' => 'Project deleted successfully.',
    'issue-created' => 'Issue created successfully.',
    'issue-updated' => 'Issue updated successfully.',
    'issue-deleted' => 'Issue deleted successfully.',
    'tag-created' => 'Tag created successfully.',
  ];

  public static function message(?string $status): ?string
  {
    if ($status === null || ! isset(self::MESSAGES[$status])) {
      return null;
    }

    return __(self::MESSAGES[$status]);
  }
}
