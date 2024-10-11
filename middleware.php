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

function usarMiddleware($accion = null) {
    global $conn;
    $ruta = $_SERVER['REQUEST_URI'];
    registrarAcceso($ruta, $accion, $conn);
}
?>
