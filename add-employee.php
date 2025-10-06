<?php
include './include/header.php';
include './config/config.php';



if (isset($_POST['submit'])) {
    $first_name       = $_POST["first_name"];
    $last_name        = $_POST["last_name"];
    $email            = $_POST["email"];
    $mobile_no        = $_POST["mobile_no"];
    $dob              = $_POST["dob"];
    $date_of_joining  = $_POST['date_of_joining'];
    $position         = $_POST["position"];
    $department       = $_POST["department"];
    $shift            = $_POST["shift"];
    $role             = $_POST["role"];
    $country          = $_POST["country"];
    $state            = $_POST["state"];
    $city             = $_POST["city"];
    $address          = $_POST["address"];
    $username         = $_POST["username"];
    $password         = md5($_POST["password"]);
    $role_table       = $_POST["role_table"];

    
    $file_name = "";
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp  = $_FILES['profile_pic']['tmp_name'];

        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($file_tmp, "uploads/" . $file_name);
    }

    
    $add_query = "INSERT INTO employees 
    (first_name,last_name,email,mobile_no,dob,date_of_joining,position,department,shift,role,country,state,city,address,profile_pic,Roles_id) 
    VALUES 
    ('$first_name','$last_name','$email','$mobile_no','$dob','$date_of_joining','$position','$department','$shift','$role','$country','$state',
    '$city','$address','$file_name','$role_table')";

    if ($conn->query($add_query)) {
        $employee_id = $conn->insert_id;

        $sql = "INSERT INTO login_credentials (username, password, employees_id) VALUES ('$username', '$password', '$employee_id')";
        $conn->query($sql);

        $success_message = "New employee <strong>$first_name $last_name</strong> has been successfully added!";
    }
}

$S_department = "SELECT dempartment_id ,dempartment_name FROM dempartment";
$result = $conn->query($S_department);

$S_roles = "SELECT Roles_id, Roles_name FROM roles";
$roles_result = $conn->query($S_roles);
?>

<div class="container mt-5">
    <form method="post" enctype="multipart/form-data">
        <h2 class="text-center mb-4">Add New Employee</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="fname" name="first_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="lname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lname" name="last_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="mobile" class="form-label">Mobile No</label>
                <input type="tel" class="form-control" id="mobile" name="mobile_no">
            </div>
            <div class="col-md-6 mb-3">
                <label for="dob" class="form-label">DOB</label>
                <input type="date" class="form-control" id="dob" name="dob">
            </div>
            <div class="col-md-6 mb-3">
                <label for="doj" class="form-label">Date of Joining</label>
                <input type="date" class="form-control" id="doj" name="date_of_joining">
            </div>
            <div class="col-md-6 mb-3">
                <label for="position" class="form-label">Position</label>
                <input type="text" class="form-control" id="position" name="position">
            </div>
            <div class="col-md-6 mb-3">
                <label for="department" class="form-label">Choose Department</label>
                <select name="department" class="form-select">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['dempartment_name']; ?>">
                            <?php echo $row['dempartment_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="role_table" class="form-label">Choose Role</label>
                <select name="role_table" class="form-select">
                    <?php while ($role_row = $roles_result->fetch_assoc()) { ?>
                        <option value="<?php echo $role_row['Roles_id']; ?>">
                            <?php echo $role_row['Roles_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="shift" class="form-label">Choose Shift</label>
                <input type="text" class="form-control" id="shift" name="shift">
            </div>
            <div class="col-md-6 mb-3">
                <label for="role" class="form-label">Assign Role</label>
                <input type="text" class="form-control" id="role" name="role">
            </div>
            <div class="col-md-6 mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country">
            </div>
            <div class="col-md-6 mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state">
            </div>
            <div class="col-md-6 mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" rows="3" name="address"></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <label for="pic" class="form-label">Profile Pic</label>
                <input type="file" class="form-control" id="pic" name="profile_pic">
            </div>
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-light btn-block waves-effect waves-light my-4">Add New Employee</button>
    </form>
</div>

<?php
$conn->close();
include './include/footer.php';
?>
