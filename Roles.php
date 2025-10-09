<?php
session_start();
include("./include/header.php");
include("./config/config.php");
?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Roles Table</h1>
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <?php
                        $Roles_employees = "SELECT Roles_id ,Roles_name FROM roles";
                        $Roles_printe = $conn->query($Roles_employees);
                        ?>
                        <tr>
                            <th>ID</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($Roles_printe->num_rows > 0) {
                            while ($row = $Roles_printe->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row['Roles_id']; ?></td>
                                    <td><?php echo $row['Roles_name']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary">                                            
                                            <a class="text-white" href="Roles_permission.php?id=<?php echo $row['Roles_id']; ?>">Roles_permission</a>
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='15' class='text-center'>No employees found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("./include/footer.php") ?>
