<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\PenyewaanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class PenyewaanDetailController extends Controller
{
    public function index()
    {
        try {
            $penyewaanDetail = Cache::remember('penyewaan_detail_all', 60, function () {
                return PenyewaanDetail::with(['penyewaan', 'alat'])->get();
            });

            $message = $penyewaanDetail->isEmpty() ? 'Data penyewaan detail is empty' : 'Successfully retrieved penyewaan detail data';
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $penyewaanDetail->isEmpty() ? null : $penyewaanDetail,
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error in the internal server',
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
                'penyewaan_detail_penyewaan_id' => 'required|exists:penyewaan,penyewaan_id',
                'penyewaan_detail_alat_id' => 'required|exists:alat,alat_id',
                'penyewaan_detail_jumlah' => 'required|integer|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 400);
            }
    
            $subHarga = Alat::find($request->penyewaan_detail_alat_id)->alat_hargaperhari * $request->penyewaan_detail_jumlah;
    
            PenyewaanDetail::create([
                'penyewaan_detail_penyewaan_id' => $request->penyewaan_detail_penyewaan_id,
                'penyewaan_detail_alat_id' => $request->penyewaan_detail_alat_id,
                'penyewaan_detail_jumlah' => $request->penyewaan_detail_jumlah,
                'penyewaan_detail_subharga' => $subHarga,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Successfully created penyewaan detail',
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $penyewaanDetail = PenyewaanDetail::with(['penyewaan', 'alat'])->find($id);

            if (!$penyewaanDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penyewaan detail not found',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved penyewaan detail',
                'data' => $penyewaanDetail,
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'penyewaan_detail_jumlah' => 'sometimes|integer|min:1',
                'penyewaan_detail_subharga' => 'sometimes|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update penyewaan detail',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $penyewaanDetail = PenyewaanDetail::find($id);
            if ($penyewaanDetail) {
                $penyewaanDetail->update($validator->validated());
                Cache::forget('penyewaan_detail_all');
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated penyewaan detail',
                'data' => $penyewaanDetail,
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $penyewaanDetail = PenyewaanDetail::find($id);
            if ($penyewaanDetail) {
                $penyewaanDetail->delete();
                Cache::forget('penyewaan_detail_all');
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted penyewaan detail',
            ], 200);

        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error in the internal server',
                'data' => null,
                'errors' => $error->getMessage(),
            ], 500);
        }
    }
}
