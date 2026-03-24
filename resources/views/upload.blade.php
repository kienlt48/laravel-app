<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Ảnh lên AWS S3</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .alert { padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 20px; }
        .gallery { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
        .gallery img { width: 200px; height: 150px; object-fit: cover; border: 1px solid #ccc; }
    </style>
</head>
<body>

<h2>Upload Ảnh (S3 + SQLite)</h2>

@if (session('success'))
    <div class="alert">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <button type="submit">Tải lên</button>
</form>

<hr>

<h3>Thư viện ảnh đã tải lên</h3>
<div class="gallery">
    @foreach ($images as $img)
        <div>
            <img src="{{ $img->url }}" alt="{{ $img->name }}">
            <p style="font-size: 12px; text-align: center;">{{ $img->name }}</p>
        </div>
    @endforeach
</div>

</body>
</html>
