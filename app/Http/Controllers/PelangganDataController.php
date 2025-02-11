<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelangganData;
use Illuminate\Support\Facades\Validator;
use Exception;

class PelangganDataController extends Controller
{
    public function index()
    {
        try {
            $pelangganData = PelangganData::with('pelanggan')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pelanggan data.',
                'data' => $pelangganData,
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
                'pelanggan_data_pelanggan_id' => 'required|exists:pelanggan,pelanggan_id',
                'pelanggan_data_jenis' => 'required|in:KTP,SIM',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create pelanggan data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $pelangganData = PelangganData::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Successfully created pelanggan data.',
                'data' => $pelangganData,
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
            $pelangganData = PelangganData::with('pelanggan')->find($id);

            if (!$pelangganData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan data not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved pelanggan data.',
                'data' => $pelangganData,
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
                'pelanggan_data_jenis' => 'sometimes|in:KTP,SIM',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update pelanggan data.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $pelangganData = PelangganData::find($id);
            if ($pelangganData) {
                $pelangganData->update($validator->validated());
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated pelanggan data.',
                'data' => $pelangganData,
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
            $pelangganData = PelangganData::find($id);
            if ($pelangganData) {
                $pelangganData->delete();
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
