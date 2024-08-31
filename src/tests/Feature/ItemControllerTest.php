<?php


namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  public function it_displays_all_items_when_no_search_query_is_provided()
  {
    // Arrange: アイテムを3つ作成
    Item::factory()->count(3)->create();

    // Act: リクエストを実行
    $response = $this->get(route('home'));

    // Assert: ステータスコードと表示内容を確認
    $response->assertStatus(200);
    $response->assertViewHas('items');
    $this->assertCount(3, $response->viewData('items'));
  }

  /** @test */
  public function it_displays_filtered_items_based_on_search_query()
  {
    // Arrange: 特定のタイトルを持つアイテムを作成
    Item::factory()->create(['title' => 'Unique Item']);
    Item::factory()->create(['title' => 'Another Item']);

    // Act: 検索クエリを含むリクエストを実行
    $response = $this->get(route('home', ['query' => 'Unique']));

    // Assert: ステータスコードと表示内容を確認
    $response->assertStatus(200);
    $response->assertViewHas('items');
    $this->assertCount(1, $response->viewData('items'));
    $this->assertEquals('Unique Item', $response->viewData('items')->first()->title);
  }

  /** @test */
  public function it_displays_mylist_when_user_is_authenticated()
  {
    // Arrange: ユーザーとお気に入りアイテムを作成
    $user = User::factory()->create();
    $this->actingAs($user);
    $item = Item::factory()->create();
    $user->favorites()->attach($item->id);

    // Act: リクエストを実行
    $response = $this->get(route('home', ['tab' => 'mylist']));

    // Assert: ステータスコードと表示内容を確認
    $response->assertStatus(200);
    $response->assertViewHas('favorites');
    $this->assertCount(1, $response->viewData('favorites'));
    $this->assertEquals($item->id, $response->viewData('favorites')->first()->id);
  }

  /** @test */
  /** @test */
  public function it_redirects_to_login_when_trying_to_access_mylist_while_not_authenticated()
  {
    // Act: 認証されていない状態で「mylist.check」にアクセス
    $response = $this->get(route('home', ['tab' => 'mylist']));

    // Assert: ログインページにリダイレクトされることを確認
    $response->assertRedirect(route('login'));
  }

}
