<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once('../modelos/conexion.php');
require_once('../modelos/soporte.php');
 
$control = $_GET['control'];
$soporte = new soporte($conexion); 

switch($control){
    case 'consulta': 
        $vec = $soporte->consulta();
    break;
    case 'insertar':
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $vec = $soporte->insertar($params);
    break;
    case 'editar':
        $json = file_get_contents('php://input');
        $id = $GET['id'];

        $params = json_decode($json);

        $vec = $soporte->editar($id, $params);
    break;
    case 'eliminar':
        $id = $GET['id'];

        $vec = $soporte->eliminar($id);
    break;
}

$datos = json_encode($vec);
echo $datos;
header('Content-Type: application/json');

?>