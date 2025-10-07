<?php
session_start();
include './config/config.php';
include './include/header.php';
if (!isset($_GET['id'])) {
    die("<div class='alert alert-danger'>Role ID not provided in URL!</div>");
}

$roleId = intval($_GET['id']);
$roleQuery = "SELECT Roles_id, Roles_name FROM roles WHERE Roles_id = $roleId";
$roleResult = $conn->query($roleQuery);
$role = $roleResult->fetch_assoc();

if (!$role) {
    die("<div class='alert alert-danger'>Role not found!</div>");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update = isset($_POST['update']) ? 1 : 0;
    $delete = isset($_POST['delete']) ? 1 : 0;
    $view   = isset($_POST['view']) ? 1 : 0;
    $add    = isset($_POST['add']) ? 1 : 0;

    $_SESSION['role_permissions'] = [
        'role_id' => $roleId,
        'update'  => $update,
        'delete'  => $delete,
        'view'    => $view,
        'add'     => $add
    ];

    $stmt = $conn->prepare("
        INSERT INTO roles_permission (`Roles_id`, `Update`, `Delete`, `View`, `Add`)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            `Update` = VALUES(`Update`),
            `Delete` = VALUES(`Delete`),
            `View`   = VALUES(`View`),
            `Add`    = VALUES(`Add`)
    ");
    $stmt->bind_param("iiiii", $roleId, $update, $delete, $view, $add);
    $stmt->execute();
    $stmt->close();

    echo "<div class='alert alert-success'>Permissions updated successfully for Role: <strong>" . htmlspecialchars($role['Roles_name']) . "</strong></div>";
}
if (
    isset($_SESSION['role_permissions']) &&
    isset($_SESSION['role_permissions']['role_id']) &&
    $_SESSION['role_permissions']['role_id'] == $roleId
) {
    $permissions = [
        'Update' => $_SESSION['role_permissions']['update'] ?? 0,
        'Delete' => $_SESSION['role_permissions']['delete'] ?? 0,
        'View'   => $_SESSION['role_permissions']['view'] ?? 0,
        'Add'    => $_SESSION['role_permissions']['add'] ?? 0,
    ];
} else {
    $permQuery = "SELECT * FROM roles_permission WHERE Roles_id = $roleId";
    $permResult = $conn->query($permQuery);
    $permissions = $permResult->fetch_assoc() ?? ['Update' => 0, 'Delete' => 0, 'View' => 0, 'Add' => 0];

    $_SESSION['role_permissions'] = [
        'role_id' => $roleId,
        'update'  => $permissions['Update'],
        'delete'  => $permissions['Delete'],
        'view'    => $permissions['View'],
        'add'     => $permissions['Add']
    ];
}
?>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            Manage Permissions for: <?php echo htmlspecialchars($role['Roles_name']); ?>
        </h1>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">Permissions Settings</h5>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-check">
                    <input type="checkbox" name="update" class="form-check-input" id="updateCheck"
                        <?php echo $permissions['Update'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="updateCheck">Update</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="delete" class="form-check-input" id="deleteCheck"
                        <?php echo $permissions['Delete'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="deleteCheck">Delete</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="view" class="form-check-input" id="viewCheck"
                        <?php echo $permissions['View'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="viewCheck">View</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="add" class="form-check-input" id="addCheck"
                        <?php echo $permissions['Add'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="addCheck">Add</label>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
            </form>
        </div>
    </div>
</div>

<?php include './include/footer.php'; ?>
