<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
        }

        /* Header Styling */
        .header {
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            font-size: 24px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile span {
            font-size: 14px;
        }

        .profile a {
            color: white;
            text-decoration: none;
            background: #2E7D32;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .profile a:hover {
            background: #4CAF50;
        }

        /* Body Content Styling */
        .main-content {
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 28px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 18px;
        }

        /* Footer Styling */
        .footer {
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            padding: 20px 0;
            text-align: center;
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
    <div class="header">
        <h1>Admin Panel</h1>
        <div class="profile">
            <span>Admin</span>
            <a href="#">Logout</a>
        </div>
    </div>