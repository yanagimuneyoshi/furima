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

    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson(route('mylist.check'));

    $response->assertStatus(200);
    $response->assertJson(['authenticated' => true]);
  }

  /** @test */
  public function it_returns_json_response_for_guest_user()
  {
    $response = $this->getJson(route('mylist.check'));

    $response->assertStatus(200);
    $response->assertJson(['authenticated' => false]);
  }

}
