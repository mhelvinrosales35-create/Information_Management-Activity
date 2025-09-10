<?php
session_start();
include "config.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $assigned_area = trim($_POST['assigned_area']);
    $status = trim($_POST['status']);

    if (!empty($name) && !empty($contact) && !empty($assigned_area)) {
        $sql = "INSERT INTO tbldeliverystaff (name, contact, assigned_area, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $contact, $assigned_area, $status);
        if ($stmt->execute()) {
            header("Location: manage_staff.php?success=1");
            exit();
        } else {
            $error = "❌ Failed to add staff.";
        }
    } else {
        $error = "⚠ Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Staff</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { max-width: 400px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        a { display: inline-block; margin-top: 15px; }
        p.error { color: red; }
    </style>
</head>
<body>
    <h2>Add New Staff</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Contact:</label>
        <input type="text" name="contact" required>

        <label>Assigned Area:</label>
        <input type="text" name="assigned_area" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="Available">Available</option>
            <option value="On Delivery">On Delivery</option>
            <option value="Inactive">Inactive</option>
        </select>

        <button type="submit">➕ Add Staff</button>
    </form>

    <br>
    <a href="manage_staff.php">⬅ Back to Manage Staff</a>
</body>
</html>
