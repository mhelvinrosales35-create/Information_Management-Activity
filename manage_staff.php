<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Fetch all staff
$sql = "SELECT * FROM tbldeliverystaff ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Staff</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h2 { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f9f9f9; }
        a.btn { padding: 6px 12px; text-decoration: none; border-radius: 5px; }
        a.edit { background: #007bff; color: white; }
        a.delete { background: #dc3545; color: white; }
        a.add { background: #28a745; color: white; margin-bottom: 15px; display: inline-block; }
    </style>
</head>
<body>
    <h2>Manage Delivery Staff</h2>

    <a href="add_staff.php" class="btn add">➕ Add New Staff</a>

    <?php if ($result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Assigned Area</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td><?php echo htmlspecialchars($row['assigned_area']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a class="btn edit" href="edit_staff.php?id=<?php echo $row['staff_id']; ?>">✏ Edit</a>
                        <a class="btn delete" href="delete_staff.php?id=<?php echo $row['staff_id']; ?>" onclick="return confirm('Are you sure you want to delete this staff?');">🗑 Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No staff found.</p>
    <?php } ?>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
