<?php

namespace App\Http\Controllers\Api\Auth;

use App\Dictionaries\ActionStatusDictionary;
use App\Http\Controllers\Controller;
use function event;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * @group PasswordReset
 */
class ResetPasswordController extends Controller
{
    /**
     * Send password reset link
     *
     * @unauthenticated
     *
     * @param  Request  $request
     * @return array
     */
    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT && $status === 'passwords.sent') {
            $message = 'Email sent';
            $status = ActionStatusDictionary::STATUS_SUCCESS;
        } else {
            $message = 'Email error';
            $status = ActionStatusDictionary::STATUS_ERROR;
        }

        return ['status' => $status, 'message' => $message];
    }

    /**
     * Reset user password
     *
     * @unauthenticated
     *
     * @param  Request  $request
     * @return array
     */
    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required|max:256',
            'email' => 'email|required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $message = 'Password change';
            $status = ActionStatusDictionary::STATUS_SUCCESS;
        } else {
            $message = 'Error';
            $status = ActionStatusDictionary::STATUS_ERROR;
        }

        return ['status' => $status, 'message' => $message];
    }
}
