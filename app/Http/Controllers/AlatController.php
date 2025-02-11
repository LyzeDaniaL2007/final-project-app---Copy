<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AlatController extends Controller
{
    public function index()
    {
        try {
            // Verifikasi JWT token
            JWTAuth::parseToken()->authenticate();

            // Gunakan cache selama 5 menit
            $alat = Cache::remember('alat_data', 300, function () {
                return Alat::with('kategori')->get();
            });

            if ($alat->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully get alat data',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully get alat data',
                'data' => $alat,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There is an error in Internal Server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            JWTAuth::parseToken()->authenticate();

            $alat = Alat::with('kategori')->find($id);

            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat not found',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully get alat data',
                'data' => $alat,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There is an error in Internal Server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'required|exists:kategori,kategori_id',
                'alat_nama' => 'required|string',
                'alat_deskripsi' => 'nullable|string',
                'alat_hargaperhari' => 'required|numeric',
                'alat_stok' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create alat data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $alat = Alat::create($validator->validated());

            // Hapus cache setelah data berubah
            Cache::forget('alat_data');

            return response()->json([
                'success' => true,
                'message' => 'Successfully created alat data.',
                'data' => $alat,
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There is an error in Internal Server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            JWTAuth::parseToken()->authenticate();

            $validator = Validator::make($request->all(), [
                'alat_nama' => 'sometimes|string|max:255',
                'alat_hargaperhari' => 'sometimes|integer',
                'alat_stok' => 'sometimes|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update alat data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $alat = Alat::find($id);
            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat not found',
                    'data' => null,
                ], 404);
            }

            $alat->update($validator->validated());

            // Hapus cache setelah data berubah
            Cache::forget('alat_data');

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated alat data.',
                'data' => $alat,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There is an error in Internal Server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            JWTAuth::parseToken()->authenticate();

            $alat = Alat::find($id);
            if (!$alat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat not found',
                    'data' => null,
                ], 404);
            }

            $alat->delete();

            // Hapus cache setelah data berubah
            Cache::forget('alat_data');

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted alat data.',
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There is an error in Internal Server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }
}
