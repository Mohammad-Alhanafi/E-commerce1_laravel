<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
        protected $table = 'likes_comment';

    protected $fillable = [
        'comment_id',
        'user_id'
    ];
}
