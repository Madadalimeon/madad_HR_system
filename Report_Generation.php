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

<style>
.btn-custom {
    background-color: #4e73df;
    color: white;
    font-weight: 500;
    border-radius: 8px;
    transition: 0.3s;
}
.btn-custom:hover {
    background-color: #375ac2;
}
.table thead th {
    background-color: #2e59d9;
    color: #fff;
}
.card-header {
    background: linear-gradient(90deg, #4e73df, #224abe);
    color: #fff;
}
.card {
    border-radius: 10px;
}

form .form-label {
    margin-bottom: 6px;
}
form .form-control,
form .form-select {
    height: 45px;
    border-radius: 6px;
}
form .row {
    align-items: end;
}
.badge-neutral {
    background-color: #f0f0f0;
    color: #333;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 8px;
}
</style>

<div class="container-fluid my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Report</h1>
        <div>
            <a href="PDF.php?<?= http_build_query([
                'start_date' => $dayset,
                'end_date' => $dayend,
                'employee_id' => $employee_id,
            ]); ?>" 
            class="btn btn-danger btn-sm shadow-sm me-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="Excel.php?<?= http_build_query([
                'start_date' => $dayset,
                'end_date' => $dayend,
                'employee_id' => $employee_id,
            ]); ?>" 
            class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header">
            <h6 class="m-0 fw-bold">Filter Attendance Report</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="Setday" class="form-label fw-semibold">Start Date</label>
                        <input type="date" id="Setday" name="Setday" class="form-control" value="<?php echo $dayset; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="End_day" class="form-label fw-semibold">End Date</label>
                        <input type="date" id="End_day" name="End_day" class="form-control" value="<?php echo $dayend; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="employee" class="form-label fw-semibold">Employee</label>
                        <select name="employee" id="employee" class="form-select">
                            <option value="">All Employees</option>
                            <?php while ($emp = mysqli_fetch_assoc($employees_result)): ?>
                                <option value="<?php echo $emp['employees_id']; ?>" <?php if ($employee_id == $emp['employees_id']) echo 'selected'; ?>>
                                    <?php echo $emp['first_name'] . ' ' . $emp['last_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-custom px-4 py-2 shadow-sm">
                        <i class="fas fa-filter me-2"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header">
            <h6 class="m-0 fw-bold">Attendance Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data_table" class="table table-hover align-middle text-center">
                    <thead>
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
                                <td colspan="7" class="text-center text-muted py-4">No records found for selected filters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    $('#data_table').DataTable({
        pageLength: 10,
        order: [[4, 'desc']]
    });
});
</script>

<?php include("./include/footer.php"); ?>