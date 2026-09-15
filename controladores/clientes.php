<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');

/* =========================
   PETICIÓN OPTIONS
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}


/* =========================
   CONSULTAR CLIENTES
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $buscar = $_GET['buscar'] ?? '';

    /* =========================
       BUSCAR CLIENTE
    ========================= */

    if ($buscar !== '') {

        $buscar = '%' . $buscar . '%';

        $sql = "SELECT
                    id,
                    nombre,
                    telefono,
                    email,
                    fecha_registro
                FROM clientes
                WHERE nombre LIKE ?
                ORDER BY nombre ASC
                LIMIT 20";

        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => mysqli_error($conexion)
            ]);
            exit;
        }

        mysqli_stmt_bind_param($stmt, "s", $buscar);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $clientes = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $clientes[] = $fila;
        }

        echo json_encode($clientes);
        exit;
    }


    /* =========================
       CONSULTAR TODOS
    ========================= */

    $sql = "SELECT
                id,
                nombre,
                telefono,
                email,
                fecha_registro
            FROM clientes
            ORDER BY nombre ASC";

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        echo json_encode([
            'estado' => 'error',
            'mensaje' => mysqli_error($conexion)
        ]);
        exit;
    }

    $clientes = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $clientes[] = $fila;
    }

    echo json_encode($clientes);
    exit;
}


/* =========================
   CREAR / EDITAR
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';


    /* =========================
       CREAR CLIENTE
    ========================= */

    if ($accion === 'crear') {

        if (
            empty($nombre) ||
            empty($telefono) ||
            empty($email)
        ) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Los campos obligatorios están incompletos.'
            ]);
            exit;
        }

        $sql = "INSERT INTO clientes
                (
                    nombre,
                    telefono,
                    email
                )
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => mysqli_error($conexion)
            ]);
            exit;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $nombre,
            $telefono,
            $email
        );

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                'estado' => 'ok',
                'mensaje' => 'Cliente creado correctamente'
            ]);

        } else {

            echo json_encode([
                'estado' => 'error',
                'mensaje' => mysqli_error($conexion)
            ]);
        }

        exit;
    }


    /* =========================
       EDITAR CLIENTE
    ========================= */

    if ($accion === 'editar') {

        $id = $_POST['id'] ?? 0;

        if (
            empty($id) ||
            empty($nombre) ||
            empty($telefono) ||
            empty($email)
        ) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Los campos obligatorios están incompletos.'
            ]);
            exit;
        }

        $sql = "UPDATE clientes SET
                    nombre = ?,
                    telefono = ?,
                    email = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => mysqli_error($conexion)
            ]);
            exit;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nombre,
            $telefono,
            $email,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                'estado' => 'ok',
                'mensaje' => 'Cliente actualizado correctamente'
            ]);

        } else {

            echo json_encode([
                'estado' => 'error',
                'mensaje' => mysqli_error($conexion)
            ]);
        }

        exit;
    }
}


/* =========================
   ELIMINAR CLIENTE
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $id = $_GET['id'] ?? 0;

    $sql = "DELETE FROM clientes WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        echo json_encode([
            'estado' => 'error',
            'mensaje' => mysqli_error($conexion)
        ]);
        exit;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        echo json_encode([
            'estado' => 'ok',
            'mensaje' => 'Cliente eliminado correctamente'
        ]);

    } else {

        echo json_encode([
            'estado' => 'error',
            'mensaje' => mysqli_error($conexion)
        ]);
    }

    exit;
}


/* =========================
   PETICIÓN NO VÁLIDA
========================= */

echo json_encode([
    'estado' => 'error',
    'mensaje' => 'Petición no válida'
]);

?>