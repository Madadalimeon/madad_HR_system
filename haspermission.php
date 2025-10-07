<?php
session_start();
include("./config/config.php");
function hasPermission() {
    global $conn;
    if (!isset($_SESSION['Roles_id'])) {
        return;
    }

    $roleId = $_SESSION['Roles_id'];
    $sql = "SELECT `Update`, `Delete`, `View`, `Add` FROM roles_permission WHERE Roles_id = $roleId LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['role_permissions'] = [
            'update' => (int)$row['Update'],
            'delete' => (int)$row['Delete'],
            'view'   => (int)$row['View'],
            'add'    => (int)$row['Add'],
        ];
    } else {
        $_SESSION['role_permissions'] = [
            'update' => 0,
            'delete' => 0,
            'view'   => 0,
            'add'    => 0,
        ];
    }
}
?>
