<?php

class usuarios {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
        $sql = "SELECT * FROM usuarios ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql) or die('No encontró la tabla productos');

        $vec = [];

        while ($row = mysqli_fetch_array($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM usuarios WHERE id = $id_usuario";
        mysqli_query($this->conexion, $sql) or die ('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params){
        $sql = "INSERT INTO usuarios (nombre, correo, telefono, contraseña, rol) 
                VALUES ('$params->nombre', '$params->correo', '$params->telefono','
                $params->contraseña','$params->rol')";

        mysqli_query($this->conexion, $sql) or die ('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params){
        $sql = "UPDATE usuarios SET 
                    nombre = '$params->nombre',
                    correo = '$params->correo',
                    telefono = '$params->telefono'
                    contraseña = '$params->contraseña'
                    rol = '$params->rol'
                WHERE id_usuario = $id";

        mysqli_query($this->conexion, $sql) or die ('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }
}

?>