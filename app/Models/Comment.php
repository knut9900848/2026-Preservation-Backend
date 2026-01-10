<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'content',
        'parent_id',
    ];

    /**
     * Get the parent commentable model (CheckSheet, Equipment, etc.)
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get all nested replies recursively
     */
    public function allReplies()
    {
        return $this->replies()->with('allReplies');
    }

    /**
     * Scope to get only top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get comments for a specific commentable
     */
    public function scopeForCommentable($query, $commentableType, $commentableId)
    {
        return $query->where('commentable_type', $commentableType)
                     ->where('commentable_id', $commentableId);
    }
}
