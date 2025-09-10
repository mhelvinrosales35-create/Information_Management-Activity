<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Fetch all shipments
$sql = "SELECT s.shipment_id, u.username AS customer, s.origin, s.destination, 
               s.weight, s.status, s.created_at
        FROM tblshipments s
        JOIN tblusers u ON s.user_id = u.user_id
        ORDER BY s.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Shipments</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f9f9f9; }
        a.action-link { color: blue; text-decoration: none; margin: 0 5px; }
        a.action-link:hover { text-decoration: underline; }
        .add-btn {
            display: inline-block; margin-bottom: 15px; padding: 10px 15px;
            background: #28a745; color: white; text-decoration: none;
            border-radius: 5px;
        }
        .add-btn:hover { background: Green; }
    </style>
</head>
<body>
    <h2>Manage Shipments</h2>

    <a class="add-btn" href="add_shipment.php">+ Add Shipment</a>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Weight (kg)</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['shipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer']); ?></td>
                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a class="action-link" href="edit_shipment.php?id=<?php echo urlencode($row['shipment_id']); ?>">Edit</a> |
                        <a class="action-link" href="delete_shipment.php?id=<?php echo urlencode($row['shipment_id']); ?>" onclick="return confirm('Are you sure you want to delete this shipment?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No shipments found.</p>
    <?php } ?>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
