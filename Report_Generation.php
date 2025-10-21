<?php
session_start();
include("./config/config.php");
include("./include/header.php");
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;
$where = "1=1";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $birthday = $_POST['birthday'];
    $time = $_POST['time'];
    if (!empty($birthday)) {
        $where .= " AND a.date = '$birthday'";
    }
    if (!empty($time)) {
        $where .= " AND a.sign_on >= '$time'";
    }
} else {
    $birthday = '';
    $time = '';
}
$count_query = "SELECT COUNT(*) AS total 
                FROM employees e 
                INNER JOIN attendance a 
                ON a.employees_id = e.employees_id 
                WHERE $where";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $records_per_page);
$Report = "SELECT e.employees_id, e.first_name, e.last_name, e.email, e.department, 
                  a.sign_on, a.sign_out, a.date 
           FROM employees e  
           INNER JOIN attendance a 
           ON a.employees_id = e.employees_id 
           WHERE $where 
           ORDER BY a.date DESC 
           LIMIT $offset, $records_per_page";
$Report_Generation = mysqli_query($conn, $Report);
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary"><b>Report Generation</b></h1>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="birthday" class="form-label fw-semibold">Select Date</label>
                        <input type="date" id="birthday" name="birthday" class="form-control"
                               value="<?php echo $birthday; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="time" class="form-label fw-semibold">Select Time</label>
                        <input type="time" id="time" name="time" class="form-control"
                               value="<?php echo $time; ?>">
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold rounded-3">
                            Generate Report
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
<a href="export.php?format=pdf&birthday=<?php echo $birthday; ?>&time=<?php echo $time; ?>" class="btn btn-danger btn-sm me-2">Export PDF</a>
<a href="export.php?format=csv&birthday=<?php echo $birthday; ?>&time=<?php echo $time; ?>" class="btn btn-success btn-sm me-2">Export CSV</a>
<a href="export.php?format=excel&birthday=<?php echo $birthday; ?>&time=<?php echo $time; ?>" class="btn btn-warning btn-sm">Export Excel</a>

            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
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

            <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include("./include/footer.php");
?>
