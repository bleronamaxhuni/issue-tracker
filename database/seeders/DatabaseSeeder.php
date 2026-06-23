<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $alice = User::factory()->create([
            'name' => 'Alice Owner',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob Owner',
            'email' => 'bob@example.com',
        ]);

        $this->call(TagSeeder::class);

        $bug = Tag::query()->where('name', 'bug')->first();
        $feature = Tag::query()->where('name', 'feature')->first();
        $enhancement = Tag::query()->where('name', 'enhancement')->first();
        $documentation = Tag::query()->where('name', 'documentation')->first();
        $urgent = Tag::query()->where('name', 'urgent')->first();
        $backend = Tag::query()->where('name', 'backend')->first();
        $frontend = Tag::query()->where('name', 'frontend')->first();

        $website = Project::query()->create([
            'user_id' => $alice->id,
            'name' => 'Website Redesign',
            'description' => 'Refresh the marketing site with a new layout, typography, and responsive components.',
            'start_date' => now()->subWeeks(2),
            'deadline' => now()->addMonths(2),
        ]);

        $mobile = Project::query()->create([
            'user_id' => $alice->id,
            'name' => 'Mobile App MVP',
            'description' => 'Ship a first version of the companion mobile app for iOS and Android.',
            'start_date' => now()->subDays(5),
            'deadline' => now()->addMonths(4),
        ]);

        $api = Project::query()->create([
            'user_id' => $bob->id,
            'name' => 'Public API',
            'description' => 'Expose a versioned REST API for third-party integrations.',
            'start_date' => now()->subMonth(),
            'deadline' => now()->addMonths(3),
        ]);

        $onboarding = Project::query()->create([
            'user_id' => $bob->id,
            'name' => 'Customer Onboarding',
            'description' => 'Improve signup flow, welcome emails, and in-app guidance for new customers.',
            'start_date' => now()->subWeeks(3),
            'deadline' => now()->addWeeks(6),
        ]);

        $homepageIssue = Issue::query()->create([
            'project_id' => $website->id,
            'title' => 'Redesign homepage hero section',
            'description' => 'Replace the static hero with a responsive layout and updated call-to-action buttons.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addWeeks(2),
        ]);
        $homepageIssue->tags()->attach([$feature->id, $frontend->id, $urgent->id]);
        $homepageIssue->assignees()->attach($bob);

        $navIssue = Issue::query()->create([
            'project_id' => $website->id,
            'title' => 'Fix mobile navigation overlap',
            'description' => 'The hamburger menu overlaps page content on screens narrower than 375px.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => now()->addWeek(),
        ]);
        $navIssue->tags()->attach([$bug->id, $frontend->id]);

        $footerIssue = Issue::query()->create([
            'project_id' => $website->id,
            'title' => 'Update footer links and legal copy',
            'description' => 'Refresh privacy policy links and add the new support email address.',
            'status' => 'closed',
            'priority' => 'low',
            'due_date' => now()->subDays(3),
        ]);
        $footerIssue->tags()->attach([$documentation->id]);

        $authIssue = Issue::query()->create([
            'project_id' => $mobile->id,
            'title' => 'Implement biometric login',
            'description' => 'Allow users to sign in with Face ID or fingerprint after the first successful login.',
            'status' => 'open',
            'priority' => 'high',
            'due_date' => now()->addMonth(),
        ]);
        $authIssue->tags()->attach([$feature->id, $backend->id]);

        $pushIssue = Issue::query()->create([
            'project_id' => $mobile->id,
            'title' => 'Push notifications not delivered on Android',
            'description' => 'Some devices stop receiving push notifications after the app is backgrounded overnight.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(10),
        ]);
        $pushIssue->tags()->attach([$bug->id, $backend->id, $urgent->id]);

        $offlineIssue = Issue::query()->create([
            'project_id' => $mobile->id,
            'title' => 'Add offline mode for issue list',
            'description' => 'Cache the most recent issues locally so users can browse them without connectivity.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => null,
        ]);
        $offlineIssue->tags()->attach([$enhancement->id, $frontend->id]);

        $rateLimitIssue = Issue::query()->create([
            'project_id' => $api->id,
            'title' => 'Document rate limiting headers',
            'description' => 'Add examples for X-RateLimit-Limit and X-RateLimit-Remaining to the API reference.',
            'status' => 'open',
            'priority' => 'medium',
            'due_date' => now()->addWeeks(3),
        ]);
        $rateLimitIssue->tags()->attach([$documentation->id, $backend->id]);

        $webhookIssue = Issue::query()->create([
            'project_id' => $api->id,
            'title' => 'Webhook retry logic returns duplicate events',
            'description' => 'Retries after a timeout can deliver the same payload twice to subscriber endpoints.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => now()->addDays(5),
        ]);
        $webhookIssue->tags()->attach([$bug->id, $backend->id, $urgent->id]);
        $webhookIssue->assignees()->attach($alice);

        $versioningIssue = Issue::query()->create([
            'project_id' => $api->id,
            'title' => 'Add v2 namespace for breaking changes',
            'description' => 'Introduce /v2 routes while keeping /v1 stable for existing integrations.',
            'status' => 'closed',
            'priority' => 'medium',
            'due_date' => now()->subWeek(),
        ]);
        $versioningIssue->tags()->attach([$feature->id, $backend->id]);

        $welcomeIssue = Issue::query()->create([
            'project_id' => $onboarding->id,
            'title' => 'Rewrite welcome email sequence',
            'description' => 'Shorten the copy and add a checklist of first steps for new accounts.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'due_date' => now()->addWeeks(2),
        ]);
        $welcomeIssue->tags()->attach([$enhancement->id, $documentation->id]);

        $tooltipIssue = Issue::query()->create([
            'project_id' => $onboarding->id,
            'title' => 'Add guided tooltips on dashboard',
            'description' => 'Highlight key areas for first-time users with dismissible onboarding hints.',
            'status' => 'open',
            'priority' => 'low',
            'due_date' => now()->addMonth(),
        ]);
        $tooltipIssue->tags()->attach([$feature->id, $frontend->id]);

        $importIssue = Issue::query()->create([
            'project_id' => $onboarding->id,
            'title' => 'CSV import fails on Windows line endings',
            'description' => 'Uploaded files with CRLF line endings produce empty rows during customer import.',
            'status' => 'open',
            'priority' => 'high',
            'due_date' => now()->addDays(7),
        ]);
        $importIssue->tags()->attach([$bug->id, $backend->id, $urgent->id]);

        $commentedIssues = [$homepageIssue, $pushIssue, $webhookIssue];

        foreach ($commentedIssues as $issue) {
            Comment::query()->create([
                'issue_id' => $issue->id,
                'author_name' => $alice->name,
                'body' => 'Started looking into this. I will post an update once I have a repro case.',
            ]);

            Comment::query()->create([
                'issue_id' => $issue->id,
                'author_name' => 'Bob Owner',
                'body' => 'Can we prioritize this for the current sprint? It is affecting several customers.',
            ]);
        }

        $homepageComments = [
            ['author_name' => 'Alice Owner', 'body' => 'Design mockups are ready in Figma — link shared in Slack.'],
            ['author_name' => 'Design Team', 'body' => 'Please use the updated spacing tokens from the design system.'],
            ['author_name' => 'Alice Owner', 'body' => 'Hero image assets exported at 1x and 2x resolutions.'],
            ['author_name' => 'Bob Owner', 'body' => 'Looks good. Ship when the mobile breakpoints are verified.'],
            ['author_name' => 'QA', 'body' => 'Tested on iPhone SE and Pixel 7 — no layout issues found.'],
            ['author_name' => 'Alice Owner', 'body' => 'Merged to staging. Ready for final review.'],
            ['author_name' => 'Bob Owner', 'body' => 'Approved. Closing after production deploy.'],
        ];

        foreach ($homepageComments as $comment) {
            Comment::query()->create([
                'issue_id' => $homepageIssue->id,
                'author_name' => $comment['author_name'],
                'body' => $comment['body'],
            ]);
        }

        Issue::factory()
            ->count(3)
            ->for($website)
            ->create()
            ->each(function (Issue $issue) use ($bug, $feature, $enhancement, $frontend, $backend) {
                $issue->tags()->attach(
                    collect([$bug, $feature, $enhancement, $frontend, $backend])
                        ->random(rand(1, 2))
                        ->pluck('id')
                        ->all()
                );

                Comment::factory()
                    ->count(rand(1, 3))
                    ->for($issue)
                    ->create();
            });

        Issue::factory()
            ->count(2)
            ->for($api)
            ->create()
            ->each(function (Issue $issue) use ($backend, $documentation) {
                $issue->tags()->attach(
                    collect([$backend, $documentation])->random(rand(1, 2))->pluck('id')->all()
                );

                Comment::factory()->count(rand(0, 2))->for($issue)->create();
            });
    }
}
