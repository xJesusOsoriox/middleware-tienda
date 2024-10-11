<?php
// db.php

$servername = "localhost";
$username = "Mayorga";
$password = "Ola123x.";
$dbname = "middleware_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>
