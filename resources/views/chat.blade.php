<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquid Glass Chat - Realtime</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            /* Nền Gradient chuyển màu RẤT CHẬM (45s) để tạo sự sang trọng */
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 45s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Container Kính Lỏng (Liquid Glass) */
        .chat-container {
            width: 420px;
            height: 85vh;
            max-height: 800px;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px) saturate(250%);
            -webkit-backdrop-filter: blur(40px) saturate(250%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.4);
            border-left: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2),
            inset 0 0 20px rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 10;
        }

        .chat-header {
            padding: 25px 20px 15px;
            text-align: center;
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
            background: rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            scroll-behavior: smooth;
            position: relative;
        }

        .chat-box::-webkit-scrollbar { display: none; }

        .message {
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
            position: relative;
            z-index: 2;
        }

        .message.me { align-items: flex-end; }
        .message.other { align-items: flex-start; }

        .sender-name {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 6px;
            margin-left: 12px;
            font-weight: 500;
        }

        .bubble {
            padding: 12px 18px;
            border-radius: 22px;
            max-width: 78%;
            font-size: 0.95rem;
            line-height: 1.5;
            word-wrap: break-word;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .me .bubble {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px) saturate(150%);
            -webkit-backdrop-filter: blur(20px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom-right-radius: 6px;
        }

        .other .bubble {
            background: rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(20px) saturate(150%);
            -webkit-backdrop-filter: blur(20px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom-left-radius: 6px;
        }

        .chat-input {
            display: flex;
            padding: 15px 20px 25px;
            background: rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            gap: 12px;
            align-items: center;
            position: relative;
            z-index: 20;
        }

        .chat-input input {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 16px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            outline: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .chat-input input::placeholder { color: rgba(255, 255, 255, 0.6); }
        .chat-input input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }

        #name { width: 30%; }
        #msg { flex: 1; }

        .chat-input button {
            border: none;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .chat-input button:hover {
            transform: scale(1.08);
            background: rgba(255, 255, 255, 0.35);
        }

        .chat-input button svg { width: 18px; height: 18px; fill: white; transform: translateX(1px); }

        .floating-emoji {
            position: absolute;
            bottom: 80px;
            font-size: 24px;
            pointer-events: none;
            animation: floatUp 2s ease-out forwards;
            z-index: 5;
            opacity: 0;
        }

        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.5); opacity: 1; }
            50% { opacity: 1; }
            100% { transform: translateY(-150px) scale(1.2); opacity: 0; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">Liquid Chat</div>

    <div id="chat-box" class="chat-box"></div>

    <div class="chat-input">
        <input id="name" placeholder="Tên bạn..." />
        <input id="msg" placeholder="Nhập tin nhắn..." autocomplete="off" />
        <button id="send-btn">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        let currentUser = '';
        const nameInput = document.getElementById('name');
        const msgInput = document.getElementById('msg');
        const chatBox = document.getElementById('chat-box');

        // Cập nhật liên tục Tên hiện tại khi người dùng gõ
        nameInput.addEventListener('input', function() {
            currentUser = this.value.trim();
        });

        document.getElementById('send-btn').addEventListener('click', sendMessage);
        msgInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        // ==============================================
        // LOGIC LẮNG NGHE REALTIME (LARAVEL ECHO)
        // ==============================================
        if(window.Echo) {
            window.Echo.channel('chat')
                .listen('.message.sent', (e) => {
                    // CHỈ hiển thị tin nhắn Echo nhận được NẾU tên người gửi KHÁC tên của bạn
                    // (Để tránh việc bạn vừa gửi xong máy tính lại tự in thêm 1 dòng nữa)
                    if (e.message.user_name !== currentUser) {
                        addMessage(e.message.user_name, e.message.content, false);
                        triggerEmojis();
                    }
                });
        }

        function sendMessage() {
            const name = nameInput.value.trim();
            const content = msgInput.value.trim();

            if (!name || !content) {
                alert("Vui lòng nhập cả Tên và Tin nhắn!");
                return;
            }

            currentUser = name;
            msgInput.value = '';

            // GỌI API BACKEND
            if(window.axios) {
                axios.post('/send', {
                    user_name: name,
                    content: content
                }).then(res => {
                    // Chỉ khi Server báo gửi thành công, ta mới in tin nhắn của chính mình ra
                    addMessage(res.data.user_name, res.data.content, true);
                    triggerEmojis();
                }).catch(err => {
                    console.error("Lỗi gửi tin:", err);
                });
            } else {
                addMessage(name, content, true);
                triggerEmojis();
            }
        }

        // Cập nhật lại hàm addMessage nhận thêm biến isMe để render chính xác
        function addMessage(name, msg, isMe) {
            const div = document.createElement('div');
            div.classList.add('message');
            div.classList.add(isMe ? 'me' : 'other');

            div.innerHTML = `
                ${!isMe ? `<span class="sender-name">${name}</span>` : ''}
                <div class="bubble">${msg}</div>
            `;

            chatBox.appendChild(div);
            chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
        }

        function triggerEmojis() {
            const emojis = ['✨', '🔥', '❤️', '👍', '😂', '🎉'];
            const count = Math.floor(Math.random() * 3) + 3;

            for(let i = 0; i < count; i++) {
                setTimeout(() => {
                    const el = document.createElement('div');
                    el.classList.add('floating-emoji');
                    el.innerText = emojis[Math.floor(Math.random() * emojis.length)];
                    const randomLeft = Math.floor(Math.random() * 80) + 10;
                    el.style.left = `${randomLeft}%`;
                    chatBox.appendChild(el);
                    setTimeout(() => el.remove(), 2000);
                }, i * 150);
            }
        }
    });
</script>

</body>
</html>
