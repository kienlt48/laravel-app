<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class ImageController extends Controller
{
    // Hiển thị form upload
    public function index()
    {
        // Lấy danh sách ảnh đã up để hiển thị
        $images = Image::latest()->get();
        return view('upload', compact('images'));
    }

    // Xử lý upload
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();

        // 1. Upload lên S3 vào thư mục 'uploads'
        $path = $file->storeAs('uploads', $fileName, 's3');

        // 2. Lấy URL công khai của file từ S3
        $url = Storage::disk('s3')->url($path);

        // 3. Lưu thông tin vào SQLite
        Image::create([
            'name' => $fileName,
            'path' => $path,
            'url'  => $url,
        ]);

        return back()->with('success', 'Upload ảnh lên S3 và lưu DB thành công!');
    }
}
