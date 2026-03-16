<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titulo) ? $titulo . ' - ' : ''; ?>SGI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body<?php 
    if (isset($_SESSION['mensaje'])) {
        echo ' data-mensaje="' . htmlspecialchars($_SESSION['mensaje']) . '"';
        unset($_SESSION['mensaje']);
    }
    if (isset($_SESSION['error'])) {
        echo ' data-error="' . htmlspecialchars($_SESSION['error']) . '"';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['advertencia'])) {
        echo ' data-advertencia="' . htmlspecialchars($_SESSION['advertencia']) . '"';
        unset($_SESSION['advertencia']);
    }
    if (isset($_SESSION['info'])) {
        echo ' data-info="' . htmlspecialchars($_SESSION['info']) . '"';
        unset($_SESSION['info']);
    }
?>>
    <nav class="navbar">
        <div class="navbar-brand"><i class="bi bi-box-seam"></i> SGI</div>
        <div class="navbar-menu">
            <a href="index.php?modulo=dashboard&accion=index"><i class="bi bi-house-door"></i> <b>Inicio</b></a>
            <a href="index.php?modulo=clientes&accion=lista"><i class="bi bi-people"></i> <b>Clientes</b></a>
            <a href="index.php?modulo=usuarios&accion=lista"><i class="bi bi-person-badge"></i> <b>Usuarios</b></a>
            <a href="index.php?modulo=proveedores&accion=lista"><i class="bi bi-truck"></i> <b>Proveedores</b></a>
            <a href="index.php?modulo=inventario&accion=lista"><i class="bi bi-box"></i> <b>Inventario</b></a>
            <a href="index.php?modulo=pedidos&accion=lista"><i class="bi bi-cart"></i> <b>Pedidos</b></a>
            <a href="index.php?modulo=auth&accion=logout"><i class="bi bi-box-arrow-right"></i> <b>Cerrar Sesion</b></a>
        </div>
    </nav>

