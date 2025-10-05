<?php
include(__DIR__ . "/../config/config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM dempartment WHERE dempartment_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../table_department.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
