<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$conexion = new mysqli("localhost", "root", "", "proyecto");

if ($conexion->connect_error) {

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Error de conexión con la base de datos"
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| GET → OBTENER CITAS
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql = "SELECT id, fecha, nombre, telefono, servicio, descripcion
            FROM citas
            ORDER BY fecha ASC";

    $resultado = $conexion->query($sql);

    $citas = [];

    if ($resultado) {

        while ($fila = $resultado->fetch_assoc()) {

            $fechaCompleta = $fila['fecha'];

            // Sacar fecha
            $fecha = '';
            $hora = '';

            if ($fechaCompleta) {

                $partes = explode(' ', $fechaCompleta);

                $fecha = $partes[0] ?? '';

                if (isset($partes[1])) {
                    $hora = substr($partes[1], 0, 5);
                }
            }

            $fila['fecha'] = $fecha;
            $fila['hora'] = $hora;

            $citas[] = $fila;
        }
    }

    echo json_encode([
        "estado" => "ok",
        "citas" => $citas
    ]);

    $conexion->close();

    exit;
}


/*
|--------------------------------------------------------------------------
| POST → GUARDAR CITA
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $datos = json_decode(
        file_get_contents("php://input"),
        true
    );

    $nombre = $datos['nombre'] ?? '';
    $telefono = $datos['telefono'] ?? '';
    $servicio = $datos['servicio'] ?? '';
    $fecha = $datos['fecha'] ?? '';
    $hora = $datos['hora'] ?? '';
    $descripcion = $datos['descripcion'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | VALIDAR CAMPOS OBLIGATORIOS
    |--------------------------------------------------------------------------
    */

    if (
        !$nombre ||
        !$telefono ||
        !$servicio ||
        !$fecha ||
        !$hora
    ) {

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Todos los campos obligatorios deben estar completos"
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | UNIR FECHA + HORA
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    |
    | fecha = 2026-09-05
    | hora  = 14:30
    |
    | MySQL recibe:
    |
    | 2026-09-05 14:30:00
    |
    */

    $fechaCompleta = $fecha . ' ' . $hora . ':00';
    // Verificar que la fecha y hora no sean del pasado
$fechaActual = date('Y-m-d H:i:s');

if ($fechaCompleta <= $fechaActual) {

    echo json_encode([
        "estado" => "error",
        "mensaje" => "No puedes reservar una cita en una fecha u hora pasada."
    ]);

    exit;
    // Verificar si ya existe una cita en ese mismo horario
$sqlVerificar = "SELECT id FROM citas WHERE fecha = ?";

$stmtVerificar = $conexion->prepare($sqlVerificar);

$stmtVerificar->bind_param(
    "s",
    $fechaCompleta
);

$stmtVerificar->execute();

$resultadoVerificar = $stmtVerificar->get_result();

if ($resultadoVerificar->num_rows > 0) {

    echo json_encode([
        "estado" => "error",
        "mensaje" => "Ese horario ya está ocupado. Por favor selecciona otra fecha u hora."
    ]);

    $stmtVerificar->close();
    $conexion->close();

    exit;
}

$stmtVerificar->close();
}
    /*
    |--------------------------------------------------------------------------
    | INSERTAR
    |--------------------------------------------------------------------------
    */

    $sql = "INSERT INTO citas
            (fecha, nombre, telefono, servicio, descripcion)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {

        echo json_encode([
            "estado" => "error",
            "mensaje" => "Error al preparar la consulta"
        ]);

        exit;
    }

    $stmt->bind_param(
        "sssss",
        $fechaCompleta,
        $nombre,
        $telefono,
        $servicio,
        $descripcion
    );

    if ($stmt->execute()) {

        echo json_encode([
            "estado" => "ok",
            "mensaje" => "Cita guardada correctamente",
            "id" => $stmt->insert_id
        ]);

    } else {

        echo json_encode([
            "estado" => "error",
            "mensaje" => "No se pudo guardar la cita"
        ]);
    }

    $stmt->close();
    $conexion->close();

    exit;
}


/*
|--------------------------------------------------------------------------
| MÉTODO NO PERMITIDO
|--------------------------------------------------------------------------
*/

echo json_encode([
    "estado" => "error",
    "mensaje" => "Método no permitido"
]);

$conexion->close();

?>