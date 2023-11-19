<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: red;
        }

        .content {
            text-align: center;
            margin-top: 20px;
        }

        .content p {
            font-size: 18px;
            color: #333;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Bildirim Mesajı</h1>
    </div>
    <div class="content">
        <p>Değerli Kullanıcımız ,</p>
        <p>{!! $notificationMessage !!}</p>
    </div>
    <div class="footer">
        <p>Bildirim Gönderim Tarihi : {{ date('d-m-Y H:i') }}</p>
    </div>
</div>
</body>
</html>
