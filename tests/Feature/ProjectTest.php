<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_projects_index(): void
    {
        $this->get(route('projects.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_own_projects_index(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'Alpha']);

        $this->actingAs($user)
            ->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Alpha');
    }

    public function test_user_can_create_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'New project',
            'description' => 'A test project',
            'start_date' => '2026-01-01',
            'deadline' => '2026-12-31',
        ]);

        $project = Project::query()->where('name', 'New project')->first();

        $this->assertNotNull($project);
        $this->assertSame($user->id, $project->user_id);
        $response->assertRedirect(route('projects.show', $project));
    }

    public function test_user_can_update_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'Old name']);

        $response = $this->actingAs($user)->patch(route('projects.update', $project), [
            'name' => 'Updated name',
            'description' => $project->description,
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $this->assertSame('Updated name', $project->fresh()->name);
    }

    public function test_user_can_delete_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $this->assertModelMissing($project);
    }

    public function test_user_cannot_view_other_users_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('projects.show', $project))
            ->assertForbidden();
    }

    public function test_user_cannot_update_other_users_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create(['name' => 'Original']);

        $this->actingAs($other)
            ->patch(route('projects.update', $project), [
                'name' => 'Hijacked',
                'description' => null,
            ])
            ->assertForbidden();

        $this->assertSame('Original', $project->fresh()->name);
    }

    public function test_user_cannot_delete_other_users_project(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        $this->actingAs($other)
            ->delete(route('projects.destroy', $project))
            ->assertForbidden();

        $this->assertModelExists($project);
    }

    public function test_project_store_requires_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('projects.index'))
            ->post(route('projects.store'), ['name' => ''])
            ->assertRedirect(route('projects.index'))
            ->assertSessionHasErrors('name');
    }
}
