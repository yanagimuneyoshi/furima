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

    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->regularUser = User::factory()->create(['role' => 'user']);
    $this->item = Item::factory()->create();
  }

  /** @test */
  public function admin_can_delete_user()
  {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($this->adminUser)->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $response->assertSessionHas('success', 'ユーザーが削除されました');
  }

  /** @test */
  public function admin_can_delete_comment()
  {
    $comment = Comment::factory()->create();

    $response = $this->actingAs($this->adminUser)->delete(route('admin.comments.destroy', $comment));

    $response->assertRedirect(route('admin.index'));
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    $response->assertSessionHas('success', 'コメントが削除されました');
  }

  /** @test */
  public function non_admin_cannot_delete_user()
  {
    $targetUser = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($this->regularUser)->delete(route('admin.users.destroy', $targetUser));

    $response->assertStatus(403);
  }

}
