<?php
session_start();
include("./config/config.php");
include("./include/header.php");
$dayset = '';
$dayend = '';
$employee_id = '';
$where = "1=1";
$e_name = "SELECT employees_id, first_name, last_name FROM employees";
$employees_result = mysqli_query($conn, $e_name);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $dayset = $_POST['Setday'] ?? '';
    $dayend = $_POST['End_day'] ?? '';
    $employee_id = $_POST['employee'] ?? '';



    if (!empty($dayset) && !empty($dayend)) {
        $where .= " AND a.date BETWEEN '$dayset' AND '$dayend'";
    } elseif (!empty($dayset)) {
        $where .= " AND a.date = '$dayset'";
    }

    if (!empty($employee_id)) {
        $where .= " AND e.employees_id = '$employee_id'";
    }
}

$Report = "SELECT e.employees_id, e.first_name, e.last_name, e.email, e.department, 
           a.sign_on, a.sign_out, a.date 
           FROM employees e  
           INNER JOIN attendance a ON a.employees_id = e.employees_id 
           WHERE $where
           ORDER BY a.date DESC";

$Report_Generation = mysqli_query($conn, $Report);
?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Report Generation</h1>
    </div>
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="Setday" class="form-label fw-semibold">Set Date</label>
                        <input type="date" id="Setday" name="Setday" class="form-control" value="<?php echo $dayset; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="End_day" class="form-label fw-semibold">End Date</label>
                        <input type="date" id="End_day" name="End_day" class="form-control" value="<?php echo $dayend; ?>">
                    </div>
                    <div class="col-6 mt-4">
                        <label>Employee</label>
                        <select name="employee" class="form-select">
                            <option value="">Select Employee</option>
                            <?php while ($emp = mysqli_fetch_assoc($employees_result)): ?>
                                <option value="<?php echo $emp['employees_id']; ?>" <?php if ($employee_id == $emp['employees_id']) echo 'selected'; ?>>
                                    <?php echo $emp['first_name'] . ' ' . $emp['last_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold rounded-3">
                            Filter Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-bold text-center fs-5">
            All Attendance Records
        </div>
        <div class="card-body">
            <div class="text-end mb-3">
                <?php

                $qs = http_build_query([
                    'start_date' => $dayset,
                    'end_date' => $dayend,
                    'employee_id' => $employee_id,
                ]);
                ?>
                <a href="PDF.php?<?= $qs; ?>" class="btn btn-danger btn-sm me-2">Export PDF</a>
                <a href="Excel.php?<?= $qs; ?>" class="btn btn-warning btn-sm">Export Excel</a>
            </div>

            <div class="table-responsive">
                <table id="data_table" class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Sign-In</th>
                            <th>Sign-Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($Report_Generation) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($Report_Generation)): ?>
                                <tr>
                                    <td><?php echo $row['employees_id']; ?></td>
                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['department']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['sign_on']; ?></td>
                                    <td><?php echo $row['sign_out']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No attendance records found for selected filters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script>
    $(document).ready(function() {
        $('#data_table').DataTable();
    });
</script>
<?php
include("./include/footer.php");
?>