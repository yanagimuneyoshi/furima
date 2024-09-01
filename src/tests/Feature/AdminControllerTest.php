<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    // CSRFトークンの検証を無効化
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    // テスト用の管理者とユーザーを作成
    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->regularUser = User::factory()->create(['role' => 'user']);
    $this->item = Item::factory()->create();
  }

  /** @test */
  public function admin_can_delete_user()
  {
    // Arrange: 通常のユーザーを作成
    $user = User::factory()->create(['role' => 'user']);

    // Act: 管理者としてログインし、ユーザー削除リクエストを送信
    $response = $this->actingAs($this->adminUser)->delete(route('admin.users.destroy', $user));

    // Assert: ユーザーが削除され、リダイレクトされることを確認
    $response->assertRedirect(route('admin.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $response->assertSessionHas('success', 'ユーザーが削除されました');
  }

  /** @test */
  public function admin_can_delete_comment()
  {
    // Arrange: コメントを作成
    $comment = Comment::factory()->create();

    // Act: 管理者としてログインし、コメント削除リクエストを送信
    $response = $this->actingAs($this->adminUser)->delete(route('admin.comments.destroy', $comment));

    // Assert: コメントが削除され、リダイレクトされることを確認
    $response->assertRedirect(route('admin.index'));
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    $response->assertSessionHas('success', 'コメントが削除されました');
  }

  /** @test */
  public function non_admin_cannot_delete_user()
  {
    // Arrange: 削除対象のユーザーを作成
    $targetUser = User::factory()->create(['role' => 'user']);

    // Act: 通常のユーザーとしてログインし、ユーザー削除リクエストを送信
    $response = $this->actingAs($this->regularUser)->delete(route('admin.users.destroy', $targetUser));

    // Assert: アクセスが拒否されることを確認
    $response->assertStatus(403);
  }

}
