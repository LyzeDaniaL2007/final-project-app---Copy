<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyewaan;
use Illuminate\Support\Facades\Validator;
use Exception;

class PenyewaanController extends Controller
{
    public function index()
    {
        try {
            $penyewaan = Penyewaan::with('pelanggan')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved penyewaan data.',
                'data' => $penyewaan,
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
                'penyewaan_pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
                'penyewaan_tglkembali' => 'required|date',
                'penyewaan_stspembayaran' => 'sometimes|in:Lunas,Belum Dibayar,DP',
                'penyewaan_sttskembali' => 'sometimes|in:Sudah Kembali,Belum Kembali',
                'penyewaan_totalharga' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create penyewaan data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $penyewaan = Penyewaan::create($validator->validated());
            

            return response()->json([
                'success' => true,
                'message' => 'Successfully created penyewaan data.',
                'data' => $penyewaan,
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
            $penyewaan = Penyewaan::with('pelanggan')->find($id);

            if (!$penyewaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penyewaan not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved penyewaan data.',
                'data' => $penyewaan,
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
                'penyewaan_tglkembali' => 'sometimes|date',
                'penyewaan_stspembayaran' => 'sometimes|in:Lunas,Belum Dibayar,DP',
                'penyewaan_sttskembali' => 'sometimes|in:Sudah Kembali,Belum Kembali',
                'penyewaan_totalharga' => 'sometimes|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update penyewaan data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $penyewaan = Penyewaan::find($id);
            if ($penyewaan) {
                $penyewaan->update($validator->validated());
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated penyewaan data.',
                'data' => $penyewaan,
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
            $penyewaan = Penyewaan::find($id);
            if ($penyewaan) {
                $penyewaan->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted penyewaan data.',
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
