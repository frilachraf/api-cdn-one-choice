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
            'file' => 'required|file|max:20480', // 20MB limit
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads'); // Store in 'storage/app/uploads'
        $type = $file->getMimeType();
        $size = $file->getSize();

        // Save file details to the database
        $fileRecord = File::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'size' => $size,
        ]);

        return response()->json([
            'success' => true,
            'data' => $fileRecord,
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
