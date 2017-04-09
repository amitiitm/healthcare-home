<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Domain\IUserDomainContract;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Guard;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $token;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */


    public function __construct(Guard $auth, PasswordBroker $passwords, IUserDomainContract $userContract, TokenRepositoryInterface $tokens)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;

        $this->middleware('guest');

        $this->userService = $userContract;

        $this->token = $tokens;
    }

    public function getEmail() {
        return view('auth.password');
    }


    public function getUser($email)
    {

        $user = User::where('email','=',$email)->first();

        return $user;
    }

    public function postEmail(Request $request)
    {
        $messages = [
            'email.required'    => 'Missing e-mail, please correct and try again.',
            'passwords.user'    => "We're sorry. We weren't able to identify you given the information provided."
        ];
        $this->validate($request, ['email' => 'required|email'], $messages);


        /*$response = $this->passwords->sendResetLink($request->only('email'), function($m)
        {
            $m->subject($this->getEmailSubject());
        });*/

        $user = $this->getUser($request->only('email')['email']);

        if($user){
            $token = $this->token->create($user);
            $response = $this->userService->sendResetLink($user,$token);
        }
        /*
         *
         * Sending custom reset link
         *
         */
        if($user){
            return redirect("reset/password/complete")->with('status', trans(PasswordBroker::RESET_LINK_SENT));
        }else{
            return redirect()->back()->withErrors(['email' => "We're sorry. We weren't able to identify you given the information provided."]);
        }
        /*switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case PasswordBroker::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }*/
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        $userByEmail = User::where('email',$request->get('email'))->first();
        if($userByEmail && $userByEmail->is_blocked){
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'auth'=> "Your account has been disabled please contact system administrator for details.",
                ]);
        }

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath())->with('status', trans($response));
            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }


}
