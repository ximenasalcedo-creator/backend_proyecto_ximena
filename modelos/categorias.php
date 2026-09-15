<?php

class categorias {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta() {

        $sql = "SELECT * FROM categorias ORDER BY nombre";

        $res = mysqli_query($this->conexion, $sql)
            or die('No encontró la tabla categorias');

        $vec = [];

        while ($row = mysqli_fetch_array($res)) {
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id) {

        $sql = "DELETE FROM categorias WHERE id_categoria = $id";

        mysqli_query($this->conexion, $sql)
            or die('No eliminó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se eliminó el registro";

        return $vec;
    }

    // INSERTAR
    public function insertar($params) {

        $sql = "INSERT INTO categorias(nombre, descripcion)
                VALUES ('$params->nombre', '$params->descripcion')";

        mysqli_query($this->conexion, $sql)
            or die('No insertó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se insertó el registro";

        return $vec;
    }

    // EDITAR
    public function editar($id, $params) {

        $sql = "UPDATE categorias SET
                    nombre = '$params->nombre',
                    descripcion = '$params->descripcion'
                WHERE id_categoria = $id";

        mysqli_query($this->conexion, $sql)
            or die('No se editó el registro');

        $vec = [];
        $vec['resultado'] = "OK";
        $vec['mensaje'] = "Se editó el registro";

        return $vec;
    }

}

?>