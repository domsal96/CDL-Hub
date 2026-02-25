<?php
error_reporting(0);
ini_set('display_errors', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
$host = "sql308.infinityfree.com";
$user = "if0_41199093";
$password = "aUYPmDUW3z";
$dbname = "if0_41199093_cdl_hub";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

