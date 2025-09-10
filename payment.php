<?php
session_start();
include "config.php";

// Ensure only customers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "customer") {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch customer's shipments (only Pending or In Transit, unpaid ones)
$sql = "SELECT s.shipment_id, s.destination, s.weight, p.payment_id 
        FROM tblshipments s 
        LEFT JOIN tblpayments p ON s.shipment_id = p.shipment_id
        WHERE s.user_id = ? AND (p.payment_id IS NULL OR p.status != 'Paid')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$shipments = $stmt->get_result();

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $shipment_id = intval($_POST['shipment_id']);
    $amount      = floatval($_POST['amount']);
    $method      = $_POST['method'];
    $status      = "Paid"; // For now we assume payment is successful

    $sql = "INSERT INTO tblpayments (shipment_id, amount, method, status) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $shipment_id, $amount, $method, $status);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Payment successful for shipment #$shipment_id</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input, select, button { padding: 8px; margin-top: 5px; width: 250px; }
        .back-btn { margin-top: 15px; display: inline-block; }
    </style>
</head>
<body>
    <h2>Make a Payment</h2>

    <?php if ($shipments->num_rows > 0) { ?>
        <form method="POST">
            <label for="shipment_id">Select Shipment:</label>
            <select name="shipment_id" required>
                <?php while ($row = $shipments->fetch_assoc()) { 
                    $amount = $row['weight'] * 10; // Example: charge 10 per kg
                ?>
                    <option value="<?php echo $row['shipment_id']; ?>">
                        #<?php echo $row['shipment_id']; ?> - <?php echo $row['destination']; ?> (<?php echo $row['weight']; ?> kg) - $<?php echo $amount; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="amount">Amount ($):</label>
            <input type="number" step="0.01" name="amount" value="<?php echo isset($amount) ? $amount : ''; ?>" required readonly>

            <label for="method">Payment Method:</label>
            <select name="method" required>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="Online">Online</option>
            </select>

            <button type="submit">Pay Now</button>
        </form>
    <?php } else { ?>
        <p>No shipments found that require payment.</p>
    <?php } ?>

    <br>
    <a class="back-btn" href="dashboard.php">Back to Dashboard</a>
</body>
</html>
