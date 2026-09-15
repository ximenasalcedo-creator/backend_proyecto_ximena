<?php
$servidor = "localhost";
$usuario = "root";
$contraseña = "";
$db = "proyecto";

$conexion = mysqli_connect($servidor, $usuario, $contraseña) or die('No se conecto a mysql');
mysqli_select_db($conexion, $db) or die('No se conecto a la base de datos');
mysqli_set_charset($conexion, 'utf8'); // codificación de idioma
?>