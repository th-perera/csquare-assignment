<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "erp_db";

$conn = mysqli_connect("$host", "$username", "$password", "$database");

if(!$conn) {
    header("Location: error/dberror.php");
    die();
}