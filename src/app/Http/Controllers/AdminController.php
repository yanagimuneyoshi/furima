<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $comments = Comment::all();
        return view('admin.index', compact('users', 'comments'));
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'ユーザーが削除されました');
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.index')->with('success', 'コメントが削除されました');
    }
}
