<?php
session_start();
include "config.php";

// Ensure only customers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "customer") {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id         = $_SESSION['user_id'];
    $origin          = intval($_POST['origin']);
    $destination     = trim($_POST['destination']);
    $receiver_name   = trim($_POST['receiver_name']);
    $receiver_contact= trim($_POST['receiver_contact']);
    $weight          = floatval($_POST['weight']);
    $status          = "Pending";

    $sql = "INSERT INTO tblshipments (user_id, origin, destination, receiver_name, receiver_contact, weight, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssds", $user_id, $origin, $destination, $receiver_name, $receiver_contact, $weight, $status);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Shipment created successfully!</p>";
        echo "<a href='my_shipments.php'>View My Shipments</a>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<h2>Create Shipment</h2>
<form method="POST">
    <label>Origin (Branch/Code):</label><br>
    <input type="number" name="origin" required><br><br>

    <label>Destination:</label><br>
    <input type="text" name="destination" required><br><br>

    <label>Receiver Name:</label><br>
    <input type="text" name="receiver_name" required><br><br>

    <label>Receiver Contact:</label><br>
    <input type="text" name="receiver_contact" required><br><br>

    <label>Weight (kg):</label><br>
    <input type="number" step="0.01" name="weight" required><br><br>

    <button type="submit">Create Shipment</button>
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>
