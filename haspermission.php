
<?php
include("./config/config.php");
if (isset($_SESSION["Roles_id"])) {
    $roleId = $_SESSION["Roles_id"];
    function getRolePermissions($roleId) {
        global $conn;
        $roleQuery = $conn->prepare("SELECT Roles_id, Roles_name FROM roles WHERE Roles_id = ?");
        $roleQuery->bind_param("i", $roleId);
        $roleQuery->execute();
        $roleResult = $roleQuery->get_result();
        if ($roleResult->num_rows == 0) {
                    return ["error" => "This role does not exist"];
        }
        $role = $roleResult->fetch_assoc();
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
            "role" => $role,
            "permissions" => $permissions
        ];
    }
}
?>