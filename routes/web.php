<?php

use App\Events\PhatThongBao;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('thongbao');
//});
Route::get('/upload', [ImageController::class, 'index']);
Route::post('/upload', [ImageController::class, 'store'])->name('image.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-queue', function () {

    // Lệnh này đẩy công việc vào Redis và đi tiếp luôn, không đợi sleep(5)
    SendWelcomeEmail::dispatch();

    return "<h1>Thành công!</h1>
            <p>Job đã được đẩy vào Redis. Hãy nhìn vào cửa sổ Terminal chạy queue:work để thấy nó xử lý.</p>
            <p>Mở file <b>storage/logs/laravel.log</b> để xem kết quả sau 5 giây.</p>";
});

Route::get('/push/{user}/{msg}', function ($user, $msg) {
    // Xóa dấu gạch dưới thành dấu cách cho đẹp
    $message = str_replace('_', ' ', $msg);

    // Phát lệnh!
    broadcast(new PhatThongBao($user, $message));

    return response()->json([
        'trang_thai' => 'Thành công',
        'chi_tiet' => "Đã gửi tin '{$message}' từ '{$user}'"
    ]);
});

Route::get('/', [ChatController::class, 'index']);
Route::post('/send', [ChatController::class, 'send']);
require __DIR__.'/auth.php';
