<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM tblusers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role']    = $row['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>User not found.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: white; 
            height: 100vh;

            display: flex;
            justify-content: center; 
            align-items: center;     
        }
        .login-box {
            background: white; 
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.2);
            width: 370px;
            text-align: center;
            color: red; 
        }
        .login-box h2 {
            margin-bottom: 20px;
        }
        .login-box input, .login-box button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: Gray;
        }
        .login-box input:focus {
            border-color: red;
            outline: none;
        }
        .login-box button {
            background: red;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-box button:hover {
            background: black;
        }
        .signup-link {
            margin-top: 15px;
            font-size: 14px;
            color: Black;
        }
        .signup-link a {
            color: Red;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
		h1 {
			color: Red;
		}
		
			
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Fast truck Logistic</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter username" required>

            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Enter password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
            </div>

            <button type="submit">Sign In</button>
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            let pass = document.getElementById("password");
            let icon = document.querySelector(".toggle-password");
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                pass.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
