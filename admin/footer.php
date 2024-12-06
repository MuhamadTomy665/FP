<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .footer {
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .footer-content p {
            margin: 0;
            font-size: 14px;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons a img {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover img {
            transform: scale(1.2);
        }
    </style>
</head>
<body>
    <div class="footer">
        <div class="footer-content">
            <p>© 2024 Admin Panel. All rights reserved.</p>
            <div class="social-icons">
                <a href="#"><img src="icons/facebook.svg" alt="Facebook"></a>
                <a href="#"><img src="icons/twitter.svg" alt="Twitter"></a>
                <a href="#"><img src="icons/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="icons/linkedin.svg" alt="LinkedIn"></a>
            </div>
        </div>
    </div>
</body>
</html>
