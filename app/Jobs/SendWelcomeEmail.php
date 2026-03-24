<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // Có thể truyền biến User vào đây nếu muốn
    }

    /**
     * Execute the job.
     * Đây là nơi chứa logic xử lý nặng
     */
    public function handle(): void
    {
        // Giả lập gửi mail tốn thời gian
        sleep(5);

        // Ghi vào file storage/logs/laravel.log để kiểm chứng
        Log::info("--- [QUEUE] Đã gửi xong Email chào mừng lúc: " . now());
    }
}
