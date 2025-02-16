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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
                'errors' => $validator->errors(),
            ], 400);
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Langsung login setelah register
        $token = JWTAuth::fromUser($user);
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully registered and logged in.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60 * 60,
            'user' => $user,
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
                'errors' => $validator->errors(),
            ], 400);
        }
    
        $credentials = $request->only('email', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to login. Incorrect email or password.',
            ], 401);
        }
    
        $user = auth('api')->user();
    
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in.',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60 * 60,
            'user' => $user,
        ], 200);
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
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Buat token reset password
        $token = Str::random(60);

        // Kirim token ke email
        Mail::raw("Gunakan token berikut untuk mereset password Anda: $token", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password Token');
        });

        // Simpan token di session atau cache (tanpa tabel tambahan)
        Cache::put("reset_password_{$request->email}", $token, now()->addMinutes(30));

        return response()->json(['message' => 'Token reset password telah dikirim ke email Anda']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Periksa token di cache
        $cachedToken = Cache::get("reset_password_{$request->email}");

        if (!$cachedToken || $cachedToken !== $request->token) {
            return response()->json(['error' => 'Token tidak valid atau sudah kedaluwarsa'], 400);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah digunakan
        Cache::forget("reset_password_{$request->email}");

        return response()->json(['message' => 'Password berhasil direset']);
    }
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        try {
            $newToken = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully.',
                'token' => $newToken,
                'user' => $user
            ], 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh token.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function getUsers()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'List of all users.',
            'data' => $users,
        ], 200);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User details.',
            'data' => $user,
        ], 200);
    }
}
