<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
if (!isset($_SESSION['OTP'])) {
    $OTP = rand(100000, 999999);
    $_SESSION['OTP'] = $OTP;
} else {
    $OTP = $_SESSION['OTP'];
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOTP = $_POST['OTP_Verification'];
    if ($enteredOTP == $_SESSION["OTP"]) {
        unset($_SESSION["OTP"]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid OTP, please try again!";
    }
}
try {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'madadalimemon90@gmail.com'; 
    $mail->Password   = 'hfcf ohbk htrg hgeg';       
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->setFrom('madadalimemon90@gmail.com', 'HR System'); 
    $mail->addAddress('madadalim903@gmail.com');             
    $mail->addReplyTo('madadalimemon90@gmail.com', 'HR System');

    $mail->isHTML(true);
    $mail->Subject = 'OTP Verification Code';
    $mail->Body    = "Hello,<br><br>Your OTP verification code is: <b>$OTP</b><br><br>Do not share this code with anyone.";
    $mail->AltBody = "Hello, Your OTP verification code is: $OTP. Do not share this code with anyone.";

    $mail->send();
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>OTP Verification</title>
</head>
<style>
    body {
        background-color: #f8f9fc;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    h5 {
        color: #4e73df;
        font-size: 18px;
        font-weight: 700;
    }
</style>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4" style="width: 400px;">
        <div class="text-center">
            <h3 class="mb-3">OTP Verification</h3>
            <h5>Get Your Code</h5>
            <p class="text-muted">Please enter the OTP code that was sent to your email address.</p>
            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger py-2"><?php echo $error; ?></div>
            <?php } ?>
            <form  method="post">
                <div class="mb-3">
                    <input type="text" name="OTP_Verification" class="form-control text-center" placeholder="Enter OTP Code" >
                </div>
                <button type="submit" class="btn btn-primary w-100">Verify Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>