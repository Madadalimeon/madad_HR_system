<?php
session_start();
include("./haspermission.php");
$rolePermissions = getRolePermissions($_SESSION['Roles_id']);
$rolesPermissions = $rolePermissions['permissions']['Roles'] ?? [];
if (!isset($rolesPermissions['View']) || $rolesPermissions['View'] != 1) {
    header("Location: ./index.php");
    exit;
}
include("./include/header.php");
include("./config/config.php");
if (!isset($_SESSION['Roles_id'])) {
    die("Please log in first!");
}

?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Roles Table</h1>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3"></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Roles_query = "SELECT Roles_id, Roles_name FROM roles";
                        $Roles_result = $conn->query($Roles_query);

                        if ($Roles_result->num_rows > 0) {
                            while ($row = $Roles_result->fetch_assoc()) {
                                $_SESSION["Roles_name"] = $row["Roles_name"];
                        ?>
                                <tr>
                                    <td><?php echo $row['Roles_id']; ?></td>
                                    <td><?php echo $row['Roles_name']; ?></td>
                                    <td>
                                        <?php if (isset($rolesPermissions['Update']) && $rolesPermissions['Update'] == 1): ?>
                                            <a href="Roles_permission.php?id=<?php echo $row['Roles_id']; ?>" class="btn btn-primary text-white">
                                                Manage Permissions
                                            </a>
                                        <?php else: ?>
                                            <span class="text-danger">No Update Permission</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No roles found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("./include/footer.php"); ?>