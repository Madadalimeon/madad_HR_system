<?php
include("./config/config.php");
if (isset($_SESSION["Roles_id"])) {
    $roleId = $_SESSION["Roles_id"];
    function getRolePermissions($roleId){
        global $conn;
        $roleId = mysqli_real_escape_string($conn, $roleId);
        $permQuery = $conn->prepare("SELECT Module, `Update`, `Delete`, `View`, `Add` FROM roles_permission WHERE Roles_id = ?"); 
        $permQuery->bind_param("i", $roleId);
        $permQuery->execute();
        $permResult = $permQuery->get_result();
        $permissions = [];
        while ($row = $permResult->fetch_assoc()) {
            $permissions[$row['Module']] = [
                'Update' => $row['Update'],
                'Delete' => $row['Delete'],
                'View' => $row['View'],
                'Add'   => $row['Add']
            ];
        }
        return [
            "role" => $roleId,
            "permissions" => $permissions
        ];
    }
}
?>
