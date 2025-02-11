<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class KategoriController extends Controller
{
    // Get all kategori data
    public function index(Request $request)
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            $kategori = Kategori::with('alat')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved kategori data.',
                'data' => $kategori,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or internal server error.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    // Get kategori data by ID
    public function show(Request $request, $id)
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            $kategori = Kategori::with('alat')->find($id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved kategori data.',
                'data' => $kategori,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or internal server error.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    // Create new kategori
    public function store(Request $request)
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'required|string|max:255|unique:kategori,kategori_nama',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create kategori data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $kategori = Kategori::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully created kategori data.',
                'data' => $kategori,
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or internal server error.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    // Update kategori data by ID
    public function update(Request $request, $id)
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'sometimes|string|max:255|unique:kategori,kategori_nama,' . $id . ',kategori_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update kategori data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $kategori = Kategori::find($id);
            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori not found.',
                ], 404);
            }

            $kategori->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated kategori data.',
                'data' => $kategori,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or internal server error.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    // Delete kategori data by ID
    public function destroy(Request $request, $id)
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            $kategori = Kategori::find($id);
            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori not found.',
                ], 404);
            }

            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted kategori data.',
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or internal server error.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }
}
