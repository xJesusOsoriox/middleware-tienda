<?php
include '../middleware.php';
usarMiddleware('Navegación: Gestión de Productos'); // Registrar navegación al módulo
include '../db.php';

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion']; // Puede ser 'Agregar', 'Editar', 'Eliminar'
    $id = $_POST['id'] ?? null; // Obtener ID si existe
    $nombre = $_POST['nombre'] ?? null; // Obtener nombre si existe
    usarMiddleware($accion . ($id ? " ID: $id" : "") . ($nombre ? " $nombre" : "")); // Registrar acción en el middleware

    if ($accion === 'Agregar producto') {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $sql = "INSERT INTO productos (nombre, precio) VALUES (:nombre, :precio)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
    }

    if ($accion === 'Editar producto') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $sql = "UPDATE productos SET nombre = :nombre, precio = :precio WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();
    }

    if ($accion === 'Eliminar producto') {
        $id = $_POST['id'];
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Redirigir a la misma página para evitar el reenvío de datos
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // Asegúrate de salir después de redirigir
}

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$stmt = $conn->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll();
?>

<style>
   a.nav-link:hover{
        color:red;
        font-weight:600;
    }

    a.nav-link{
        font-weight:600;
    }

    button{
        width: 130px;
        height:38px;
    }

    table thead tr th{
        text-align:center;
    }

    table tbody td {
        text-align:center;
    }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Menú horizontal con Cierre de Sesión -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="../admin.php"><i class="fa fa-home" style="font-size:24px; margin-right:10px;"></i>TIENDA A&J
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link r" href="usuarios.php">Gestión de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:red; font-weight:600;" href="productos.php">Gestión de Productos</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Cerrar Sesión</a> <!-- Botón de Cerrar Sesión -->
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Gestión de Productos</h2>

    <!-- Formulario para agregar productos -->
    <form method="POST" class="mb-4" id="formAgregar">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" required>
        </div>
        <input type="hidden" name="accion" value="Agregar producto" id="accionAgregar">
        <button type="submit" class="btn btn-primary btn-sm">Registrar </button>
    </form>


    <br>
    <h4>Lista de Productos</h4>
        <!-- Tabla para mostrar productos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="40%">Nombre</th>
                <th width="20%">Precio</th>
                <th width="30%">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id']; ?></td>
                    <td><?= $producto['nombre']; ?></td>
                    <td><?= $producto['precio']; ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" 
                                data-id="<?= $producto['id']; ?>" 
                                data-nombre="<?= $producto['nombre']; ?>" 
                                data-precio="<?= $producto['precio']; ?>">
                            Editar
                        </button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $producto['id']; ?>">
                            <input type="hidden" name="accion" value="Eliminar producto">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br><br>
</div>

<!-- Modal para editar productos -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formEditar">
                    <div class="mb-3">
                        <label for="nombreEditar" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombreEditar" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="precioEditar" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="precioEditar" name="precio" required>
                    </div>
                    <input type="hidden" name="id" id="idEditar">
                    <input type="hidden" name="accion" value="Editar producto">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Escuchar el evento de apertura del modal para llenar los campos
    var myModal = document.getElementById('modalEditar');
    myModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Botón que activó el modal

        // Extraer la información de los atributos de datos del botón
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var precio = button.getAttribute('data-precio');

        // Rellenar los campos en el formulario
        var idEditar = myModal.querySelector('#idEditar');
        var nombreEditar = myModal.querySelector('#nombreEditar');
        var precioEditar = myModal.querySelector('#precioEditar');

        idEditar.value = id;
        nombreEditar.value = nombre;
        precioEditar.value = precio;
    });
</script>

</body>
</html>

