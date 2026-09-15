<?php

class servicios {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT s.*, us.nombre AS usuario 
                FROM servicios s
                INNER JOIN usuarios us ON s.id_usuario = us.id_usuario
                ORDER BY us.nombre";

        $res = mysqli_query($this->conexion, $sql);

        if(!$res){
            die("Error SQL consulta: " . mysqli_error($this->conexion));
        }

        $vec = [];

        while ($row = mysqli_fetch_assoc($res)){
            $vec[] = $row;
        }

        return $vec;
    }
    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM servicios WHERE id_servicio = $id";

        $res = mysqli_query($this->conexion, $sql);

        if(!$res){
            die("Error SQL eliminar: " . mysqli_error($this->conexion));
        }

        return [
            "resultado" => "OK",
            "mensaje" => "Se eliminó el registro"
        ];
    }
    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO servicios 
                (nombre_servicio, descripcion, costo, fecha, id_usuario)
                VALUES (
                    '$params->nombre_servicio',
                    '$params->descripcion',
                    '$params->costo',
                    '$params->fecha',
                    '$params->id_usuario'
                )";

        $res = mysqli_query($this->conexion, $sql);

        return [
            "resultado" => "OK",
            "mensaje" => "Se insertó el registro"
        ];
    }
    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE servicios SET 
                    nombre_servicio = '$params->nombre_servicio',
                    descripcion = '$params->descripcion',
                    costo = '$params->costo',
                    fecha = '$params->fecha',
                    id_usuario = '$params->id_usuario'
                WHERE id_servicio = $id";

        $res = mysqli_query($this->conexion, $sql);

        if(!$res){
            die("Error SQL editar: " . mysqli_error($this->conexion));
        }

        return [
            "resultado" => "OK",
            "mensaje" => "Se editó el registro"
        ];
    }
}
?>