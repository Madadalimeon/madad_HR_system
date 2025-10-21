<?php
session_start();
$inactive = 3600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: ./Backend/logout.php");
    exit;
}
$_SESSION['last_activity'] = time();
include("./config/config.php");
include("./include/header.php");
if (!isset($_SESSION['Roles_id'])) {
    die("Please log in first!");
}
include_once("./haspermission.php");
$rolePermissions = getRolePermissions($_SESSION['Roles_id'] ?? 0);
$employeePnermissions = $rolePermissions['permissions']['Employees'] ?? [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    foreach ($_POST['otp_data'] as $emp_id => $status) {
        $check_sql = "SELECT OTP_Check FROM employees WHERE employees_id = '$emp_id'";
        $check_result = mysqli_query($conn, $check_sql);
        $row = mysqli_fetch_assoc($check_result);
        $otp_value = ($row['OTP_Check'] == 1) ? 0 : 1;
        $update_sql = "UPDATE employees SET OTP_Check = '$otp_value' WHERE employees_id = '$emp_id'";
        mysqli_query($conn, $update_sql);
    }
    echo "<div class='alert alert-success' role='alert'>OTP Status Updated Successfully</div>";
}       

?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Employee Table</h1>
        <div>
            <?php if (isset($employeePermissions['Add']) && $employeePermissions['Add'] == 1): ?>
                <a href="add-employee.php" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-user-plus fa-sm text-white-50"></i> Add Employee
                </a>
            <?php else: ?>
                <span class="text-danger">You do not have permission to add an employee!</span>
            <?php endif; ?>
        </div>
    </div>

    <form method="post">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="m-0 font-weight-bold">Manage Employee OTP Access</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>DOB</th>
                                <th>Roles</th>
                                <?php if ($_SESSION['Roles_id'] == 15): ?>
                                    <th class="text-center">Allow OTP Login</th>
                                <?php endif; ?>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT employees_id, first_name, last_name, email, mobile_no, dob, roles_name, OTP_Check FROM employees";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= $row['employees_id']; ?></td>
                                    <td><?= $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= $row['mobile_no']; ?></td>
                                    <td><?= $row['dob']; ?></td>
                                    <td><?= $row['roles_name']; ?></td>

                                    <?php if ($_SESSION['Roles_id'] == 15): ?>
                                        <td class="text-center">
                                            <input type="checkbox" class="otp-checkbox" name="otp_data[<?= $row['employees_id']; ?>]" <?= $row['OTP_Check'] == 1 ? 'checked' : '' ?>>
                                        </td>
                                    <?php endif; ?>

                                    <td class="text-center">
                                        <?php if ($employeePermissions['Update'] == 1): ?>
                                            <a href="update.php?id=<?= $row['employees_id']; ?>" class="text-primary mx-2"><i class="fa-solid fa-pen"></i></a>
                                        <?php endif; ?>
                                        <?php if ($employeePermissions['Delete'] == 1): ?>
                                            <a href="./Backend/delete.php?id=<?= $row['employees_id']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <?php if ($_SESSION['Roles_id'] == 15): ?>
                        <button type="submit" class="btn btn-primary save-btn shadow"> Save OTP </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include("./include/footer.php"); ?>