<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
  public function show($item_id)
  {
    $item = Item::findOrFail($item_id);
    $comments = $item->comments()->latest()->get();

    return view('comments', compact('item', 'comments'));
  }

  public function store(Request $request, $item_id)
  {

    $request->validate([
      'content' => 'required|string|max:255',
    ]);

    $comment = new Comment;
    $comment->user_id = auth()->id();
    $comment->item_id = $item_id;
    $comment->content = $request->input('content');
    $comment->save();

    return redirect()->route('comments.show', ['item_id' => $item_id]);
  }

  public function destroy(Comment $comment)
  {
    $comment->delete();
    return back()->with('success', 'コメントが削除されました。');
  }

  
}
