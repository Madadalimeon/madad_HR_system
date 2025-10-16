<?php

$inactive = 3600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: ./Backend/logout.php");
    exit;
}
$_SESSION['last_activity'] = time();


session_start();
include './config/config.php';
include './include/header.php';
if (!isset($_SESSION['Roles_id'])) {
    die("Please log in first!");
}
$attendancePermissionss = $rolePermissions['permissions']['Attendance'] ?? [];

if (!isset($attendancePermissionss['View']) || $attendancePermissionss['View'] != 1) {
    header("Location: ./index.php");
    exit;
}



if (isset($_POST['sign_in'])) {
    $emp = $_SESSION['employees_id'];
    $today = date('Y-m-d');
    $sign_in_time = date('H:i:s');

    $check_sql = "SELECT * FROM attendance WHERE employees_id = '$emp' AND date = '$today'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<div class='alert alert-info text-center'>You have already signed in today.</div>";
    } else {
        $insert_sql = "INSERT INTO attendance (employees_id, sign_on, date) 
                       VALUES ('$emp', '$sign_in_time', '$today')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "<div class='alert alert-success text-center'>Sign In Successful</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
        }
    }
}
if (isset($_POST['sign_out'])) {
    $emp = $_SESSION['employees_id'];
    $today = date('Y-m-d');
    $sign_out_time = date('H:i:s');

    $check_sql = "SELECT * FROM attendance WHERE employees_id = '$emp' AND date = '$today'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        echo "<div class='alert alert-warning text-center'>You haven't signed in today.</div>";
    } else {
        $update_sql = "UPDATE attendance 
                       SET sign_out = '$sign_out_time' 
                       WHERE employees_id = '$emp' AND date = '$today'";
        if ($conn->query($update_sql) === TRUE) {
            echo "<div class='alert alert-success text-center'>Sign Out Successful</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . $conn->error . "</div>";
        }
    }
}
?>
<style>
    .h {
        display: flex;
        justify-content: center;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="h">Sign In & Sign Out</h3>
        </div>
    </div>
</div>
<div class="container my-5">    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" class="text-center">
                <div class="row">
                    <?php if (isset($attendancePermissionss['Add']) && $attendancePermissionss['Add'] == 1): ?>
                        <div class="col-6 mb-3">
                            <button type="submit" name="sign_in" class="btn btn-success w-100 py-3">
                                <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                            </button>
                        </div>
                    <?php else: ?>
                        <span class="text-danger">You do not have permission to sign_in an employee!</span>
                    <?php endif; ?>

                    <?php if (isset($attendancePermissionss['Add']) && $attendancePermissionss['Add'] == 1): ?>
                        <div class="col-6 mb-3">
                            <button type="submit" name="sign_out" class="btn btn-danger w-100 py-3">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out
                            </button>
                        </div>
                    <?php else: ?>
                        <span class="text-danger">You do not have permission to sign_out an employee!</span>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include './include/footer.php';
?>
