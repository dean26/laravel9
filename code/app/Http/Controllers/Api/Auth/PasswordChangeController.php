<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordChangeRequest;
use Illuminate\Support\Facades\Hash;

/**
 * @group Users
 */
class PasswordChangeController extends Controller
{
    /**
     * Change password for logged user
     *
     * @authenticated
     * @bodyParam password string required
     * @bodyParam password_confirmed string required
     */
    public function update(PasswordChangeRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user->update($data);

        return response()->json([$user]);
    }
}
