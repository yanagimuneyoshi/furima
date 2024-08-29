<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_searches_items_by_title()
    {
        // テスト用のアイテムを作成
        $item1 = Item::factory()->create(['title' => 'Test Item 1']);
        $item2 = Item::factory()->create(['title' => 'Another Test Item']);

        // 検索クエリを使用して検索を実行
        $response = $this->get(route('item.search', ['query' => 'Test']));

        // 検索結果に期待されるアイテムが含まれているかを確認
        $response->assertStatus(200)
            ->assertViewIs('item')
            ->assertViewHas('items', function ($items) use ($item1, $item2) {
                return $items->contains($item1) && $items->contains($item2);
            });
    }

    /** @test */
    public function it_searches_favorites_by_title()
    {
        // テスト用のユーザーとアイテムを作成
        $user = User::factory()->create();
        $item = Item::factory()->create(['title' => 'Favorite Item']);

        // ユーザーのお気に入りにアイテムを追加
        $user->favorites()->attach($item->id);

        // 検索クエリを使用してお気に入りを検索
        $response = $this->actingAs($user)->get(route('item.search', ['query' => 'Favorite', 'tab' => 'mylist']));

        // 検索結果に期待されるアイテムが含まれているかを確認
        $response->assertStatus(200)
            ->assertViewIs('item')
            ->assertViewHas('favorites', function ($favorites) use ($item) {
                return $favorites->contains($item);
            });
    }

    /** @test */
    public function it_searches_items_by_category()
    {
        // テスト用のカテゴリーとアイテムを作成
        $category = Category::factory()->create(['name' => 'Electronics']);
        $item = Item::factory()->create(['title' => 'Item in Category']);
        $item->categories()->attach($category->id);

        // カテゴリーを使って検索を実行
        $response = $this->get(route('item.search', ['query' => 'Electronics']));

        // 検索結果に期待されるアイテムが含まれているかを確認
        $response->assertStatus(200)
            ->assertViewIs('item')
            ->assertViewHas('items', function ($items) use ($item) {
                return $items->contains($item);
            });
    }
}
