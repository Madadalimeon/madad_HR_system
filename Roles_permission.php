<?php
session_start();
include './config/config.php';
include './include/header.php';


if (!isset($_GET['id'])) {
    die("<div class='alert alert-danger'>Role ID not provided in URL!</div>");
}

$roleId = $_GET['id'];
$roleQuery = "SELECT Roles_id, Roles_name FROM roles WHERE Roles_id = $roleId";
$roleResult = $conn->query($roleQuery);
$role = $roleResult->fetch_assoc();

$modules = ['Employees', 'Department', 'Attendance_table','Attendance','Roles'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($modules as $module) {
        $update = isset($_POST['update'][$module]) ? 1 : 0;
        $delete = isset($_POST['delete'][$module]) ? 1 : 0;
        $view   = isset($_POST['view'][$module]) ? 1 : 0;
        $add    = isset($_POST['add'][$module]) ? 1 : 0;
        $check = $conn->prepare("SELECT COUNT(*) FROM roles_permission WHERE Roles_id = ? AND Module = ?");
        $check->bind_param("is", $roleId, $module);
        $check->execute();
        $check->bind_result($count);
        $check->fetch();
        $check->close();
        if ($count > 0) {
            $stmt = $conn->prepare("
                UPDATE roles_permission
                SET `Update` = ?, `Delete` = ?, `View` = ?, `Add` = ?
                WHERE Roles_id = ? AND Module = ?
            ");
            $stmt->bind_param("iiiiis", $update, $delete, $view, $add, $roleId, $module);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("
                INSERT INTO roles_permission (Roles_id, Module, `Update`, `Delete`, `View`, `Add`)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isiiii", $roleId, $module, $update, $delete, $view, $add);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<div class='alert alert-success'>Permissions updated successfully for Role: <strong>" . $role['Roles_name'] . "</strong></div>";
}
$permissionsData = [];
$permQuery = "SELECT * FROM roles_permission WHERE Roles_id = $roleId";
$permResult = $conn->query($permQuery);
while ($row = $permResult->fetch_assoc()) {
    $permissionsData[$row['Module']] = $row;
}
?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Manage Permissions for: <?php echo $role['Roles_name']; ?></h1>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Permissions Settings</h5>
        </div>
        <div class="card-body">
            <form method="post">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>View</th>
                            <th>Add</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($modules as $module):
                            $perm = $permissionsData[$module] ?? ['Update'=>0,'Delete'=>0,'View'=>0,'Add'=>0];
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $module; ?></td>
                            <td><input type="checkbox" name="update[<?php echo $module; ?>]" class="form-check-input" <?php echo $perm['Update'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="delete[<?php echo $module; ?>]" class="form-check-input" <?php echo $perm['Delete'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="view[<?php echo $module; ?>]" class="form-check-input" <?php echo $perm['View'] ? 'checked' : ''; ?>></td>
                            <td><input type="checkbox" name="add[<?php echo $module; ?>]" class="form-check-input" <?php echo $perm['Add'] ? 'checked' : ''; ?>></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Save Permissions</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include './include/footer.php'; ?>
