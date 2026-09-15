<?php

class productos {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT * FROM productos ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql) or die('No encontró la tabla productos');

        $vec = [];

        while ($row = mysqli_fetch_array($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM productos WHERE id_producto = $id";
        mysqli_query($this->conexion, $sql) or die ('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO productos (nombre, descripcion, precio) 
                VALUES ('$params->nombre', '$params->descripcion', '$params->precio')";

        mysqli_query($this->conexion, $sql) or die ('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE productos SET 
                    nombre = '$params->nombre',
                    descripcion = '$params->descripcion',
                    precio = '$params->precio'
                WHERE id_producto = $id";

        mysqli_query($this->conexion, $sql) or die ('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }
}

?>