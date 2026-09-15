<?php

class marketing {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT * FROM marketing ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql) or die('No encontró la tabla marketing');

        $vec = [];

        while ($row = mysqli_fetch_array($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM marketing WHERE id_compania = $id";
        mysqli_query($this->conexion, $sql) or die ('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO marketing (nombre, descripcion, fecha_inicio, fecha_fin) 
                VALUES ('$params->nombre', '$params->descripcion', '$params->fecha_inicio',
                '$params->fecha_fin')";

        mysqli_query($this->conexion, $sql) or die ('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE marketing SET 
                    nombre = '$params->nombre',
                    descripcion = '$params->descripcion',
                    fecha_inicio = '$params->fecha_inicio',
                    fecha_fin ='$params->fecha_fin',

                WHERE id_campana= $id";

        mysqli_query($this->conexion, $sql) or die ('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }
}

?>