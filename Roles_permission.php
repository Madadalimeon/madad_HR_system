<?php
include '/XAMPP/htdocs/HR_system/include/header.php';
include './config/config.php';
$sql = "SELECT Roles_id, Roles_name FROM roles";
$rolesResult = $conn->query($sql);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $rolesPermissions = [];
    while ($role = $rolesResult->fetch_assoc()) {
        $id = $role['Roles_id'];
        $rolesPermissions[$id] = [
            'Roles_name' => $role['Roles_name'],
            'Update' => isset($_POST['update'][$id]) ? 1 : 0,
            'Delete' => isset($_POST['delete'][$id]) ? 1 : 0,
            'View'   => isset($_POST['view'][$id]) ? 1 : 0,
            'Add'    => isset($_POST['add'][$id]) ? 1 : 0,
        ];

    }
    $stmt = $conn->prepare("INSERT INTO roles_permission (Name, Roles_id, `Update`, `Delete`, `View`, `Add`) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($rolesPermissions as $role_id => $perms) {
        $stmt->bind_param(
            "siiiii",
            $perms['Roles_name'],
            $role_id,
            $perms['Update'],
            $perms['Delete'],
            $perms['View'],
            $perms['Add']
        );
        $stmt->execute();
    }
    $stmt->close();
    echo "<div class='alert alert-success'>Permissions saved successfully!</div>";
    
    function checkRoleUpdate($perms)
    {
        if (isset($perms['Roles_id']) && $perms['Roles_id'] == 1) {
            if (isset($perms['Update']) && ($perms['Update'] == 1 || $perms['Update'] == 0)) {
                echo "ok";
            } else {
                echo "no";
            }
        } else {
            echo "Role ID is not 1";
        }
    }
    checkRoleUpdate($role);
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
                <form id="rolesForm" method="post">
                    <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Update</th>
                                <th>Delete</th>
                                <th>View</th>
                                <th>Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $rolesResult->data_seek(0);
                            while ($role = $rolesResult->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $role['Roles_id']; ?></td>
                                    <td><?= $role['Roles_name']; ?></td>
                                    <td><input type='checkbox' name='update[<?= $role['Roles_id']; ?>]' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='delete[<?= $role['Roles_id']; ?>]' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='view[<?= $role['Roles_id']; ?>]' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='add[<?= $role['Roles_id']; ?>]' class='perm-checkbox'></td>
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

<script>
    function JSONPreview() {
        const checkboxes = document.querySelectorAll('.perm-checkbox');
        const roles = {};

        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            const id = row.cells[0].innerText.trim();

            if (!roles[id]) {
                roles[id] = {
                    ID: parseInt(id),
                    Update: 0,
                    Delete: 0,
                    View: 0,
                    Add: 0
                };
            }

            if (cb.name.startsWith('update')) roles[id].Update = cb.checked ? 1 : 0;
            if (cb.name.startsWith('delete')) roles[id].Delete = cb.checked ? 1 : 0;
            if (cb.name.startsWith('view')) roles[id].View = cb.checked ? 1 : 0;
            if (cb.name.startsWith('add')) roles[id].Add = cb.checked ? 1 : 0;
        });

        const rolesArray = Object.values(roles);
        document.getElementById('jsonPreview').textContent = JSON.stringify(rolesArray, null, 4);
    }


    document.querySelectorAll('.perm-checkbox').forEach(cb => cb.addEventListener('change', JSONPreview));


    JSONPreview();
</script>

<?php
include '/XAMPP/htdocs/HR_system/include/footer.php';
?>