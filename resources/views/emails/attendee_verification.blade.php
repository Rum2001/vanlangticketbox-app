<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Xác nhận Đăng ký Sự kiện</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: red;
        }

        p {
            color: #555555;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-height: 50px;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
            position: relative;
        }

        .qr-code img {
            display: block;
            margin: 0 auto;
            border: 4px solid #f9f9f9;
            border-radius: 4px;
        }

        .qr-code:after {
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px dashed #999999;
            border-radius: 4px;
            pointer-events: none;
        }

        .event-details {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
        }

        .event-details h3 {
            margin-top: 0;
        }

        .thank-you {
            margin-top: 20px;
            font-weight: bold;
        }

        .team {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <!-- Add your logo here -->
            <!-- <img src="your-logo.png" alt="Logo"> -->
        </div>
        <h2>Xác nhận đăng ký sự kiện</h2>
        <p>Kính gửi {{ $email }},</p>
        <p>Chúc mừng bạn đã đăng ký tham gia thành công sự kiện "{{ $event_name }}" bằng cách sử dụng mã xác nhận dưới đây:</p>
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
        </div>
        <div class="event-details">
            <h3>Thông tin sự kiện:</h3>
            <p>Tên sự kiện: {{ $event_name }}</p>
        </div>
        <p class="thank-you">Cảm ơn bạn đã đăng ký tham gia sự kiện. Chúng tôi rất mong được đón tiếp bạn!</p>
        <p class="team">Trân trọng,</p>
        <p class="team">Đội ngũ tổ chức sự kiện</p>
    </div>
</body>
</html>
