<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Mail;

class AdminMailController extends Controller
{
    public function showMailForm()
    {
        return view('admin.mail_form');
    }


    public function sendMail(Request $request)
    {
        $details = [
            'title' => $request->input('title'),
            'body' => $request->input('body')
        ];


        Mail::to($request->input('email'))->send(new UserNotificationMail($details));


        return redirect('/admin')->with('success', 'メールが送信されました');
    }

}
