<?php

$servername = "srv-pleskdb21.ps.kz:3306";
$username = "altyn_bilim";
$password = "glkR283*";
$dbname = "altynbil_db";

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "altyn_bilim";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "TRUNCATE TABLE review";
if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>