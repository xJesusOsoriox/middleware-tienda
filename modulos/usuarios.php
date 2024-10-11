<?php
session_start();
include '../middleware.php';
usarMiddleware('Navegación: Gestión de Usuarios'); // Registrar navegación al módulo
include '../db.php';

// Función para registrar acciones
function registrarAccion($accion) {
    global $conn;
    $sql = "INSERT INTO accesos (ruta, accion) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SERVER['REQUEST_URI'], $accion]);
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        // Registro de nuevo usuario
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

        // Verificar si el correo ya existe
        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$correo]);
        $existeCorreo = $stmt->fetchColumn();

        if ($existeCorreo > 0) {
            // Almacenar mensaje en la sesión
            $_SESSION['mensaje'] = 'El correo ya está registrado.';
        } else {
            $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$nombre, $correo, $contrasena])) {
                registrarAccion("Agregar usuario: $nombre");
                $_SESSION['mensaje'] = 'Usuario agregado exitosamente.';
            }
        }
    } elseif (isset($_POST['eliminar'])) {
        // Eliminación de usuario
        $id = $_POST['id'];
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$id])) {
            registrarAccion("Eliminar usuario: $id");
            $_SESSION['mensaje'] = 'Usuario eliminado exitosamente.';
        }
    } elseif (isset($_POST['editar'])) {
        // Edición de usuario
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];

        // Verificar si el correo ya existe para otro usuario
        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$correo, $id]);
        $existeCorreo = $stmt->fetchColumn();

        if ($existeCorreo > 0) {
            // Almacenar mensaje en la sesión
            $_SESSION['mensaje'] = 'El correo ya está registrado por otro usuario.';
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute([$nombre, $correo, $id])) {
                registrarAccion("Editar usuario: $id");
                $_SESSION['mensaje'] = 'Usuario editado exitosamente.';
            }
        }
    }

    // Redirigir a la misma página después de procesar el formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Obtener usuarios
$sql = "SELECT * FROM usuarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll();

// Mostrar mensaje si existe
if (isset($_SESSION['mensaje'])) {
    echo "<script>alert('{$_SESSION['mensaje']}');</script>";
    unset($_SESSION['mensaje']); // Limpiar mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

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
                    <a class="nav-link"  style="color:red; font-weight:600;" href="usuarios.php">Gestión de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="productos.php">Gestión de Productos</a>
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
    <h2 class="text-center">Gestión de Usuarios</h2>
        <form method="POST" class="mb-3">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" name="agregar" class="btn btn-primary btn-sm">Registrar </button>
        </form>


        <br>
        <h4>Lista de Usuarios</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="30%">Nombre</th>
                    <th width="30%">Correo</th>
                    <th width="30%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id']; ?></td>
                        <td><?= $usuario['nombre']; ?></td>
                        <td><?= $usuario['correo']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $usuario['id']; ?>">
                                <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $usuario['id']; ?>">
                                Editar
                            </button>

                            <!-- Modal para editar usuario -->
                            <div class="modal fade" id="editModal<?= $usuario['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?= $usuario['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="nombreEdit" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="nombreEdit" name="nombre" value="<?= $usuario['nombre']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="correoEdit" class="form-label">Correo</label>
                                                    <input type="email" class="form-control" id="correoEdit" name="correo" value="<?= $usuario['correo']; ?>" required>
                                                </div>
                                                <button type="submit" name="editar" class="btn btn-primary">Actualizar Usuario</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br><br>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
