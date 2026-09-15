<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');

$identificacion = $_POST['identificacion'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$celular = $_POST['celular'] ?? '';
$email = $_POST['email'] ?? '';
$clave = $_POST['clave'] ?? '';

if (
    empty($identificacion) ||
    empty($nombre) ||
    empty($email) ||
    empty($clave)
) {
    echo json_encode([
        'estado' => false,
        'mensaje' => 'Complete los campos obligatorios.'
    ]);
    exit;
}

// Comprobar si el correo ya existe
$sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    echo json_encode([
        'estado' => false,
        'mensaje' => 'Este correo ya está registrado.'
    ]);

    exit;
}

// Registrar usuario
$rol = 'Cliente';

$sql = "INSERT INTO usuarios
        (identificacion, nombre, direccion, celular, email, rol, clave)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "issssss",
    $identificacion,
    $nombre,
    $direccion,
    $celular,
    $email,
    $rol,
    $clave
);

if ($stmt->execute()) {

    echo json_encode([
        'estado' => true,
        'mensaje' => 'Usuario registrado correctamente.'
    ]);

} else {

    echo json_encode([
        'estado' => false,
        'mensaje' => 'No se pudo registrar el usuario.'
    ]);
}

$stmt->close();
$conexion->close();

?>