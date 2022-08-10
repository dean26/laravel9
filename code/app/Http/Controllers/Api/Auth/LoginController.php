<?php

namespace App\Http\Controllers\Api\Auth;

use App\Dictionaries\ActionStatusDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Auth
 */
class LoginController extends Controller
{
    /**
     * Login method based on tokens
     *
     * @unauthenticated
     *
     * @param  mixed  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['status' => ActionStatusDictionary::STATUS_ERROR, 'message' => 'Invalid login details']);
        }

        $user = User::query()->where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('getbuilt_token')->plainTextToken;

        return response()->json([
            'status' => ActionStatusDictionary::STATUS_SUCCESS,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Login method based on spa cookie
     *
     * @unauthenticated
     *
     * @param  mixed  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_spa(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['status' => ActionStatusDictionary::STATUS_ERROR, 'message' => 'Invalid login details']);
        }

        return response()->json([
            'status' => ActionStatusDictionary::STATUS_SUCCESS,
        ]);
    }

    /**
     * Show information about logged user
     *
     * @group Users
     * @authenticated
     *
     * @param  mixed  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Logout user from all devices based on tokens
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['status' => ActionStatusDictionary::STATUS_SUCCESS]);
    }

    /**
     * Logout user based on cookie spa
     *
     * @authenticated
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout_spa(Request $request)
    {
        Auth::guard('web')->logout();

        return response()->json(['status' => ActionStatusDictionary::STATUS_SUCCESS]);
    }

    /**
     * Logout user from current request
     *
     * @authenticated
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout_current(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => ActionStatusDictionary::STATUS_SUCCESS]);
    }
}
