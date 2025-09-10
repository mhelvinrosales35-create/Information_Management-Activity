<?php
session_start();
include "config.php";

// ✅ Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}

// ✅ Get shipment ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p style='color:red;'>Invalid shipment ID.</p>";
    exit();
}
$shipment_id = intval($_GET['id']);

// ✅ Fetch shipment details
$sql = "SELECT * FROM tblshipments WHERE shipment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shipment_id);
$stmt->execute();
$result = $stmt->get_result();
$shipment = $result->fetch_assoc();

if (!$shipment) {
    echo "<p style='color:red;'>Shipment not found.</p>";
    exit();
}

// ✅ Handle update form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $receiver_name = $_POST['receiver_name'];
    $receiver_contact = $_POST['receiver_contact'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];

    $sql = "UPDATE tblshipments 
            SET origin = ?, destination = ?, receiver_name = ?, receiver_contact = ?, weight = ?, status = ?
            WHERE shipment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdsi", $origin, $destination, $receiver_name, $receiver_contact, $weight, $status, $shipment_id);

    if ($stmt->execute()) {
        header("Location: manage_shipments.php?updated=1");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Error updating shipment: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Shipment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { width: 400px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { margin-top: 15px; padding: 10px; width: 100%; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Edit Shipment</h2>

    <form method="POST">
        <label for="origin">Origin:</label>
        <input type="text" name="origin" id="origin" value="<?php echo htmlspecialchars($shipment['origin']); ?>" required>

        <label for="destination">Destination:</label>
        <input type="text" name="destination" id="destination" value="<?php echo htmlspecialchars($shipment['destination']); ?>" required>

        <label for="receiver_name">Receiver Name:</label>
        <input type="text" name="receiver_name" id="receiver_name" value="<?php echo htmlspecialchars($shipment['receiver_name']); ?>" required>

        <label for="receiver_contact">Receiver Contact:</label>
        <input type="text" name="receiver_contact" id="receiver_contact" value="<?php echo htmlspecialchars($shipment['receiver_contact']); ?>" required>

        <label for="weight">Weight (kg):</label>
        <input type="number" name="weight" id="weight" step="0.01" value="<?php echo htmlspecialchars($shipment['weight']); ?>" required>

        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="Pending" <?php if($shipment['status']=="Pending") echo "selected"; ?>>Pending</option>
            <option value="In Transit" <?php if($shipment['status']=="In Transit") echo "selected"; ?>>In Transit</option>
            <option value="Delivered" <?php if($shipment['status']=="Delivered") echo "selected"; ?>>Delivered</option>
            <option value="Cancelled" <?php if($shipment['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
        </select>

        <button type="submit">💾 Update Shipment</button>
    </form>

    <a href="manage_shipments.php">⬅ Back to Manage Shipments</a>
</body>
</html>
