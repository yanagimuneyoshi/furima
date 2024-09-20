<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNotificationMail;
use App\Models\User;

class AdminMailControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * メール送信機能のテスト
   *
   * @return void
   */
  public function testSendMail()
  {
    $adminUser = User::factory()->create([
      'role' => 'admin',
    ]);

    $this->actingAs($adminUser);

    $this->withoutMiddleware();

    Mail::fake();

    $requestData = [
      'email' => 'test@example.com',
      'title' => 'Test Title',
      'body' => 'Test Body'
    ];

    $response = $this->post(route('admin.sendMail'), $requestData);



    Mail::assertSent(UserNotificationMail::class, function ($mail) use ($requestData) {

      return $mail->hasTo($requestData['email']) &&
        $mail->details['title'] === $requestData['title'] &&
        $mail->details['body'] === $requestData['body'];
    });

    $response->assertRedirect()
      ->assertSessionHas('success', 'メールが送信されました');
  }

}
