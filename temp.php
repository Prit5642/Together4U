<?php
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $error_message = "Please enter your email.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format.";
        } else {
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(32));
                $reset_link = "localhost/MembershipM-PHP/reset_password.php?token=$token"; 
                $sql_insert_token = "INSERT INTO password_reset_tokens (email, token) VALUES ('$email', '$token')";
                if ($conn->query($sql_insert_token) === TRUE) {
                    ini_set("SMTP", "smtp.gmail.com");
                    ini_set("smtp_port", "587");
                    ini_set("sendmail_from", "pritshah096@gmail.com");
                    $to = $email;
                    $subject = "Password Reset";
                    $message = "Click the link below to reset your password:\n\n$reset_link";
                    $headers = "From: pritshah096@gmail.com";

                    if (mail($to, $subject, $message, $headers)) {
                        $success_message = "Password reset link has been sent to your email.";
                    } else {
                        $error_message = "Failed to send password reset link. Please try again later.";
                    }
                } else {
                    $error_message = "Error: " . $sql_insert_token . "<br>" . $conn->error;
                }
            } else {
                $error_message = "Email not found. Please enter a registered email.";
            }
        }
    }
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
                <p class="login-box-msg">Forgot Password</p>
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
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Send Link!</button>
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