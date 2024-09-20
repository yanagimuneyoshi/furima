<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_recommendations_tab_items_when_no_auth_and_tab_is_recommendations()
    {
        $item1 = Item::factory()->create(['title' => 'Test Item 1']);
        $item2 = Item::factory()->create(['title' => 'Test Item 2']);

        $response = $this->get(route('item.search', ['query' => 'Test', 'tab' => 'recommendations']));

        $response->assertStatus(200);
        $response->assertViewHas('items', function ($items) use ($item1, $item2) {
            return $items->contains($item1) && $items->contains($item2);
        });
        $response->assertViewHas('activeTab', 'recommendations');
    }

    /** @test */
    public function it_returns_mylist_items_when_authenticated_and_tab_is_mylist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $favoriteItem = Item::factory()->create(['title' => 'Favorite Item']);
        $user->favorites()->attach($favoriteItem);

        $response = $this->get(route('item.search', ['query' => 'Favorite', 'tab' => 'mylist']));

        $response->assertStatus(200);
        $response->assertViewHas('favorites', function ($favorites) use ($favoriteItem) {
            return $favorites->contains($favoriteItem);
        });
        $response->assertViewHas('activeTab', 'mylist');
    }

    /** @test */
    public function it_redirects_to_login_when_not_authenticated_and_mylist_tab_is_requested()
    {
        $response = $this->get(route('item.search', ['tab' => 'mylist']));

        $response->assertRedirect(route('login'));
    }
}
