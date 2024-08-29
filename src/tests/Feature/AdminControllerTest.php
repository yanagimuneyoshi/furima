<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_admin_dashboard()
  {
    // 管理者ユーザーを作成しログイン
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    // ダッシュボードページにアクセス
    $response = $this->get(route('admin.index'));

    // ページが正常に表示され、期待するビューがレンダリングされるか確認
    $response->assertStatus(200);
    $response->assertViewIs('admin.index');
    $response->assertViewHasAll(['users', 'comments']);
  }

  /** @test */
  public function it_allows_admin_to_delete_user()
  {
    // 管理者と一般ユーザーを作成し、管理者でログイン
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($admin);

    // ユーザー削除を試行
    $response = $this->delete(route('admin.users.destroy', $user->id));

    // ユーザーが削除され、リダイレクトされるか確認
    $response->assertRedirect(route('admin.index'));
    $response->assertSessionHas('success', 'ユーザーが削除されました');
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
  }

  /** @test */
  // public function it_allows_admin_to_delete_comment()
  // {
  //   // 管理者ユーザーとコメントを作成し、管理者でログイン
  //   $admin = User::factory()->create(['role' => 'admin']);
  //   $comment = Comment::factory()->create();
  //   $this->actingAs($admin);

  //   // コメント削除を試行
  //   $response = $this->delete(route('admin.comments.destroy', $comment->id));

  //   // コメントが削除され、リダイレクトされるか確認
  //   $response->assertRedirect(route('admin.index'));
  //   $response->assertSessionHas('success', 'コメントが削除されました');
  //   $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  // }

  // /** @test */
  // public function it_does_not_allow_non_admin_to_access_admin_dashboard()
  // {
  //   // 一般ユーザーを作成しログイン
  //   $user = User::factory()->create(['role' => 'user']);
  //   $this->actingAs($user);

  //   // 管理者ダッシュボードにアクセスを試行
  //   $response = $this->get(route('admin.index'));

  //   // アクセスが拒否されることを確認
  //   $response->assertStatus(403); // 権限がない場合のステータス
  // }

  // /** @test */
  // public function it_does_not_allow_non_admin_to_delete_user()
  // {
  //   // 一般ユーザーと別の一般ユーザーを作成し、一般ユーザーでログイン
  //   $user = User::factory()->create(['role' => 'user']);
  //   $anotherUser = User::factory()->create(['role' => 'user']);
  //   $this->actingAs($user);

  //   // ユーザー削除を試行
  //   $response = $this->delete(route('admin.users.destroy', $anotherUser->id));

  //   // ユーザーが削除されないことを確認
  //   $response->assertStatus(403); // 権限がない場合のステータス
  //   $this->assertDatabaseHas('users', ['id' => $anotherUser->id]);
  // }

  // /** @test */
  // public function it_does_not_allow_non_admin_to_delete_comment()
  // {
  //   // 一般ユーザーとコメントを作成し、一般ユーザーでログイン
  //   $user = User::factory()->create(['role' => 'user']);
  //   $comment = Comment::factory()->create();
  //   $this->actingAs($user);

  //   // コメント削除を試行
  //   $response = $this->delete(route('admin.comments.destroy', $comment->id));

  //   // コメントが削除されないことを確認
  //   $response->assertStatus(403); // 権限がない場合のステータス
  //   $this->assertDatabaseHas('comments', ['id' => $comment->id]);
  // }
}
