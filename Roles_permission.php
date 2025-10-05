<?php
include '/XAMPP/htdocs/HR_system/include/header.php';
include './config/config.php';
$sql = "SELECT Roles_id, Roles_name FROM roles";
$result = $conn->query($sql);
?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Table Role </h1>
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3"></div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="rolesForm" action="process_roles.php" method="post">
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
                            <?php while($role = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $role['Roles_id']; ?></td>
                                    <td><?php echo $role['Roles_name']; ?></td>
                                    <td><input type='checkbox' name='update[]' value='<?php echo $role['Roles_id']; ?>' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='delete[]' value='<?php echo $role['Roles_id']; ?>' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='view[]' value='<?php echo $role['Roles_id']; ?>' class='perm-checkbox'></td>
                                    <td><input type='checkbox' name='add[]' value='<?php echo $role['Roles_id']; ?>' class='perm-checkbox'></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <h4>JSON Preview:</h4>
    <pre id="jsonPreview" style="background:#f8f9fa; padding:10px; border:1px solid #ccc; border-radius:4px;"></pre>
</div>
<script>
const checkboxes = document.querySelectorAll('.perm-checkbox');
const jsonPreview = document.getElementById('jsonPreview');
function JSONPreview() {
    const roles = {};
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        const id = row.cells[0].innerText.trim();
        if (!roles[id]) {
            roles[id] = { ID: parseInt(id), Update: 0, Delete: 0, View: 0, Add: 0 };
        }
        if (cb.name === 'update[]') roles[id].Update = cb.checked ? 1 : 0;
        if (cb.name === 'delete[]') roles[id].Delete = cb.checked ? 1 : 0;
        if (cb.name === 'view[]') roles[id].View = cb.checked ? 1 : 0;
        if (cb.name === 'add[]') roles[id].Add = cb.checked ? 1 : 0;
    });
    const rolesArray = Object.values(roles);
    jsonPreview.textContent = JSON.stringify(rolesArray, null, 4);
}
checkboxes.forEach(cb => cb.addEventListener('change', JSONPreview));
JSONPreview();
</script>
<?php
include '/XAMPP/htdocs/HR_system/include/footer.php';
?>
