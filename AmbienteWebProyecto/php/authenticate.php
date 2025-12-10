<?php
require_once "init.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$correo   = trim($_POST['correo']   ?? '');
$password = trim($_POST['password'] ?? '');

if ($correo === '' || $password === '') {
    header("Location: login.php?error=" . urlencode("Correo y contraseña son requeridos."));
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo LIMIT 1");
    $stmt->execute([':correo' => $correo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: login.php?error=" . urlencode("Usuario no encontrado."));
        exit;
    }

    if ($password !== $user['password']) {
        header("Location: login.php?error=" . urlencode("Contraseña incorrecta."));
        exit;
    }

    $_SESSION['usuario'] = [
        'id'     => $user['id'],
        'nombre' => $user['nombre'],
        'correo' => $user['correo'],
        'rol'    => $user['rol']
    ];

    header("Location: dashboard.php");
    exit;

} catch (Exception $e) {
    header("Location: login.php?error=" . urlencode("Error al iniciar sesión."));
    exit;
}
