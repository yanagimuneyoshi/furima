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


    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

    $this->user = User::factory()->create();
    $this->item = Item::factory()->create();
  }

  /** @test */
  public function it_displays_comments_for_an_item()
  {

    $comment = Comment::factory()->create(['item_id' => $this->item->id]);

    $response = $this->get(route('comments.show', $this->item->id));

    $response->assertStatus(200);
    $response->assertSee($comment->content);
  }

  /** @test */
  public function it_stores_a_new_comment()
  {
    Session::start();

    $response = $this->actingAs($this->user)->post(route('comments.store', $this->item->id), [
      '_token' => csrf_token(),
      'content' => 'テストコメント'
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(route('comments.show', $this->item->id));
    $this->assertDatabaseHas('comments', [
      'item_id' => $this->item->id,
      'user_id' => $this->user->id,
      'content' => 'テストコメント'
    ]);
  }


  /** @test */
  public function it_deletes_a_comment()
  {
    $comment = Comment::factory()->create([
      'item_id' => $this->item->id,
      'user_id' => $this->user->id,
    ]);


    Session::start();

    $response = $this->actingAs($this->user)->delete(route('comments.destroy', $comment->id), [
      '_token' => csrf_token(),
    ]);

    $response->assertStatus(302);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
  }

}
