<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerAuthCheckTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_returns_json_response_for_authenticated_user()
  {
    // Arrange: 認証済みユーザーを作成
    $user = User::factory()->create();
    $this->actingAs($user);

    // Act: 認証チェックリクエストを実行
    $response = $this->getJson(route('mylist.check'));

    // Assert: レスポンス内容を確認
    $response->assertStatus(200);
    $response->assertJson(['authenticated' => true]);
  }

  /** @test */
  public function it_returns_json_response_for_guest_user()
  {
    // Act: 認証されていない状態でリクエストを実行
    $response = $this->getJson(route('mylist.check'));

    // Assert: レスポンス内容を確認
    $response->assertStatus(200);
    $response->assertJson(['authenticated' => false]);
  }

}
