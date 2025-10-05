<?php
include("./config/config.php");
include("./include/header.php");
if (isset($_POST['submit'])) {
    $Roles = $_POST['Roles'];
    $add_Roles_query = "INSERT INTO roles (Roles_name) VALUES ('$Roles')";
    $conn->query($add_Roles_query);
        
}
?>
<form method="post">
    <div class="container mt-5">
        <h2 class="text-center">Add Roles </h2>
        <div class="row mt-5">                        
            <div class="col-12 mb-3">
                <label for="Roles" class="form-label">Roles</label>
                <input type="text" class="form-control" id="Roles" name="Roles" required>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-light btn-block waves-effect waves-light my-4">Add Roles</button>
    </div>
</form>

<?php
include("./include/footer.php");
?>
