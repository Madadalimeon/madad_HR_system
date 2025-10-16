<?php
session_start();

$inactive = 60;
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
$employeePermissions = $rolePermissions['permissions']['Employees'] ?? [];

 if (!isset($employeePermissions['View']) && $employeePermissions['View'] == 1){
            header("Location: ./index.php");
 }

?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Employee  Table</h1>
        <div class="mt-4">
            <?php if(isset($employeePermissions['Add']) && $employeePermissions['Add'] == 1): ?>
                <a href="add-employee.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus fa-sm text-white-50"></i> Add Employee
                </a>
            <?php else: ?>
                <span class="text-danger">You do not have permission to add an employee!</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>DOB</th>
                            <th>Roles</th>
                            <th>OTP_check</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_employees = "SELECT employees_id, first_name, last_name, email, mobile_no, dob, date_of_joining, position,  roles_name FROM employees";
                        $print_data = $conn->query($sql_employees);

                        if ($print_data->num_rows > 0):
                            while ($row = $print_data->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $row['employees_id']; ?></td>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['mobile_no']; ?></td>
                            <td><?php echo $row['dob']; ?></td>
                            <td><?php echo $row['roles_name']; ?></td>
                            <td><?php echo $row['roles_name']; ?></td>
                            <td>
                                <?php if(isset($employeePermissions['Update']) && $employeePermissions['Update'] == 1): ?>
                                    <a href="update.php?id=<?php echo $row['employees_id']; ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(isset($employeePermissions['Delete']) && $employeePermissions['Delete'] == 1): ?>
                                    <a href="./Backend/delete.php?id=<?php echo $row['employees_id']; ?>" onclick="return confirm('Are you sure?')">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                            echo "<tr><td colspan='7' class='text-center'>No employees found</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("./include/footer.php"); ?>
