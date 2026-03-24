<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trung tâm thông báo Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background-color: #f4f7f6; padding-top: 50px; }
        .box-noti { max-width: 500px; margin: auto; background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
        .header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .log-list { list-style: none; padding: 0; margin: 0; max-height: 400px; overflow-y: auto; }
        .log-item { padding: 15px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; }
        .log-item img { width: 40px; height: 40px; border-radius: 50%; margin-right: 15px; }
        .log-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

<div class="box-noti">
    <div class="header">
        <h5 class="m-0">🔔 Thông báo hệ thống</h5>
        <span class="badge bg-danger rounded-pill fs-6" id="bell-count">0</span>
    </div>
    <ul class="log-list" id="log-list">
        <li class="text-center text-muted p-4" id="empty-msg">Chưa có thông báo nào...</li>
    </ul>
</div>

<script type="module">
    // Lắng nghe đúng kênh và sự kiện
    Echo.channel('kenh-thong-bao')
        .listen('.ThongBaoMoi', (event) => {
            // In ra F12 để kiểm tra
            console.log("Dữ liệu bóc được:", event);

            const data = event.data;

            // 1. Tăng số đếm ở cái chuông
            const bell = document.getElementById('bell-count');
            bell.innerText = parseInt(bell.innerText) + 1;

            // 2. Ẩn dòng "Chưa có thông báo"
            document.getElementById('empty-msg').style.display = 'none';

            // 3. Hiện Toast góc phải
            Swal.fire({
                title: data.username,
                text: data.message,
                imageUrl: data.avatar,
                imageWidth: 50,
                imageHeight: 50,
                timer: 4000,
                position: 'top-end',
                toast: true,
                showConfirmButton: false,
                timerProgressBar: true
            });

            // 4. Thêm tin nhắn mới lên đầu danh sách
            const newLog = `
                    <li class="log-item">
                        <img src="${data.avatar}" alt="Avatar">
                        <div>
                            <strong>${data.username}</strong>
                            <p class="m-0 text-dark">${data.message}</p>
                            <small class="text-muted">${data.time}</small>
                        </div>
                    </li>`;
            document.getElementById('log-list').insertAdjacentHTML('afterbegin', newLog);
        });
</script>
</body>
</html>
