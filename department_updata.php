<?php
include("./config/config.php");
include("./include/header.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM dempartment WHERE dempartment_id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $department = $result->fetch_assoc();
    } else {
        echo "Department not found!";
        exit;
    }
}

if (isset($_POST['update'])) {
    $department_name = $_POST["department"]; 
    $update_query = "UPDATE dempartment SET dempartment_name = '$department_name' WHERE dempartment_id = $id";
    if ($conn->query($update_query) === TRUE) {
        echo "<script>window.location.href = 'table_department.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- Update Department Form -->

<form method="post">
    <div class="container mt-5">
        <h2 class="text-center">Update Department</h2>
        <div class="row mt-5">
            <div class="col-12 mb-3">
                <label for="department">Department Name</label>
                <input type="text" class="form-control" id="department" name="department" 
                       value="<?= $department['department_name'] ?>" required>
            </div>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Department</button>
    </div>
</form>
<?php include("./include/footer.php"); ?>
<?php include("./include/footer.php"); ?>