<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Validate user ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color:red;'>Invalid user ID.</p>";
    exit();
}
$user_id = intval($_GET['id']);

// ✅ Fetch user details
$sql = "SELECT * FROM tblusers WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p style='color:red;'>User not found.</p>";
    exit();
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        // Hash new password if provided
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE tblusers SET username = ?, password = ?, role = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $hashed_password, $role, $user_id);
    } else {
        // Keep old password
        $sql = "UPDATE tblusers SET username = ?, role = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $role, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: manage_users.php?msg=updated");
        exit();
    } else {
        echo "<p style='color:red;'>Error updating user.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { padding: 20px; border: 1px solid #ddd; width: 400px; background: #f9f9f9; border-radius: 8px; }
        label { display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; }
        button { padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label>Password (leave blank to keep old password)</label>
        <input type="password" name="password">

        <label>Role</label>
        <select name="role" required>
            <option value="admin" <?php if ($user['role']=="admin") echo "selected"; ?>>Admin</option>
            <option value="staff" <?php if ($user['role']=="staff") echo "selected"; ?>>Staff</option>
            <option value="customer" <?php if ($user['role']=="customer") echo "selected"; ?>>Customer</option>
        </select>

        <button type="submit">Update User</button>
    </form>

    <br>
    <a href="manage_users.php">⬅ Back to Manage Users</a>
</body>
</html>
