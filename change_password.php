<?php
session_start();
include("./config/config.php");
if (!isset($_GET['email'])) {
    die("Email is required.");
}
$email = mysqli_real_escape_string($conn, $_GET['email']);
$error = "";
$success = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = md5($new_password);
        $sql = "UPDATE login_credentials lc 
                JOIN employees e ON e.employees_id = lc.employees_id 
                SET lc.password = '$hashed_password' 
                WHERE e.email = '$email'";
        if ($conn->query($sql)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Failed to update password: " . $conn->error;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Forgot Password</title>
    <style>
        body {
            background: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 25px;
            width: 380px;
            background: #fff;
        }
        .btn-custom {
            background: #4e73df;
            border: none;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #2e59d9;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="card">
        <h4 class="text-center mb-3">Forgot Password</h4>
        <p class="text-muted text-center" style="font-size: 14px;">
            Enter your new password for <?= htmlspecialchars($email); ?>
        </p>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3 position-relative">
                <label class="form-label">New password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                <i class="fa-solid fa-eye-slash toggle-password" data-target="new_password"></i>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                <i class="fa-solid fa-eye-slash toggle-password" data-target="confirm_password"></i>
            </div>

            <button type="submit" class="btn btn-custom w-100 text-white">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle Script -->
    <script>
        document.querySelectorAll(".toggle-password").forEach(icon => {
            icon.addEventListener("click", function() {
                const targetId = this.getAttribute("data-target");
                const input = document.getElementById(targetId);

                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                } else {
                    input.type = "password";
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                }
            });
        });
    </script>
</body>
</html>