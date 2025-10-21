<?php
session_start();
include './config/config.php';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $sql = "SELECT e.employees_id, u.username, u.password, e.Roles_id, e.email
            FROM login_credentials u
            INNER JOIN employees e ON u.employees_id = e.employees_id
            WHERE u.username = '$username' AND u.password = '$password'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['employees_id'] = $row['employees_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['Roles_id'] = $row['Roles_id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['last_activity'] = time();
        $_SESSION['otp'] = $OTP;
        $_SESSION['otp_time'] = time();
        $_SESSION['reset_link'] = time();
        $e_id = $_SESSION['employees_id'];
        $OPT_query = "SELECT OTP_Check FROM employees WHERE employees_id ='$e_id'";
        $stmt = mysqli_query($conn, $OPT_query)->fetch_assoc();
        $OTP  = intval($stmt['OTP_Check']);
        if ($OTP === 1) {
            header("Location: email.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 380px;">
        <h3 class="text-center mb-3">Login</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" class="form-control" placeholder="Email or Username" id="username" name="username" required>
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Log In</button>

            <div class="text-center mt-3">
                <a href="forget_password.php" class="text-decoration-none">Forgot Password?</a>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-danger w-50 me-2"><i class="fab fa-google"></i> Google</button>
                <button type="button" class="btn btn-outline-primary w-50 ms-2"><i class="fab fa-facebook"></i> Facebook</button>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>