<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function download($filename)
    {
        // Use URL-decoded filename since it was encoded in the frontend
        $decodedFilename = urldecode($filename);
        
        // Define the correct file path in the 'public/chat_files' directory
        $filePath = 'chat_files/' . $decodedFilename;

        // Log the full path for debugging
        \Log::info("Full file path: " . storage_path('app/public/' . $filePath));

        // Check if the file exists in the 'public' disk
        if (Storage::disk('public')->exists($filePath)) {
            // Serve the file for download
            return Storage::disk('public')->download($filePath);
        } else {
            // Log the error if file not found
            \Log::error("File not found: " . $filePath);

            // Return a 404 error if the file is not found
            return abort(404, 'File not found.');
        }
    }
}
