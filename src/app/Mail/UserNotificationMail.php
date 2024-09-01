<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @param array $details
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        \Log::info('UserNotificationMail buildメソッドが呼び出されました', $this->details); // デバッグ用ログ

        return $this->subject($this->details['title'] ?? '重要なお知らせ')
            ->view('emails.user_notification')
            ->with('details', $this->details);
    }
}

