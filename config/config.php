<?php
$servername = "localhost";
$username   = "root";
$pass       = "";
$db         = "admin";

$conn = new mysqli($servername, $username, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
