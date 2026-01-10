<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get all comments for a specific commentable (with nested replies)
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
        ]);

        // Get top-level comments with all nested replies
        $comments = Comment::forCommentable($validated['commentable_type'], $validated['commentable_id'])
            ->topLevel()
            ->with(['user', 'allReplies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return $this->formatComment($comment);
            }),
        ]);
    }

    /**
     * Store a new comment or reply
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Get authenticated user
        $user = $request->user();

        // Create comment
        $comment = Comment::create([
            'commentable_type' => $validated['commentable_type'],
            'commentable_id' => $validated['commentable_id'],
            'user_id' => $user->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Load relationships
        $comment->load(['user', 'parent']);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => $this->formatComment($comment),
        ], 201);
    }

    /**
     * Display a specific comment with its replies
     */
    public function show(Comment $comment)
    {
        $comment->load(['user', 'allReplies.user', 'parent']);

        return response()->json([
            'comment' => $this->formatComment($comment),
        ]);
    }

    /**
     * Update a comment
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to update this comment'
            ], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        $comment->load(['user', 'parent']);

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $this->formatComment($comment),
        ]);
    }

    /**
     * Soft delete a comment
     */
    public function destroy(Request $request, Comment $comment)
    {
        // Check if user owns the comment
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    /**
     * Format comment data with nested replies
     */
    private function formatComment($comment)
    {
        return [
            'id' => $comment->id,
            'commentable_type' => $comment->commentable_type,
            'commentable_id' => $comment->commentable_id,
            'content' => $comment->content,
            'parent_id' => $comment->parent_id,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'email' => $comment->user->email,
                'avatar' => $comment->user->avatar,
            ],
            'replies' => $comment->relationLoaded('allReplies')
                ? $comment->allReplies->map(function ($reply) {
                    return $this->formatComment($reply);
                })
                : [],
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'deleted_at' => $comment->deleted_at,
        ];
    }
}
