<?php
session_start();
include("./config/config.php");
include("./include/header.php");
if (!isset($_SESSION['employees_id'])) {
    die("Please log in first!");
}
$e_id = $_SESSION['employees_id'];
$Report = "SELECT e.employees_id, e.first_name, e.last_name, e.email, e.department, 
           a.sign_on, a.sign_out, a.date 
           FROM employees e 
           INNER JOIN attendance a 
           ON a.employees_id = e.employees_id 
           WHERE e.employees_id = '$e_id'
           ORDER BY a.date DESC ";
$Report_Generation = mysqli_query($conn, $Report);
print_r($Report_Generation);
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
                        <label for="birthday" class="form-label fw-semibold">Select Date</label>
                        <input type="date" id="birthday" name="birthday" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="appt" class="form-label fw-semibold">Select Time</label>
                        <input type="time" id="appt" name="appt" class="form-control" required>
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
            Generated Reports
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Sign-In</th>
                            <th>Sign-Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($Report_Generation) > 0): ?>
                            <?php while ($Report_data = mysqli_fetch_assoc($Report_Generation)): ?>
                                <tr>
                                    <td><?php echo $Report_data['employees_id']; ?></td>
                                    <td><?php echo $Report_data['first_name'] . ' ' . $Report_data['last_name']; ?></td>
                                    <td><?php echo $Report_data['department']; ?></td>
                                    <td><?php echo $Report_data['date']; ?></td>
                                    <td><?php echo $Report_data['sign_on']; ?></td>
                                    <td><?php echo $Report_data['sign_out']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No attendance records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include("./include/footer.php");
?>
