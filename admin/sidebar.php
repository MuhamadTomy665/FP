<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .sidebar-header {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            margin: 15px 0;
        }

        .sidebar nav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            transition: background 0.3s ease;
        }

        .sidebar nav ul li a:hover {
            background-color: #34495e;
        }

        /* Tombol Logout */
        .sidebar nav ul li.logout {
            margin-top: auto;
        }

        .sidebar nav ul li.logout a {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
        }

        .sidebar nav ul li.logout a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Dashboard</h2>
        </div>
        <nav>
            <ul>
                <li><a href="home_admin.php">Dashboard</a></li>
                <li><a href="user_admin.php">Data User</a></li>
                <li><a href="order_admin.php">Data Ticket</a></li>
                <li><a href="tambah_konser.php">Kelola Konser</a></li>
                <!-- Tombol Logout -->
                <li class="logout"><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>
</body>
</html>
