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
   ROLES PERMITIDOS
========================= */

$rolesPermitidos = [
    'Admin',
    'Vendedor',
    'Cajero',
    'Bodeguero',
    'Supervisor'
];

/* =========================
   CONSULTAR USUARIOS
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    /* =========================
       BUSCAR VENDEDORES
    ========================= */

    $buscar = trim($_GET['buscar'] ?? '');

    if ($buscar !== '') {

        $buscar = '%' . $buscar . '%';

        $sql = "SELECT
                    id_usuario,
                    nombre,
                    rol
                FROM usuarios
                WHERE nombre LIKE ?
                AND rol = 'Vendedor'
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

        $vendedores = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $vendedores[] = $fila;
        }

        echo json_encode($vendedores);
        exit;
    }

    /* =========================
       CONSULTAR TODOS LOS USUARIOS
    ========================= */

    $sql = "SELECT
                id_usuario,
                identificacion,
                nombre,
                direccion,
                celular,
                email,
                rol
            FROM usuarios
            ORDER BY id_usuario DESC";

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        echo json_encode([
            'estado' => 'error',
            'mensaje' => mysqli_error($conexion)
        ]);
        exit;
    }

    $usuarios = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $usuarios[] = $fila;
    }

    echo json_encode($usuarios);
    exit;
}


/* =========================
   CREAR / EDITAR
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $identificacion = $_POST['identificacion'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $email = $_POST['email'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $clave = $_POST['clave'] ?? '';

    /* =========================
       VALIDAR ROL
    ========================= */

    if (!in_array($rol, $rolesPermitidos, true)) {

        echo json_encode([
            'estado' => 'error',
            'mensaje' => 'El rol seleccionado no es válido.'
        ]);

        exit;
    }

    /* =========================
       CREAR USUARIO
    ========================= */

    if ($accion === 'crear') {

        if (
            empty($identificacion) ||
            empty($nombre) ||
            empty($email) ||
            empty($clave)
        ) {

            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Los campos obligatorios están incompletos.'
            ]);

            exit;
        }

        $sql = "INSERT INTO usuarios
                (
                    identificacion,
                    nombre,
                    direccion,
                    celular,
                    email,
                    rol,
                    clave
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "issssss",
            $identificacion,
            $nombre,
            $direccion,
            $celular,
            $email,
            $rol,
            $clave
        );

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                'estado' => 'ok',
                'mensaje' => 'Usuario creado correctamente'
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
       EDITAR USUARIO
    ========================= */

    if ($accion === 'editar') {

        $id_usuario = $_POST['id_usuario'] ?? 0;

        if (
            empty($id_usuario) ||
            empty($identificacion) ||
            empty($nombre) ||
            empty($email)
        ) {

            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Los campos obligatorios están incompletos.'
            ]);

            exit;
        }

        /* =========================
           EDITAR SIN CAMBIAR CLAVE
        ========================= */

        if ($clave === '') {

            $sql = "UPDATE usuarios SET
                        identificacion = ?,
                        nombre = ?,
                        direccion = ?,
                        celular = ?,
                        email = ?,
                        rol = ?
                    WHERE id_usuario = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "isssssi",
                $identificacion,
                $nombre,
                $direccion,
                $celular,
                $email,
                $rol,
                $id_usuario
            );

        } else {

            /* =========================
               EDITAR CAMBIANDO CLAVE
            ========================= */

            $sql = "UPDATE usuarios SET
                        identificacion = ?,
                        nombre = ?,
                        direccion = ?,
                        celular = ?,
                        email = ?,
                        rol = ?,
                        clave = ?
                    WHERE id_usuario = ?";

            $stmt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "issssssi",
                $identificacion,
                $nombre,
                $direccion,
                $celular,
                $email,
                $rol,
                $clave,
                $id_usuario
            );
        }

        if (mysqli_stmt_execute($stmt)) {

            echo json_encode([
                'estado' => 'ok',
                'mensaje' => 'Usuario actualizado correctamente'
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
   ELIMINAR USUARIO
========================= */

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $id_usuario = $_GET['id_usuario'] ?? 0;

    $sql = "DELETE FROM usuarios
            WHERE id_usuario = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_usuario
    );

    if (mysqli_stmt_execute($stmt)) {

        echo json_encode([
            'estado' => 'ok',
            'mensaje' => 'Usuario eliminado correctamente'
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
