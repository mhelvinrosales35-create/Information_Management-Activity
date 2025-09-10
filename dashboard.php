<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.php");
    exit();
}

$role = $_SESSION['role'];
$username = "User"; // default name
if (isset($_SESSION['username'])) {
    $username = ucfirst($_SESSION['username']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f6f9;
    }
    /* Top Navbar */
    .navbar {
        background: Red;
        color: White;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .navbar .logo {
        font-weight: bold;
        font-size: 18px;
    }
    .navbar .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .navbar .user-info a {
        color: White;
        text-decoration: none;
    }
    /* Sidebar */
    .sidebar {
        width: 220px;
        background: White;
        position: fixed;
        top: 50px;
        bottom: 0;
        left: 0;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        padding-top: 20px;
    }
    .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }
    .sidebar a:hover {
        background: #f1f1f1;
    }
    /* Main content */
    .main {
        margin-left: 220px;
        padding: 20px;
    }
    .welcome {
        background: White;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
        gap: 20px;
    }
    .card {
        background: White;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        text-align: center;
    }
    .card h3 {
        margin: 10px 0;
    }
</style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">🚚 FastTruck Dashboard</div>
        <div class="user-info">
            <span>Welcome, <?php echo $username; ?>!</span>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#"><i class="fas fa-home"></i> Home</a>
        <?php if ($role == "admin") { ?>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_shipments.php"><i class="fas fa-box"></i> Manage Shipments</a>
            <a href="manage_staff.php"><i class="fas fa-id-badge"></i> Manage Staff</a>
            <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
        <?php } elseif ($role == "staff") { ?>
            <a href="my_assignments.php"><i class="fas fa-tasks"></i> My Assignments</a>
            <a href="update_status.php"><i class="fas fa-edit"></i> Update Status</a>
        <?php } else { ?>
            <a href="create_shipment.php"><i class="fas fa-plus"></i> Create Shipment</a>
            <a href="my_shipments.php"><i class="fas fa-truck"></i> My Shipments</a>
            <a href="payment.php"><i class="fas fa-credit-card"></i> Make Payment</a>
        <?php } ?>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="welcome">
            <h2>Welcome back, <?php echo $username; ?>!</h2>
            <p>Here’s an overview of your account.</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Unprinted</h3>
                <p>0</p>
            </div>
            <div class="card">
                <h3>Uncollected</h3>
                <p>0</p>
            </div>
            <div class="card">
                <h3>Canceled Today</h3>
                <p>0</p>
            </div>
            <div class="card">
                <h3>Total Shipments</h3>
                <p>0</p>
            </div>
        </div>
    </div>
</body>
</html>
