<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Get staff_id from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid staff ID.</p>";
    exit();
}
$staff_id = intval($_GET['id']);

// ✅ Fetch staff record
$sql = "SELECT * FROM tbldeliverystaff WHERE staff_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
    echo "<p style='color:red;'>❌ Staff not found.</p>";
    exit();
}

// ✅ Update staff details
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $assigned_area = trim($_POST['assigned_area']);
    $status = trim($_POST['status']);

    if (!empty($name) && !empty($contact) && !empty($assigned_area)) {
        $sql = "UPDATE tbldeliverystaff SET name = ?, contact = ?, assigned_area = ?, status = ? WHERE staff_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $contact, $assigned_area, $status, $staff_id);

        if ($stmt->execute()) {
            header("Location: manage_staff.php?updated=1");
            exit();
        } else {
            $error = "❌ Failed to update staff.";
        }
    } else {
        $error = "⚠ Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Staff</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { max-width: 400px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { display: inline-block; margin-top: 15px; }
        p.error { color: red; }
    </style>
</head>
<body>
    <h2>Edit Staff</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($staff['contact']); ?>" required>

        <label>Assigned Area:</label>
        <input type="text" name="assigned_area" value="<?php echo htmlspecialchars($staff['assigned_area']); ?>" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="Available" <?php if($staff['status']=="Available") echo "selected"; ?>>Available</option>
            <option value="On Delivery" <?php if($staff['status']=="On Delivery") echo "selected"; ?>>On Delivery</option>
            <option value="Inactive" <?php if($staff['status']=="Inactive") echo "selected"; ?>>Inactive</option>
        </select>

        <button type="submit">💾 Save Changes</button>
    </form>

    <br>
    <a href="manage_staff.php">⬅ Back to Manage Staff</a>
</body>
</html>
