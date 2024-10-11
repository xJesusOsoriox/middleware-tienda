<?php
include 'middleware.php';
usarMiddleware('Navegación: Panel Administrador'); // Registrar navegación al módulo
?>

<style>
   a.list-group-item:hover{
        color:red;
        font-weight:600;
    }

    a.list-group-item{
        font-weight:600;
    }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- Menú horizontal con Cierre de Sesión -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php"><i class="fa fa-home" style="font-size:24px; margin-right:10px;"></i>TIENDA A&J
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a> <!-- Botón de Cerrar Sesión -->
                </li>
            </ul>
        </div>
    </div>
</nav>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Panel de Administración</h2>
        <div class="list-group">
            <a href="modulos/usuarios.php" class="list-group-item list-group-item-action">Gestión de Usuarios</a>
            <a href="modulos/productos.php" class="list-group-item list-group-item-action">Gestión de Productos</a>
<br>
            <img src="img/tienda-online.png" style="width:450px; margin:auto; display:block;" alt="">
        </div>
    </div>
</body>
</html>
