<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php');

$control = $_GET['control'] ?? '';

$ventas = new ventas($conexion);

switch($control){

    case 'consulta':
        $vec = $ventas->consulta();
    break;

    case 'insertar':
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $vec = $ventas->insertar($params);
    break;

    case 'editar':
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $id = $_GET['id'] ?? null;

        if($id){
            $vec = $ventas->editar($id, $params);
        } else {
            $vec = ["error" => "ID no proporcionado"];
        }
    break;

    default:
        $vec = ["error" => "Control no válido"];
    break;
}
echo json_encode($vec);
?>