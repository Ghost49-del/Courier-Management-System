<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .btn-dashboard {
            width: 100%;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logout-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require 'partials/_nav.php' ?>

    <div class="dashboard-container">
        <div class="welcome-card">
            <h2>Welcome, <?php echo $_SESSION['mail']?></h2>
            <p>You are logged into the Courier Management System</p>
            <hr>
            <p>Need to leave? <a href="/login/logout.php" class="logout-link">Click here to logout</a></p>
        </div>

        <div class="grid-container">
            <div class="dashboard-card">
                <i class="fas fa-box"></i>
                <h4>Place Courier</h4>
                <a href="/login/place_courier.php" class="btn btn-danger btn-dashboard">Start Shipping</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-truck"></i>
                <h4>Track Delivery</h4>
                <a href="/login/delivery_status.php" class="btn btn-success btn-dashboard">Check Status</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-comments"></i>
                <h4>Feedback</h4>
                <a href="/login/feedback.php" class="btn btn-warning btn-dashboard">Give Feedback</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-trash-alt"></i>
                <h4>Remove Feedback</h4>
                <a href="/login/del_feedback.php" class="btn btn-dark btn-dashboard">Delete</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-user-plus"></i>
                <h4>Staff Registration</h4>
                <a href="/login/staff.php" class="btn btn-primary btn-dashboard">Register Staff</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-user-edit"></i>
                <h4>Update Staff</h4>
                <a href="/login/staff_update.php" class="btn btn-secondary btn-dashboard">Update Details</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-store"></i>
                <h4>Add Franchise</h4>
                <a href="/login/franchise.php" class="btn btn-danger btn-dashboard">New Franchise</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-store-slash"></i>
                <h4>Remove Franchise</h4>
                <a href="/login/del_franchise.php" class="btn btn-dark btn-dashboard">Delete Franchise</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>