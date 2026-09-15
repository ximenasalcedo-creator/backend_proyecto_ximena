<?php

class contabilidad {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT con.*, v.fecha AS fecha_venta
            FROM contabilidad con
            INNER JOIN ventas v 
            ON con.id_venta = v.id_venta
            ORDER BY v.fecha";
        $res = mysqli_query($this->conexion, $sql) or die('No encontró la tabla contabilidad');

        $vec = [];

        while ($row = mysqli_fetch_array($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM contabilidad WHERE id_contabilidad = $id";
        mysqli_query($this->conexion, $sql) or die ('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO contabilidad (descripcion, monto, fecha, id_venta, id_compra) 
                VALUES ('$params->nombre', '$params->descripcion', '$params->monto','$params->fecha', 
                $params->id_venta, $params->id_compra)";

        mysqli_query($this->conexion, $sql) or die ('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE contabilidad SET 
                    descripcion = '$params->descripcion',
                    monto = '$params->monto',
                    fecha = '$params->fecha ',
                    id_venta ='$params->id_venta',
                    id_compra ='$params->id_compra'

                WHERE id_contabilidad= $id";

        mysqli_query($this->conexion, $sql) or die ('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }
}

?>