<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $sql = "DELETE FROM tblusers WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_users.php?msg=deleted");
    exit();
}

// ✅ Fetch all users
$sql = "SELECT user_id, username, role, created_at FROM tblusers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        tr:hover { background: #f9f9f9; }
        a.btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; }
        .edit { background: #007bff; color: white; }
        .delete { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <h2>Manage Users</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == "deleted") { ?>
        <p style="color: green;">User deleted successfully.</p>
    <?php } ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a class="btn edit" href="edit_user.php?id=<?php echo $row['user_id']; ?>">Edit</a>
                    <a class="btn delete" href="manage_users.php?delete=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
