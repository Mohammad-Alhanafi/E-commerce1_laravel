<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // إضافة تعليق
  public function store(Request $request)
{
    $request->validate([
        'comment' => 'required|min:2|max:500'
    ]);

    // منع السبام (5 ثواني)
    if (session()->has('last_comment_time')) {

        $last = session('last_comment_time');

        if (now()->diffInSeconds($last) < 5) {

            return response()->json([
                'success' => false,
                'message' => 'انتظر 5 ثواني قبل إرسال تعليق جديد'
            ]);
        }
    }

    session([
        'last_comment_time' => now()
    ]);

    $comment = Comment::create([
        'name' => Auth::check()
            ? Auth::user()->name
            : 'زائر',

        'comment' => $request->comment
    ]);

    return response()->json([
        'success' => true,
        'comment' => $comment
    ]);
}

    // الرد
    public function reply(Request $request, $id)
    {
        $reply = Comment::create([
            'name' => null,
            'comment' => $request->comment,
            'parent_id' => $id,
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    // لايك
    public function like($id)
    {
        $comment = Comment::findOrFail($id);

        $existing = CommentLike::where('comment_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            CommentLike::create([
                'comment_id' => $id,
                'user_id' => Auth::id()
            ]);
            $liked = true;
        }

        return response()->json([
            'likes' => $comment->likes()->count(),
            'liked' => $liked
        ]);
    }

    // حذف
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (!Auth::check()) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $user = Auth::user();
        $isAdmin = in_array(strtolower($user->role ?? ''), ['admin', 'superadmin']);
        $isOwner = $comment->name && $comment->name === $user->name;

        if (!$isAdmin && !$isOwner) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function delete($id)
    {
        return $this->destroy($id);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if (!Auth::check()) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $user = Auth::user();
        $isAdmin = in_array(strtolower($user->role ?? ''), ['admin', 'superadmin']);
        $isOwner = $comment->name && $comment->name === $user->name;

        if (!$isAdmin && !$isOwner) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $comment->update([
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    public function fetch()
    {
        $comments = Comment::whereNull('parent_id')
            ->with(['replies.likes', 'likes'])
            ->latest()
            ->get();

        return response()->json($comments);
    }
}