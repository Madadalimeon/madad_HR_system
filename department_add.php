<?php
session_start();
include("./config/config.php");
include("./include/header.php");
if (isset($_POST['submit'])) {
    $department = $_POST['department'];
    $add_department_query = "INSERT INTO dempartment (dempartment_name) VALUES ('$department')";
    $conn->query($add_department_query);
        echo "<div class='alert alert-success' role='alert'>New Department Add Successfully</div>";
}
?>
<form method="post">
    <div class="container mt-5">
        <h2 class="text-center">Add New Department</h2>
        <div class="row mt-5">                        
            <div class="col-12 mb-3">
                <label for="department" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="department" name="department" required>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-light btn-block waves-effect waves-light my-4">Add Department</button>
    </div>
</form>

<?php
include("./include/footer.php");
?>
