<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use Illuminate\Http\Request;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'size_ml' => 'required|integer|min:0',
            'category' => 'required|string|max:100',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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
        $perfume = Perfume::find($id);

        if (!$perfume) {
            return response()->json([
                'message' => 'Perfume not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'size_ml' => 'sometimes|required|integer|min:0',
            'category' => 'sometimes|required|string|max:100',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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