<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register. Please check your input data.',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered.',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Please check your input data.',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

        $credentials = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Incorrect email or password.',
                'data' => null,
            ], 401);
        }

        return $this->createTokenResponse($token);
    }

    private function createTokenResponse($token)
    {
        $expiresInMinutes = 60; // Token expires in 60 minutes

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresInMinutes * 60, // Convert to seconds
            'user' => auth('api')->user(),
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset email.',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent successfully.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send password reset link.',
            'errors' => ['email' => __($status)],
        ], 400);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password.',
                'data' => null,
                'errors' => $validator->errors(),
            ], 400);
        }

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
            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to reset password.',
            'errors' => ['email' => __($status)],
        ], 400);
    }
}
