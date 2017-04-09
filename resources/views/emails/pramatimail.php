<?php

namespace App\Http\Controllers;

use Illuminate\Mail;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Send an e-mail reminder to the user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function sendEmailReminder(Request $request, $id)
    {
        $user = User::findOrFail($id);

        Mail::send('emails.mail', ['user' => $user], function ($m) use ($user) {
            $m->from('info@pramaticare.com');

            $m->to($user->email, $user->name)->subject('Enquiry Details');
        });
    }
}