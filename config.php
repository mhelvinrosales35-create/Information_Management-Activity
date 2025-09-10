<?php
$host = "localhost";
$user = "root"; // DB username
$pass = "";     // DB password
$db   = "logistics"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>