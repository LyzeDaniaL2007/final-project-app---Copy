<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Exception;

class PelangganController extends Controller
{
    public function index()
    {
        try {
            $pelanggan = Cache::remember('pelanggan', 60 * 60 * 24, function () {
                return Pelanggan::with('pelangganData', 'penyewaan')->get();
            });

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pelanggan data.',
                'data' => $pelanggan,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pelanggan_nama' => 'required|string|max:150',
                'pelanggan_alamat' => 'required|string|max:200',
                'pelanggan_notelp' => 'required|string|max:20',
                'pelanggan_email' => 'required|string|email|max:100|unique:pelanggan,pelanggan_email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create pelanggan data. Please check your input.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $pelanggan = Pelanggan::create($validator->validated());
            Cache::put('pelanggan', Pelanggan::all(), 60 * 60 * 24);

            return response()->json([
                'success' => true,
                'message' => 'Successfully created pelanggan data.',
                'data' => $pelanggan,
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pelanggan = Pelanggan::with('pelangganData', 'penyewaan')->find($id);

            if (!$pelanggan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pelanggan data.',
                'data' => $pelanggan,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pelanggan_nama' => 'sometimes|string|max:150',
                'pelanggan_alamat' => 'sometimes|string|max:200',
                'pelanggan_notelp' => 'sometimes|string|max:20',
                'pelanggan_email' => 'sometimes|string|email|max:100|unique:pelanggan,pelanggan_email,' . $id . ',pelanggan_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update pelanggan data. Please check your input.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $pelanggan = Pelanggan::find($id);
            if ($pelanggan) {
                $pelanggan->update($validator->validated());
                Cache::put('pelanggan', Pelanggan::all(), 60 * 60 * 24);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated pelanggan data.',
                'data' => $pelanggan,
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::find($id);
            if ($pelanggan) {
                $pelanggan->delete();
                Cache::put('pelanggan', Pelanggan::all(), 60 * 60 * 24);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted pelanggan data.',
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error in the internal server.',
                'errors' => $error->getMessage(),
            ], 500);
        }
    }
}
