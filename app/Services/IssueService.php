<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Tag;
use App\Models\User;
use App\Presenters\TagPresenter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class IssueService
{
    public function __construct(private TagPresenter $tags) {}

  /**
   * @return array<string, mixed>
   */
  public function showViewData(Issue $issue, User $user): array
  {
    $issue->load(['project', 'tags', 'assignees']);

    $isOwner = $issue->isOwnedBy($user);

    $hasAnyTags = false;
    $availableTags = collect();
    $availableUsers = collect();

    if ($isOwner) {
      $hasAnyTags = Tag::query()->exists();
      $availableTags = Tag::query()
        ->whereNotIn('id', $issue->tags->modelKeys())
        ->orderBy('name')
        ->get();
      $availableUsers = User::query()
        ->whereNotIn('id', $issue->assignees->modelKeys())
        ->orderBy('name')
        ->get();
    }

    return [
      'issue' => $issue,
      'isOwner' => $isOwner,
      'hasAnyTags' => $hasAnyTags,
      'availableTags' => $availableTags,
      'availableUsers' => $availableUsers,
      'hasAttachedTags' => $issue->tags->isNotEmpty(),
      'hasAttachedAssignees' => $issue->assignees->isNotEmpty(),
      'hasAvailableTags' => $availableTags->isNotEmpty(),
      'hasAvailableUsers' => $availableUsers->isNotEmpty(),
      'commentAuthorName' => $user->name,
    ];
  }

  public function paginatedForRequest(Request $request): LengthAwarePaginator
  {
    $issues = Issue::query()
      ->forUser($request->user())
      ->with(['project', 'tags', 'assignees'])
      ->filtered($this->filtersFromRequest($request))
      ->latest()
      ->paginate(5)
      ->withQueryString();

    return $this->markOwnership($issues, $request->user());
  }

  /**
   * @return array<string, string>
   */
  public function filtersFromRequest(Request $request): array
  {
    return array_filter(
      $request->only(['status', 'priority', 'tag', 'search']),
      fn (mixed $value) => filled($value),
    );
  }

  public function hasActiveFilters(array $filters): bool
  {
    return $filters !== [];
  }

  public function markOwnership(LengthAwarePaginator $issues, User $user): LengthAwarePaginator
  {
    return $issues->through(function (Issue $issue) use ($user) {
      $issue->setAttribute('is_owned', $issue->project->user_id === $user->id);

      return $issue;
    });
  }

  /**
   * @return array<string, int|string|null>
   */
  public function tagPayload(Tag $tag): array
  {
    return [
      'id' => $tag->id,
      'name' => $tag->name,
            'color' => $this->tags->displayColor($tag),
    ];
  }

  /**
   * @return array<string, int|string>
   */
  public function assigneePayload(User $user): array
  {
    return [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
    ];
  }

  public function attachTag(Issue $issue, Tag $tag): bool
  {
    if ($issue->tags()->where('tags.id', $tag->id)->exists()) {
      return false;
    }

    $issue->tags()->attach($tag);

    return true;
  }

  public function detachTag(Issue $issue, Tag $tag): void
  {
    $issue->tags()->detach($tag);
  }

  public function attachAssignee(Issue $issue, User $user): bool
  {
    if ($issue->assignees()->where('users.id', $user->id)->exists()) {
      return false;
    }

    $issue->assignees()->attach($user);

    return true;
  }

  public function detachAssignee(Issue $issue, User $user): void
  {
    $issue->assignees()->detach($user);
  }
}
