<?php
/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 23/06/16
 * Time: 11:51
 */
namespace App\Models\Grant\Verifier;

use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }
}