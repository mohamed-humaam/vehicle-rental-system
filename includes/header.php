<?php
session_start();

// Redirect to login if not logged in (except for the login page itself)
if (!isset($_SESSION['user_id']) && !strpos($_SERVER['REQUEST_URI'], 'login.php')) {
    header("Location: /modules/login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rental</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <script src="/assets/js/header.js" defer></script>
    <style>
        header {
            background: #2c3e50;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header h1 {
            color: white;
            margin: 0;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background: rgba(255,255,255,0.1);
        }

        .logout-btn {
            margin-left: auto;
            background-color: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .user-name {
            color: #3498db;
            margin-right: 10px;
            cursor: default;
        }

        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }

            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-btn {
                margin-left: 0;
                margin-top: 1rem;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Vehicle Rental System</h1>
        <nav>
            <a href="/index.php">Home</a>
            <a href="/modules/vehicles/list.php">Vehicles</a>
            <a href="/modules/bookings/list.php">Bookings</a>
            <a href="/modules/customers/list.php">Customers</a>
            <a href="/modules/payments/report.php">Payments</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['user_name'])): ?>
                    <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <?php endif; ?>
                <a href="/modules/login/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php endif; ?>
        </nav>
    </header>
    <main>