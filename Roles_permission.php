<?php
include '/XAMPP/htdocs/HR_system/include/header.php';
include './config/config.php';
$rolesQuery = "SELECT Roles_id, Roles_name FROM roles";
$rolesResult = $conn->query($rolesQuery);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $rolesResult->data_seek(0);
    while ($role = $rolesResult->fetch_assoc()) {
        $id = $role['Roles_id'];
        $update = isset($_POST['update'][$id]) ? 1 : 0;
        $delete = isset($_POST['delete'][$id]) ? 1 : 0;
        $view   = isset($_POST['view'][$id]) ? 1 : 0;
        $add    = isset($_POST['add'][$id]) ? 1 : 0;
        $stmt = $conn->prepare("
            INSERT INTO roles_permission (Roles_id, `Update`, `Delete`, `View`, `Add`)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                `Update` = VALUES(`Update`),
                `Delete` = VALUES(`Delete`),
                `View`   = VALUES(`View`),
                `Add`    = VALUES(`Add`)
        ");
        $stmt->bind_param("iiiii", $id, $update, $delete, $view, $add);
        $stmt->execute();
        $stmt->close();
    }
    echo "<div class='alert alert-success'>Permissions updated successfully!</div>";
}
$permissionsData = [];
$permQuery = "SELECT * FROM roles_permission";
$permResult = $conn->query($permQuery);
while ($perm = $permResult->fetch_assoc()) {
    $permissionsData[$perm['Roles_id']] = $perm;
}
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Roles Permissions</h1>
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Manage Role Permissions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="post">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Update</th>
                                <th>Delete</th>
                                <th>View</th>
                                <th>Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rolesResult->data_seek(0);
                            while ($role = $rolesResult->fetch_assoc()) {
                                $id = $role['Roles_id'];
                                $perm = $permissionsData[$id] ?? ['Update' => 0, 'Delete' => 0, 'View' => 0, 'Add' => 0];
                            ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= htmlspecialchars($role['Roles_name']) ?></td>
                                    <td>
                                        <input type="checkbox" name="update[<?= $id ?>]" class="form-check-input"
                                            <?= $perm['Update'] == 1 ? 'checked' : '' ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="delete[<?= $id ?>]" class="form-check-input"
                                            <?= $perm['Delete'] == 1 ? 'checked' : '' ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="view[<?= $id ?>]" class="form-check-input"
                                            <?= $perm['View'] == 1 ? 'checked' : '' ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="add[<?= $id ?>]" class="form-check-input"
                                            <?= $perm['Add'] == 1 ? 'checked' : '' ?>>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '/XAMPP/htdocs/HR_system/include/footer.php';
?>
