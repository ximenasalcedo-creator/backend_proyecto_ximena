<?php

class ventas {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }
    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT v.*, us.nombre AS usuario 
                FROM ventas v
                INNER JOIN usuarios us ON v.id_usuario = us.id_usuario
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
    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO ventas (id_usuario, fecha, total) 
                VALUES (
                    '$params->id_usuario',
                    '$params->fecha',
                    '$params->total'
                )";

        $res = mysqli_query($this->conexion, $sql);

        if(!$res){
            die("Error SQL insertar: " . mysqli_error($this->conexion));
        }

        return [
            "resultado" => "OK",
            "mensaje" => "Se insertó el registro"
        ];
    }
    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE ventas SET 
                id_usuario = '$params->id_usuario',
                fecha = '$params->fecha',
                total = '$params->total'
                WHERE id_venta = $id";

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