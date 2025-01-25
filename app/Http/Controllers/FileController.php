<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function upload(Request $request)
{
    $request->validate([
        'files' => 'required|array', // Ensure 'files' is an array
        'files.*' => 'file|max:20480', // Validate each file in the array
    ]);
 
    $uploadedFiles = $request->file('files');
    $fileRecords = [];

    foreach ($uploadedFiles as $file) {
        $path = $file->store('images', 'public'); // Store each file in 'storage/app/public/images'
        $type = $file->getMimeType();
        $size = $file->getSize();
        $url = Storage::url($path);

        // Save file details to the database
        $fileRecord = File::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'size' => $size,
            'url' => $url
        ]);

        $fileRecords[] = [
            'success' => true,
            'data' => $fileRecord,
            'url' => asset($url)
        ];
    }

    return response()->json([
        'success' => true,
        'files' => $fileRecords
    ], 201);
}


    // List All Files
    public function index()
    {
        $files = File::all();

        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }

    // Download a File
    public function download($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::download($file->path, $file->name);
    }

    // Delete a File
    public function destroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        Storage::delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

}
