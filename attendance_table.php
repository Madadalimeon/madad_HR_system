<?php

$inactive = 3600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: ./Backend/logout.php");
    exit;
}
$_SESSION['last_activity'] = time();


session_start();
include './config/config.php';
include './include/header.php';
if (!isset($_SESSION['Roles_id'])) {
    die("Please log in first!");
}
$attendancePermissions = $rolePermissions['permissions']['Attendance'] ?? [];
if (!isset($attendancePermissions['View']) || $attendancePermissions['View'] != 1) {
    header("Location: ./index.php");
    exit;
}



?>
<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Attendance Report</h1> 
    </div>
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3"> 
            <h6 class="m-0 font-weight-bold">Employee Attendance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Employee ID</th>
                            <th>Full Name</th>
                            <th>Sign In</th>
                            <th>Sign Out</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $att_SQL = "SELECT 
                        attendance.id,  attendance.employees_id, 
                        employees.first_name,employees.last_name,
                        attendance.sign_on,attendance.sign_out,attendance.date 
                        FROM attendance INNER JOIN employees 
                        ON attendance.employees_id = employees.employees_id
                       ORDER BY attendance.date DESC, attendance.sign_on DESC";

                        $print_data = $conn->query($att_SQL);

                        if ($print_data && $print_data->num_rows > 0) {
                            while ($row = $print_data->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['employees_id']; ?></td>
                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?php echo $row['sign_on']; ?></td>
                                    <td><?php echo $row['sign_out'] ? $row['sign_out'] : '---'; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No attendance records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include './include/footer.php';
?>
