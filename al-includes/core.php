<?php

$servername = "localhost";
$username = "root";
$password = "root";
$conn = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=u260263254_Eventos", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>