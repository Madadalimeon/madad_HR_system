<?php
session_start();
include("./include/header.php");
include("./config/config.php");
include("./Backend/delete.php");
include_once("./haspermission.php");

if (!isset($_SESSION['Roles_id'])) {
    die("Please log in first!");
}
$rolePermissions = getRolePermissions($_SESSION['Roles_id']);
$employeeDepartment = $rolePermissions['permissions']['Department'] ?? [];
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Employee Department Table</h1>

        <?php if (isset($employeeDepartment['Add']) && $employeeDepartment['Add'] == 1): ?>
            <a href="department_add.php" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus fa-sm text-white-50"></i> Add Department
            </a>
        <?php else: ?>
            <span class="text-danger">You do not have permission to add a department!</span>
        <?php endif; ?>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Department List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $department_sql = "SELECT dempartment_id, dempartment_name FROM dempartment";
                        $print_data = $conn->query($department_sql);

                        if ($print_data && $print_data->num_rows > 0) {
                            while ($row = $print_data->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row['dempartment_id']; ?></td>
                                    <td><?php echo $row['dempartment_name']; ?></td>
                                    <td>
                                        <?php if (isset($employeeDepartment['Update']) && $employeeDepartment['Update'] == 1): ?>
                                            <a href="./department_updata.php?id=<?php echo $row['dempartment_id']; ?>" class="text-primary me-2">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (isset($employeeDepartment['Delete']) && $employeeDepartment['Delete'] == 1): ?>
                                            <a href="./backend/department_Delete.php?id=<?php echo $row['dempartment_id']; ?>" class="text-danger">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No departments found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
 include("./include/footer.php"); 
 
?>
