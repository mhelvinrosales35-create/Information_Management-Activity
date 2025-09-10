<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // admin, staff, or customer

    // Insert into tblusers
    $sql = "INSERT INTO tblusers (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // If role = staff, also add to tbldeliverystaff
        if ($role == "staff") {
            $name = $_POST['name'];
            $contact = $_POST['contact'];
            $assigned_area = $_POST['assigned_area'];
            $status = "Available";

            $sql2 = "INSERT INTO tbldeliverystaff (user_id, name, contact, assigned_area, status) 
                     VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("issss", $user_id, $name, $contact, $assigned_area, $status);
            $stmt2->execute();
        }

        echo "Account created successfully! <a href='signin.php'>Sign In</a>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: White;
            height: 100vh;

            display: flex;
            justify-content: center; 
            align-items: center;     
        }
        .signup-box {
            background: White;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            color: Red;
        }
        .signup-box h2 {
            margin-bottom: 20px;
        }
        .signup-box input, .signup-box select, .signup-box button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .signup-box input:focus, .signup-box select:focus {
            border-color: Red;
            outline: none;
        }
        .signup-box button {
            background: Red;
            color: White;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .signup-box button:hover {
            background: Black;
        }
        .signin-link {
            margin-top: 15px;
            font-size: 14px;
            color: Black;
        }
        .signin-link a {
            color: Red;
            text-decoration: none;
        }
        .signin-link a:hover {
            text-decoration: underline;
			color: black;
        }
    </style>
</head>
<body>
    <div class="signup-box">
        <h1>Fast truck Logistics</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <select name="role" required onchange="toggleStaffFields(this.value)">
                <option value="">Select Role</option>
                <option value="customer">Customer</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>

            <div id="staffFields" style="display:none;">
                <input type="text" name="name" placeholder="Full Name">
                <input type="text" name="contact" placeholder="Contact Number">
                <input type="text" name="assigned_area" placeholder="Assigned Area">
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
        </div>
    </div>

    <script>
    function toggleStaffFields(role) {
        document.getElementById("staffFields").style.display = (role === "staff") ? "block" : "none";
    }
    </script>
</body>
</html>
