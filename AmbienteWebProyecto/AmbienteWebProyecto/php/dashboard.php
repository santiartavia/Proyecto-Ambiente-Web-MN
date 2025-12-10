<?php
// php/dashboard.php

require_once "init.php";
require_login();

$usuario = $_SESSION['usuario'];

// $pdo viene de conexion.php incluído en init.php

// ==== Estadísticas generales ====
try {
    // Total de usuarios
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    $totalUsuarios = (int)$stmt->fetchColumn();

    // Total de citas
    $stmt = $pdo->query("SELECT COUNT(*) FROM citas");
    $totalCitas = (int)$stmt->fetchColumn();

    // Citas pendientes
    $stmt = $pdo->query("SELECT COUNT(*) FROM citas WHERE estado = 'Pendiente'");
    $citasPendientes = (int)$stmt->fetchColumn();

    // Mensajes de soporte
    $stmt = $pdo->query("SELECT COUNT(*) FROM soporte");
    $totalSoporte = (int)$stmt->fetchColumn();

    // Últimas 5 citas (las más recientes primero)
    $stmt = $pdo->query("
        SELECT paciente, doctor, fecha, hora, motivo, estado
        FROM citas
        ORDER BY fecha DESC, hora DESC
        LIMIT 5
    ");
    $ultimasCitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si algo pasa con la BD, no matamos la página, solo mostramos un mensaje simple
    $errorBD = "Error al obtener datos: " . $e->getMessage();
    $totalUsuarios = $totalCitas = $citasPendientes = $totalSoporte = 0;
    $ultimasCitas = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel MediConnect</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tu estilo -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-moss">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-moss-deep shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.html">
            <i class="bi bi-heart-pulse me-1"></i>MediConnect
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navTop">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="navTop" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item me-3 text-white small">
                    <span class="d-block fw-semibold">
                        <?= htmlspecialchars($usuario['nombre']) ?>
                    </span>
                    <span class="opacity-75">
                        Rol: <?= htmlspecialchars($usuario['rol']) ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?> 👋
        </h3>
        <p class="text-muted mb-0">
            Has iniciado sesión con el rol: <strong><?= htmlspecialchars($usuario['rol']) ?></strong>.
        </p>
    </div>

    <?php if (!empty($errorBD)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($errorBD) ?>
        </div>
    <?php endif; ?>

    <!-- TARJETAS DE RESUMEN -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Usuarios</h6>
                            <h3 class="fw-bold mb-0"><?= $totalUsuarios ?></h3>
                        </div>
                        <div class="rounded-circle bg-cream p-2">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        Pacientes, doctores y administradores registrados.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Citas totales</h6>
                            <h3 class="fw-bold mb-0"><?= $totalCitas ?></h3>
                        </div>
                        <div class="rounded-circle bg-cream p-2">
                            <i class="bi bi-calendar2-week fs-4 text-success"></i>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        Todas las citas registradas en el sistema.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Citas pendientes</h6>
                            <h3 class="fw-bold mb-0"><?= $citasPendientes ?></h3>
                        </div>
                        <div class="rounded-circle bg-cream p-2">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        A la espera de confirmación o atención.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Mensajes soporte</h6>
                            <h3 class="fw-bold mb-0"><?= $totalSoporte ?></h3>
                        </div>
                        <div class="rounded-circle bg-cream p-2">
                            <i class="bi bi-life-preserver fs-4 text-danger"></i>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">
                        Consultas enviadas por los usuarios.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- ACCESOS RÁPIDOS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">
                        <i class="bi bi-calendar-plus me-1"></i> Gestión de citas
                    </h5>
                    <p class="small text-muted">
                        Registra nuevas citas, consulta el historial y administra estados.
                    </p>
                    <!-- Estos enlaces van a tus scripts PHP que ya tienes -->
                    <a href="citas_listar.php" class="btn btn-moss btn-sm">Ver citas</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">
                        <i class="bi bi-person-lines-fill me-1"></i> Usuarios
                    </h5>
                    <p class="small text-muted">
                        Administra pacientes, doctores y administradores del sistema.
                    </p>
                    <a href="usuarios_listar.php" class="btn btn-moss btn-sm">Ver usuarios</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-2">
                        <i class="bi bi-chat-dots me-1"></i> Soporte
                    </h5>
                    <p class="small text-muted">
                        Revisa los mensajes de soporte y da seguimiento a incidentes.
                    </p>
                    <!-- Si luego haces una página soporte_listar.php, apúntala aquí -->
                    <!-- <a href="soporte_listar.php" class="btn btn-moss btn-sm">Ver soporte</a> -->
                    <button class="btn btn-moss btn-sm" disabled>Pendiente implementar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ÚLTIMAS CITAS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-cream fw-semibold">
            <i class="bi bi-clock-history me-1"></i> Últimas citas registradas
        </div>
        <div class="card-body">

            <?php if (empty($ultimasCitas)): ?>
                <p class="text-muted mb-0">Aún no hay citas registradas.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ultimasCitas as $cita): ?>
                            <tr>
                                <td><?= htmlspecialchars($cita['paciente']) ?></td>
                                <td><?= htmlspecialchars($cita['doctor']) ?></td>
                                <td><?= htmlspecialchars($cita['fecha']) ?></td>
                                <td><?= htmlspecialchars(substr($cita['hora'], 0, 5)) ?></td>
                                <td><?= htmlspecialchars($cita['motivo']) ?></td>
                                <td>
                                    <?php
                                    $badge = 'secondary';
                                    if ($cita['estado'] === 'Pendiente') $badge = 'warning';
                                    if ($cita['estado'] === 'Confirmada') $badge = 'success';
                                    if ($cita['estado'] === 'Cancelada') $badge = 'danger';
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= htmlspecialchars($cita['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
