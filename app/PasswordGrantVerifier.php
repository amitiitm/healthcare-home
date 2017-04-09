<?php
/**
 * Created by PhpStorm.
 * User: mohitgupta
 * Date: 23/06/16
 * Time: 11:51
 */
namespace App;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier
{
    public function verify($username, $password)
    {
        // if email



        if(strpos($username,'@')>=0 && false){
            $credentials = [
                'email'    => $username,
                'password' => $password,
            ];
            if (Auth::once($credentials)) {
                return Auth::user()->id;
            }
            return false;
        }else{
            $userWithPhone = User::where('phone','=',$username)->first();

            if($userWithPhone && $userWithPhone->login_otp==$password){
                Auth::loginUsingId($userWithPhone->id);
                return Auth::user()->id;
            }
            return false;
        }
    }
}