<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class ImageController extends Controller
{
    public function uploadToCloudinary(Request $request)
    {
        $uploadedFile = $request->file('image');
        $cloudinary = new Cloudinary();
        
        $result = $cloudinary->uploadApi()->upload($uploadedFile->getRealPath(), [
            'folder' => 'your-folder-name', // Optional: specify a folder in Cloudinary
        ]);

        return response()->json([
            'url' => $result['secure_url'], // The URL of the uploaded image
        ]);
    }
}
