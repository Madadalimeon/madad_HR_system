<?php
include '/XAMPP/htdocs/HR_system/include/header.php';
include './config/config.php';
?>


<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Employee Data Table</h1>
        <a href="add-employee.php" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Add Employee
        </a>
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <?php
                        $sql_employees = "SELECT 
                        employees_id, first_name,last_name,
                        email,mobile_no,dob,date_of_joining,position,Roles_id FROM   employees";
                        $print_data = $conn->query($sql_employees);
                        ?>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>DOB</th>
                            <th>Action</th>
                            <th>Roles_id</th>
                        </tr>
                    </thead>
                    <tbody>
  <?php
                if ($print_data->num_rows > 0) {
                  while ($row = $print_data->fetch_assoc()) {
                ?>
                    <tr>
                      <td><?php echo $row['employees_id']; ?></td>
                      <td><?php echo $row['first_name']; echo $row['last_name']; ?></td>                     
                      <td><?php echo $row['email']; ?></td>
                      <td><?php echo $row['mobile_no']; ?></td>
                      <td><?php echo $row['dob']; ?></td>                                        
                      <td>

                        <a href="update.php?id=<?php echo $row['employees_id']; ?>">
                          <i class="fa-solid fa-pen"></i>
                        </a>


                        <a href="./Backend/delete.php?id=<?php echo $row['employees_id']; ?>">
                          <i class="fa-solid fa-trash "></i>
                        </a>
                      </td>
                      <td><?php echo $row['Roles_id']; ?></td>


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

<?php
include '/XAMPP/htdocs/HR_system/include/footer.php';
?>