<?php
include(__DIR__ . "/../config/config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM employees WHERE employees_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../tables.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
