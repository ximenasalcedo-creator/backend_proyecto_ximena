<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once('../modelos/conexion.php');
require_once('../modelos/marketing.php');
 
$control = $_GET['control'];
$marketing = new marketing($conexion); 

switch($control){
    case 'consulta': 
        $vec = $marketing->consulta();
    break;
    case 'insertar':
        $json = file_get_contents('php://input');
        $params = json_decode($json);

        $vec = $marketing->insertar($params);
    break;
    case 'editar':
        $json = file_get_contents('php://input');
        $id = $GET['id'];

        $params = json_decode($json);

        $vec = $marketing->editar($id, $params);
    break;
    case 'eliminar':
        $id = $GET['id'];

        $vec = $marketing->eliminar($id);
    break;
}

$datos = json_encode($vec);
echo $datos;
header('Content-Type: application/json');

?>