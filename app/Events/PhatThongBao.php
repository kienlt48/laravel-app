<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Dùng Now để test ngay lập tức
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PhatThongBao implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // BẮT BUỘC public để Laravel tự lấy biến này gửi xuống Javascript
    public array $data;

    public function __construct(string $user, string $message)
    {
        // Gói toàn bộ dữ liệu vào mảng $data
        $this->data = [
            'username' => $user,
            'message'  => $message,
            'time'     => now()->format('H:i:s'),
            'avatar'   => 'https://ui-avatars.com/api/?name=' . urlencode($user) . '&background=random',
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('kenh-thong-bao'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ThongBaoMoi';
    }
}
