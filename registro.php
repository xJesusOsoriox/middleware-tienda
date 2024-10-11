<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$correo]);
    $existeCorreo = $stmt->fetchColumn();

    if ($existeCorreo > 0) {
        // Mostrar un mensaje de error si el correo ya está registrado
        echo "<script>alert('El correo ya está registrado.');</script>";
    } else {
        // Proceder a registrar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$nombre, $correo, $contrasena])) {
            // Redirigir al index.php después de un registro exitoso
            header('Location: index.php');
            exit();
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
?>


<style>
    h2{
        text-align:center;
    }
    form{
        width:70%;
        margin:auto;
        display: block;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registro de Usuarios</h2>
        <form method="POST">
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
            <button type="submit" class="btn btn-primary">Registrar</button>
            <a class="btn btn-primary" href="index.php">Iniciar Sesión</a>
        </form>
    </div>
</body>
</html>
