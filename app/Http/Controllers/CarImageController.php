<?php

namespace App\Http\Controllers;

use App\Models\CarImage;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarImageController extends Controller
{
    //
    public function uploadCarImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validate each file
        ]);

        $uploadedFiles = $request->file('images');
        $filePaths = [];

        foreach ($uploadedFiles as $file) {
            $path = $file->store('images','public');
            $type = $file->getMimeType();
            $size = $file->getSize();

            $fileRecord = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $type,
                'size' => $size,
                'car_id' => $request->input('car_id'),
                'agency_id' => $request->input('agency_id'),
        ]);
        }

        return response()->json([
            'resuets' => $request->file('images')
        ]);
        return response()->json([
            'message' => 'Files uploaded successfully!',
            'paths' => $uploadedFiles, // Array of file paths
            'urls' => array_map(fn($path) => asset('storage/' . $path), $filePaths),
        ]);
    }


}
