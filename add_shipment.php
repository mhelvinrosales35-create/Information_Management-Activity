<?php
session_start();
include "config.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "admin") {
    header("Location: sign_in.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];  // Customer who owns the shipment
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $receiver_name = $_POST['receiver_name'];
    $receiver_contact = $_POST['receiver_contact'];
    $weight = $_POST['weight'];
    $status = "Pending"; // default when created

    $sql = "INSERT INTO tblshipments (user_id, origin, destination, receiver_name, receiver_contact, weight, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssds", $user_id, $origin, $destination, $receiver_name, $receiver_contact, $weight, $status);

    if ($stmt->execute()) {
        header("Location: manage_shipments.php?success=1");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
    }
}


$customers = $conn->query("SELECT user_id, username FROM tblusers WHERE role = 'customer'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Shipment</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        form { width: 400px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        button { margin-top: 15px; padding: 10px; width: 100%; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Add Shipment</h2>

    <form method="POST">
        <label for="user_id">Customer:</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Select Customer --</option>
            <?php while ($row = $customers->fetch_assoc()) { ?>
                <option value="<?php echo $row['user_id']; ?>">
                    <?php echo htmlspecialchars($row['username']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="origin">Origin:</label>
        <input type="text" name="origin" id="origin" required>

        <label for="destination">Destination:</label>
        <input type="text" name="destination" id="destination" required>

        <label for="receiver_name">Receiver Name:</label>
        <input type="text" name="receiver_name" id="receiver_name" required>

        <label for="receiver_contact">Receiver Contact:</label>
        <input type="text" name="receiver_contact" id="receiver_contact" required>

        <label for="weight">Weight (kg):</label>
        <input type="number" name="weight" id="weight" step="0.01" required>

        <button type="submit">➕ Add Shipment</button>
    </form>

    <a href="manage_shipments.php">⬅ Back to Manage Shipments</a>
</body>
</html>
