<?php

class inventario {
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    // CONSULTAR TODOS
    public function consulta(){
       $sql = "SELECT inv.*, pr.nombre AS producto
        FROM inventario inv
        INNER JOIN productos pr 
        ON inv.id_producto = pr.id_producto
        ORDER BY inv.fecha";

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

    // CONSULTAR POR STOCK
    public function consulta2($stock){
        $sql = "SELECT * FROM inventario 
                WHERE stock = $stock 
                ORDER BY fecha";

        $res = mysqli_query($this->conexion, $sql);

        if(!$res){
            die("Error SQL consulta2: " . mysqli_error($this->conexion));
        }

        $vec = [];

        while ($row = mysqli_fetch_assoc($res)){
            $vec[] = $row;
        }

        return $vec;
    }

    // ELIMINAR
    public function eliminar($id){
        $sql = "DELETE FROM inventario WHERE id_inventario = $id";

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
        $sql = "INSERT INTO inventario (id_producto, stock, fecha) 
                VALUES (
                    '$params->id_producto',
                    '$params->stock',
                    '$params->fecha'
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
        $sql = "UPDATE inventario SET 
                    id_producto = '$params->id_producto',
                    stock = '$params->stock',
                    fecha = '$params->fecha'
                WHERE id_inventario = $id";

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
    