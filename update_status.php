<?php
session_start();
include "config.php";

// ✅ Ensure only staff can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "staff") {
    header("Location: sign_in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Get staff_id for this logged-in staff
$sql = "SELECT staff_id FROM tbldeliverystaff WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if (!$staff) {
    echo "<p style='color:red;'>❌ Staff record not found in tbldeliverystaff.</p>";
    exit();
}
$staff_id = $staff['staff_id'];

// ✅ Get assignment ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid assignment ID.</p>";
    exit();
}
$assignment_id = intval($_GET['id']);

// ✅ Fetch assignment and ensure it belongs to this staff
$sql = "SELECT a.assignment_id, a.status AS assignment_status, a.shipment_id
        FROM tblshipmentassignment a
        WHERE a.assignment_id = ? AND a.staff_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $assignment_id, $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

if (!$assignment) {
    echo "<p style='color:red;'>❌ Assignment not found or does not belong to you.</p>";
    exit();
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $new_status = $_POST['status'];

    // Allowed statuses
    $allowed_statuses = ["Assigned", "Picked Up", "Delivered", "Cancelled"];
    if (!in_array($new_status, $allowed_statuses)) {
        echo "<p style='color:red;'>❌ Invalid status selected.</p>";
        exit();
    }

    // Update assignment
    $sql = "UPDATE tblshipmentassignment SET status = ? WHERE assignment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $assignment_id);
    $stmt->execute();

    // Map assignment status → shipment status
    if ($new_status === "Assigned") $shipment_status = "Pending";
    elseif ($new_status === "Picked Up") $shipment_status = "In Transit";
    elseif ($new_status === "Delivered") $shipment_status = "Delivered";
    else $shipment_status = "Cancelled";

    // Update shipment
    $sql = "UPDATE tblshipments SET status = ? WHERE shipment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $shipment_status, $assignment['shipment_id']);
    $stmt->execute();

    // ✅ Redirect with success message
    header("Location: my_assignments.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Assignment Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { padding: 20px; border: 1px solid #ddd; width: 400px; background: #f9f9f9; border-radius: 8px; }
        label, select, button { display: block; margin: 10px 0; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Update Assignment Status</h2>

    <form method="POST">
        <label for="status">Choose new status:</label>
        <select name="status" id="status" required>
            <option value="Assigned"  <?php if($assignment['assignment_status']=="Assigned") echo "selected"; ?>>Assigned</option>
            <option value="Picked Up" <?php if($assignment['assignment_status']=="Picked Up") echo "selected"; ?>>Picked Up</option>
            <option value="Delivered" <?php if($assignment['assignment_status']=="Delivered") echo "selected"; ?>>Delivered</option>
            <option value="Cancelled" <?php if($assignment['assignment_status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
        </select>
        <button type="submit">Update</button>
    </form>

    <br>
    <a href="my_assignments.php">⬅ Back to My Assignments</a>
</body>
</html>
