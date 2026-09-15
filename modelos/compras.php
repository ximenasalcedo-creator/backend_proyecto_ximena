<?php

class compras {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT * FROM compras ORDER BY fecha";
        $res = mysqli_query($this->conexion, $sql) or die('No encontró la tabla compras');

        $vec = [];

        while ($row = mysqli_fetch_array($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM compras WHERE id_compra = $id";
        mysqli_query($this->conexion, $sql) or die ('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO compras(fecha,total) 
                VALUES ('$params->fecha','$params->total')";

        mysqli_query($this->conexion, $sql) or die ('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE compras SET 
                    fecha = '$params->fecha',
                    total = '$params->total'
                WHERE id_compras = $id";

        mysqli_query($this->conexion, $sql) or die ('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }
}

?>