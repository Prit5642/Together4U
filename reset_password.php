<?php
// Database connection parameters
include('includes/config.php');

// Check if token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token
    $sql = "SELECT * FROM password_reset_tokens WHERE token = '$token'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid, allow password reset
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and validate new password
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            if ($new_password === $confirm_password) {
                // Hash the new password before storing it in the database
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Retrieve the email associated with the token
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Update the user's password in the database
                $sql_update_password = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
                if ($conn->query($sql_update_password) === TRUE) {
                    // Password updated successfully, delete the token
                    $sql_delete_token = "DELETE FROM password_reset_tokens WHERE token = '$token'";
                    $conn->query($sql_delete_token);

                    echo "Password reset successful. You can now <a href='login.php'>login</a> with your new password.";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "Token not provided.";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Membership Management System</title>
    
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .login-page {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 360px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Reset Password</p>
                <?php
                    if (isset($error_message)) {
                    echo '<div class="alert alert-danger">' . $error_message . '</div>';
                    }
                ?>
                <?php
                    if (isset($success_message)) {
                    echo '<div class="alert alert-success">' . $error_message . '</div>';
                    }
                ?>
                <!-- Form -->
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="index.php">Back to Login</a>
                </p>

            </div>
        </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>