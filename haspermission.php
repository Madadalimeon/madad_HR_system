<?php
include("./config/config.php");
function hasPermission() {
    global $conn;
    if (!isset($_SESSION['Roles_id'])) {
        echo "No role assigned.";
        return;
    }
    $roleId = $_SESSION['Roles_id'];
    $sql = "SELECT Roles_id, Module, `Update`, `Delete`, `View`, `Add` 
            FROM roles_permission 
            WHERE Roles_id = $roleId 
            LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $permissions = $result->fetch_assoc();
        print_r($permissions);
    } else {
        echo "No permissions found for this role.";
    }
}
?>
