<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// 📌 Get total shipments
$total_shipments = $conn->query("SELECT COUNT(*) AS total FROM tblshipments")->fetch_assoc()['total'];

// 📌 Get shipments by status
$shipment_status = $conn->query("SELECT status, COUNT(*) AS count FROM tblshipments GROUP BY status");

// 📌 Get total staff
$total_staff = $conn->query("SELECT COUNT(*) AS total FROM tbldeliverystaff")->fetch_assoc()['total'];

// 📌 Get staff by status
$staff_status = $conn->query("SELECT status, COUNT(*) AS count FROM tbldeliverystaff GROUP BY status");

// 📌 Get payments summary
$total_payments = $conn->query("SELECT SUM(amount) AS total FROM tblpayments WHERE status='Paid'")->fetch_assoc()['total'];
$payment_methods = $conn->query("SELECT method, COUNT(*) AS count FROM tblpayments WHERE status='Paid' GROUP BY method");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports - Fast Truck Logistics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2 { margin-top: 30px; }
        table { border-collapse: collapse; width: 60%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f9f9f9; }
        .summary { margin: 20px 0; padding: 10px; background: #eef; border-left: 4px solid #007bff; }
        a { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>📊 Reports Dashboard</h1>

    <div class="summary">
        <strong>Total Shipments:</strong> <?php echo $total_shipments; ?><br>
        <strong>Total Staff:</strong> <?php echo $total_staff; ?><br>
        <strong>Total Payments Collected:</strong> ₱<?php echo number_format($total_payments ?? 0, 2); ?>
    </div>

    <h2>📦 Shipments by Status</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        <?php while($row = $shipment_status->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>👷 Delivery Staff by Status</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        <?php while($row = $staff_status->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h2>💰 Payments by Method</h2>
    <table>
        <tr><th>Method</th><th>Count</th></tr>
        <?php while($row = $payment_methods->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['method']); ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
