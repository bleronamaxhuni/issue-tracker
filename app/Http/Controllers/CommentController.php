<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, Issue $issue): JsonResponse
    {
        $this->authorize('view', $issue);

        $comments = $issue->comments()
            ->latest()
            ->paginate(5);

        return response()->json([
            'data' => $comments->getCollection()->map(fn (Comment $comment) => $this->commentPayload($comment)),
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
            'next_page_url' => $comments->nextPageUrl(),
        ]);
    }

    public function store(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'comment' => $this->commentPayload($comment),
        ], 201);
    }

    /**
     * @return array<string, int|string>
     */
    private function commentPayload(Comment $comment): array
    {
        return [
            'id' => $comment->id,
            'author_name' => $comment->author_name,
            'body' => $comment->body,
            'created_at' => $comment->created_at->diffForHumans(),
        ];
    }
}
