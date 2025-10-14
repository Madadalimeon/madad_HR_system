<?php  
include("./config/config.php"); 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
require 'vendor/autoload.php'; 

$error = ""; 
$success = ""; 
$email = ""; 

if (isset($_GET['email'])) {     
    $email = mysqli_real_escape_string($conn, $_GET['email']);     
    $sql = "SELECT email FROM employees WHERE email = '$email' LIMIT 1";     
    $result = $conn->query($sql);     

    if ($result && $result->num_rows > 0) {         
        $mail = new PHPMailer(true);         
        try {             
            $mail->isSMTP();             
            $mail->Host       = 'smtp.gmail.com';             
            $mail->SMTPAuth   = true;             
            $mail->Username   = 'madadalimemon90@gmail.com';             
            $mail->Password   = 'hfcf ohbk htrg hgeg'; // App Password
            $mail->SMTPSecure = 'tls';             
            $mail->Port       = 587;              

            $mail->setFrom('madadalimemon90@gmail.com', 'HR System');             
            $mail->addAddress($email);             
            $mail->addReplyTo('madadalimemon90@gmail.com', 'HR Support');              

            $reset_link = "http://localhost/madad_HR_system/change_password.php?email=" . urlencode($email);              

            $mail->isHTML(true);             
            $mail->Subject = 'Password Reset Link';             
            $mail->Body    = "Click here to reset your password: <a href='$reset_link'>Reset Password</a>";             
            $mail->AltBody = "Open this link to reset your password: $reset_link";              

            if ($mail->send()) {                 
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: 'Reset link sent successfully to your email!',
                            confirmButtonColor: '#4e73df'
                        });
                    });
                </script>";
            } else {                 
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed!',
                            text: 'Failed to send email. Try again!',
                            confirmButtonColor: '#4e73df'
                        });
                    });
                </script>";
            }         
        } catch (Exception $e) {             
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Mailer Error!',
                        text: 'Something went wrong: {$mail->ErrorInfo}',
                        confirmButtonColor: '#4e73df'
                    });
                });
            </script>";
        }     
    } else {         
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Not Found!',
                    text: 'This email is not registered in our system.',
                    confirmButtonColor: '#4e73df'
                });
            });
        </script>";
    } 
}
?>

<!doctype html>
<html lang="en">
<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert CDN -->
    <title>Forget Password</title>
    <style>
        body {
            background: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .btn-custom {
            background: #4e73df;
            border: none;
        }
        .btn-custom:hover {
            background: #2e59d9;
        }
    </style>
</head>
<body>

<div class="card p-4" style="width: 380px;">
    <h4 class="text-center mb-3">Forgot Password</h4>
    <p class="text-muted text-center" style="font-size: 14px;">Enter your email to receive reset link</p>

    <form method="get">
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn btn-custom w-100 text-white">Send Reset Link</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
