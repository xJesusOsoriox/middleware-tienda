<?php
include '../db.php';

// Consulta para obtener los registros de accesos y acciones, ordenados por fecha
$sql = "SELECT * FROM accesos ORDER BY fecha ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$accesos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Información</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>

    table thead tr th{
        text-align:center;
    }

    table tbody td {
        text-align:center;
    }

</style>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Registro de Navegación y Acciones</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ruta</th>
                    <th>Acción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accesos as $acceso): ?>
                    <tr>
                        <td><?= $acceso['id']; ?></td>
                        <td><?= $acceso['ruta']; ?></td>
                        <td><?= $acceso['accion']; ?></td>
                        <td><?= $acceso['fecha']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
