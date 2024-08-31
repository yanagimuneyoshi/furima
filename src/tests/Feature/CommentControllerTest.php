<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class CommentControllerTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    // CSRFトークンの検証を無効化
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

    // 必要なモデルのファクトリを作成
    $this->user = User::factory()->create();
    $this->item = Item::factory()->create();
  }

  /** @test */
  public function it_displays_comments_for_an_item()
  {
    // Arrange: Prepare the data
    $comment = Comment::factory()->create(['item_id' => $this->item->id]);

    // Act: Make the request to the show route
    $response = $this->get(route('comments.show', $this->item->id));

    // Assert: Check that the response contains the comment
    $response->assertStatus(200);
    $response->assertSee($comment->content);
  }

  /** @test */
  /** @test */
  public function it_stores_a_new_comment()
  {
    // Arrange: セッションを開始してCSRFトークンを設定
    Session::start();

    // Act: Simulate a logged-in user and make a POST request to store a comment
    $response = $this->actingAs($this->user)->post(route('comments.store', $this->item->id), [
      '_token' => csrf_token(),  // 明示的にCSRFトークンを含める
      'content' => 'テストコメント'
    ]);

    // Assert: Check that the comment is in the database
    $response->assertStatus(302);  // リダイレクトのステータスコードを確認
    $response->assertRedirect(route('comments.show', $this->item->id));  // 正しいリダイレクト先を確認

    $this->assertDatabaseHas('comments', [
      'item_id' => $this->item->id,
      'user_id' => $this->user->id,
      'content' => 'テストコメント'
    ]);
  }


  /** @test */
  public function it_deletes_a_comment()
  {
    // Arrange: Prepare a comment to delete
    $comment = Comment::factory()->create([
      'item_id' => $this->item->id,
      'user_id' => $this->user->id,
    ]);

    // セッションを開始してCSRFトークンを設定
    Session::start();

    // Act: Simulate a logged-in user and make a DELETE request
    $response = $this->actingAs($this->user)->delete(route('comments.destroy', $comment->id), [
      '_token' => csrf_token(),  // 明示的にCSRFトークンを含める
    ]);

    // Assert: Check that the comment is deleted
    $response->assertStatus(302);  // ステータスコードが302（リダイレクト）であることを確認
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }

}
