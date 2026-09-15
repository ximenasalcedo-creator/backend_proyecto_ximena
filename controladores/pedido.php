<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once dirname(__DIR__) . '/modelos/conexion.php';
require_once dirname(__DIR__) . '/modelos/pedido.php';

$modelo = new pedido($conexion);

$control = $_GET['control'] ?? 'consulta';

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

// =====================================================
// CONSULTA
// =====================================================

if ($control === 'consulta') {

    echo json_encode(
        $modelo->consulta(),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

// =====================================================
// VER
// =====================================================

if ($control === 'ver') {

    echo json_encode(
        $modelo->ver($id),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

// =====================================================
// INSERTAR
// =====================================================

if ($control === 'insertar') {

    $contenido =
        file_get_contents(
            "php://input"
        );

    $datos =
        json_decode(
            $contenido,
            true
        );

    if (!is_array($datos)) {

        echo json_encode([
            "estado" => "error",
            "mensaje" =>
                "Datos JSON inválidos."
        ]);

        exit;
    }

    echo json_encode(
        $modelo->insertar($datos),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

// =====================================================
// ELIMINAR
// =====================================================

if ($control === 'eliminar') {

    echo json_encode(
        $modelo->eliminar($id),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}

// =====================================================
// CONTROL DESCONOCIDO
// =====================================================

echo json_encode([
    "estado" => "error",
    "mensaje" =>
        "Control no válido."
], JSON_UNESCAPED_UNICODE);