<?php

namespace App\Providers;

use App\Models\Issue;
use App\Presenters\IssuePresenter;
use App\Presenters\ProjectPresenter;
use App\Presenters\TagPresenter;
use App\Support\FlashStatus;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $issuePresenter = app(IssuePresenter::class);
        $projectPresenter = app(ProjectPresenter::class);
        $tagPresenter = app(TagPresenter::class);

        View::share(compact('issuePresenter', 'projectPresenter', 'tagPresenter'));

        View::composer('components.flash-message', function ($view) {
            $view->with('flashMessage', FlashStatus::message(session('status')));
        });

        View::composer([
            'issues.index',
            'issues.show',
            'projects.show',
            'dashboard',
            'issues.partials.form-fields',
            'issues.partials.edit-modal',
            'issues.partials.results',
            'components.issue-status-badge',
        ], function ($view) use ($issuePresenter) {
            $view->with([
                'issueStatuses' => Issue::STATUSES,
                'issuePriorities' => Issue::PRIORITIES,
                'issueStatusLabels' => $issuePresenter->statusLabels(),
            ]);
        });
    }
}
