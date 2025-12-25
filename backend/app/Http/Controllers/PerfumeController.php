<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerfumeController extends Controller
{
    /**
     * Display a listing of perfumes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $perfumes = Perfume::all();
        
        return response()->json([
            'message' => 'Success get all perfumes',
            'data' => $perfumes
        ], 200);
    }

    /**
     * Display the specified perfume.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $perfume = Perfume::find($id);

        if (!$perfume) {
            return response()->json([
                'message' => 'Perfume not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Success get perfume detail',
            'data' => $perfume
        ], 200);
    }

    /**
     * Store a newly created perfume in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check if user is admin
        $user = Auth::guard('api')->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        // For update, we expect all fields to be sent
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size_ml' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $perfumeData = $request->only([
            'name', 
            'brand', 
            'description', 
            'price', 
            'stock', 
            'size_ml', 
            'category'
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perfumes', $filename, 'public');
            $perfumeData['file_path'] = $path;
        }

        $perfume = Perfume::create($perfumeData);

        return response()->json([
            'message' => 'Perfume created successfully',
            'data' => $perfume
        ], 201);
    }

    /**
     * Update the specified perfume in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        $user = Auth::guard('api')->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $perfume = Perfume::find($id);

        if (!$perfume) {
            return response()->json([
                'message' => 'Perfume not found'
            ], 404);
        }

        // Get all request data
        $allData = $request->all();
        
        // Debug: Log received data (check storage/logs/laravel.log)
        \Log::info('Update request - All data:', $allData);
        \Log::info('Update request - Input name:', ['name' => $request->input('name')]);
        \Log::info('Update request - Method:', ['method' => $request->method()]);
        \Log::info('Update request - Content-Type:', ['content-type' => $request->header('Content-Type')]);

        $validator = Validator::make($allData, [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size_ml' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'debug' => [
                    'received_data' => $allData,
                    'request_all' => $request->all(),
                ]
            ], 422);
        }

        $perfumeData = $request->only([
            'name', 
            'brand', 
            'description', 
            'price', 
            'stock', 
            'size_ml', 
            'category'
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($perfume->file_path && Storage::disk('public')->exists($perfume->file_path)) {
                Storage::disk('public')->delete($perfume->file_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('perfumes', $filename, 'public');
            $perfumeData['file_path'] = $path;
        }

        $perfume->update($perfumeData);
        $perfume->refresh(); // Refresh to get updated data

        return response()->json([
            'message' => 'Perfume updated successfully',
            'data' => $perfume
        ], 200);
    }

    /**
     * Remove the specified perfume from storage (soft delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Check if user is admin
        $user = Auth::guard('api')->user();
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $perfume = Perfume::find($id);

        if (!$perfume) {
            return response()->json([
                'message' => 'Perfume not found'
            ], 404);
        }

        // Soft delete (tidak menghapus file)
        $perfume->delete();

        return response()->json([
            'message' => 'Perfume deleted successfully'
        ], 200);
    }
}