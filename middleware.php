<?php
// middleware.php
include 'db.php';

function registrarAcceso($ruta, $accion, $conn) {
    $sql = "INSERT INTO accesos (ruta, accion) VALUES (:ruta, :accion)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ruta', $ruta);
    $stmt->bindParam(':accion', $accion);
    $stmt->execute();
}

function AccederMiddleware() {
    global $conn; // Usamos la conexión a la base de datos existente
    $sql = "SELECT * FROM accesos ORDER BY fecha ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(); // Devuelve los registros
}

function AccederMiddleware2() {
    global $conn; // Usamos la conexión a la base de datos existente
    $sql = "SELECT * FROM productos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(); // Devuelve los registros
}

function AccederMiddleware3() {
    global $conn; // Usamos la conexión a la base de datos existente
    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(); // Devuelve los registros
}

function usarMiddleware($accion = null) {
    global $conn;
    $ruta = $_SERVER['REQUEST_URI'];
    registrarAcceso($ruta, $accion, $conn);
}
?>
