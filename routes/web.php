<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Projects
    Route::resource('projects', ProjectController::class)->except(['create', 'edit']);

    // Issues
    Route::post('projects/{project}/issues', [IssueController::class, 'store'])->name('projects.issues.store');
    Route::resource('issues', IssueController::class)->only(['index', 'show', 'update', 'destroy']);

    // Tags
    Route::post('issues/{issue}/tags/{tag}', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
    Route::delete('issues/{issue}/tags/{tag}', [IssueController::class, 'detachTag'])->name('issues.tags.detach');
    Route::resource('tags', TagController::class)->only(['index', 'store']);


    // Comments
    Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
    Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
