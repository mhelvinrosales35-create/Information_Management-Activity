<?php
session_start();
include "config.php";

// Only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "staff") {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Find staff_id using user_id
$sql = "SELECT staff_id FROM tbldeliverystaff WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
    echo "<p style='color:red;'>Staff record not found.</p>";
    exit();
}
$staff_id = $staff['staff_id'];

// Get staff assignments
$sql = "SELECT a.assignment_id, a.status AS assignment_status, a.assigned_at,
               s.shipment_id, s.origin, s.destination, s.receiver_name, s.receiver_contact, 
               s.weight, s.status AS shipment_status
        FROM tblshipmentassignment a
        JOIN tblshipments s ON a.shipment_id = s.shipment_id
        WHERE a.staff_id = ?
        ORDER BY a.assigned_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Assignments</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f9f9f9; }
        a.action-link { color: blue; text-decoration: none; }
        a.action-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>My Assignments</h2>

    <?php if ($assignments->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Assignment ID</th>
                <th>Shipment ID</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Receiver</th>
                <th>Contact</th>
                <th>Weight (kg)</th>
                <th>Shipment Status</th>
                <th>Assignment Status</th>
                <th>Assigned At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $assignments->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['assignment_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['shipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['origin']); ?></td>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['receiver_contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['weight']); ?></td>
                    <td><?php echo htmlspecialchars($row['shipment_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['assignment_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['assigned_at']); ?></td>
                    <td>
                        <a class="action-link" href="update_status.php?id=<?php echo urlencode($row['assignment_id']); ?>">Update</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No assignments found.</p>
    <?php } ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
