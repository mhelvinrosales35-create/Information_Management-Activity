<?php
session_start();
include "config.php";

// Allow only logged-in customers
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "customer") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all shipments belonging to this customer
$sql = "SELECT * FROM tblshipments WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Shipments</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f9f9f9; }
        .back-btn { margin-top: 15px; display: inline-block; }
    </style>
</head>
<body>
    <h2>My Shipments</h2>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Receiver Name</th>
                <th>Receiver Contact</th>
                <th>Weight (kg)</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['shipment_id']; ?></td>
                    <td><?php echo $row['origin']; ?></td>
                    <td><?php echo $row['destination']; ?></td>
                    <td><?php echo $row['receiver_name']; ?></td>
                    <td><?php echo $row['receiver_contact']; ?></td>
                    <td><?php echo $row['weight']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No shipments found. <a href="create_shipment.php">Create one</a>.</p>
    <?php } ?>

    <br>
    <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
</body>
</html>
