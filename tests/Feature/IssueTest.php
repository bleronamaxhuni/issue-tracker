<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_issues_index(): void
    {
        $this->get(route('issues.index'))->assertRedirect(route('login'));
    }

    public function test_owner_can_create_issue_on_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(route('projects.issues.store', $project), [
            'title' => 'Bug fix',
            'description' => 'Something broke',
            'status' => 'open',
            'priority' => 'high',
        ]);

        $issue = Issue::query()->where('title', 'Bug fix')->first();

        $this->assertNotNull($issue);
        $this->assertSame($project->id, $issue->project_id);
        $response->assertRedirect(route('issues.show', $issue));
    }

    public function test_user_cannot_create_issue_on_others_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        $this->actingAs($other)
            ->post(route('projects.issues.store', $project), [
                'title' => 'Unauthorized issue',
                'status' => 'open',
                'priority' => 'medium',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('issues', ['title' => 'Unauthorized issue']);
    }

    public function test_assignee_can_view_issue(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $issue = Issue::factory()->for($project)->create();
        $issue->assignees()->attach($assignee);

        $this->actingAs($assignee)
            ->get(route('issues.show', $issue))
            ->assertOk()
            ->assertSee($issue->title);
    }

    public function test_assignee_cannot_update_issue(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $issue = Issue::factory()->for($project)->create(['title' => 'Original']);
        $issue->assignees()->attach($assignee);

        $this->actingAs($assignee)
            ->patch(route('issues.update', $issue), [
                'title' => 'Changed by assignee',
                'status' => 'open',
                'priority' => 'medium',
            ])
            ->assertForbidden();

        $this->assertSame('Original', $issue->fresh()->title);
    }

    public function test_owner_can_update_issue(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $issue = Issue::factory()->for($project)->create(['title' => 'Before']);

        $response = $this->actingAs($user)->patch(route('issues.update', $issue), [
            'title' => 'After',
            'status' => 'in_progress',
            'priority' => 'low',
        ]);

        $response->assertRedirect(route('issues.show', $issue));
        $this->assertSame('After', $issue->fresh()->title);
        $this->assertSame('in_progress', $issue->fresh()->status);
    }

    public function test_owner_can_delete_issue(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $issue = Issue::factory()->for($project)->create();

        $response = $this->actingAs($user)->delete(route('issues.destroy', $issue));

        $response->assertRedirect(route('projects.show', $project));
        $this->assertModelMissing($issue);
    }

    public function test_issue_index_only_shows_accessible_issues(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $outsider = User::factory()->create();

        $project = Project::factory()->for($owner)->create();
        $ownedIssue = Issue::factory()->for($project)->create(['title' => 'Owned issue']);
        $assignedIssue = Issue::factory()->for($project)->create(['title' => 'Assigned issue']);
        $assignedIssue->assignees()->attach($assignee);

        $otherProject = Project::factory()->for($outsider)->create();
        Issue::factory()->for($otherProject)->create(['title' => 'Hidden issue']);

        $this->actingAs($owner)
            ->get(route('issues.index'))
            ->assertOk()
            ->assertSee('Owned issue')
            ->assertSee('Assigned issue')
            ->assertDontSee('Hidden issue');

        $this->actingAs($assignee)
            ->get(route('issues.index'))
            ->assertOk()
            ->assertSee('Assigned issue')
            ->assertDontSee('Owned issue')
            ->assertDontSee('Hidden issue');
    }

    public function test_issue_index_json_search_returns_results(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Issue::factory()->for($project)->create(['title' => 'Searchable bug']);

        $response = $this->actingAs($user)
            ->getJson(route('issues.index', ['search' => 'Searchable']));

        $response->assertOk()
            ->assertJsonStructure(['html', 'count', 'count_label'])
            ->assertJsonFragment(['count' => 1]);

        $this->assertStringContainsString('Searchable bug', $response->json('html'));
    }
}
