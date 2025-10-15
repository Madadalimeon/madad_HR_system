<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOTP = $_POST['OTP_Verification'];
    if ($enteredOTP == $_SESSION["OTP"]) {
        $_SESSION['user_logged_in'] = $_SESSION['email']; 
        unset($_SESSION["OTP"]);
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid OTP, please try again!";
    }
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
            <form action="OTP.php" method="post">
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