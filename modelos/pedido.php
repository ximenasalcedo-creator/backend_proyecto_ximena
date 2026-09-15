<?php

class pedido
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // =====================================================
    // CONSULTAR VENTAS
    // =====================================================

    public function consulta()
    {
        $sql = "
            SELECT
                v.id_venta,
                v.fo_cliente,
                c.nombre AS cliente,
                v.productos,
                v.subtotal,
                v.fecha,
                v.total,
                v.fo_vendedor,
                u.nombre AS vendedor
            FROM ventas v
            LEFT JOIN clientes c
                ON c.id = v.fo_cliente
            LEFT JOIN usuarios u
                ON u.id_usuario = v.fo_vendedor
            ORDER BY v.id_venta DESC
        ";

        $resultado = $this->conexion->query($sql);

        if (!$resultado) {
            return [
                "estado" => "error",
                "mensaje" => "Error al consultar ventas: " . $this->conexion->error
            ];
        }

        $ventas = [];

        while ($fila = $resultado->fetch_assoc()) {

            $ventas[] = [
                "id_venta" => (int)$fila["id_venta"],
                "fo_cliente" => $fila["fo_cliente"],
                "cliente" => $fila["cliente"] ?? "Sin cliente",
                "productos" => $fila["productos"],
                "subtotal" => (float)$fila["subtotal"],
                "fecha" => $fila["fecha"],
                "total" => (float)$fila["total"],
                "fo_vendedor" => $fila["fo_vendedor"],
                "vendedor" => $fila["vendedor"] ?? "Sin vendedor"
            ];
        }

        return $ventas;
    }


    // =====================================================
    // VER DETALLE DE UNA VENTA
    // =====================================================

    public function ver($id)
    {
        if ($id <= 0) {
            return [
                "estado" => "error",
                "mensaje" => "ID de venta inválido."
            ];
        }

        // -------------------------------
        // DATOS GENERALES DE LA VENTA
        // -------------------------------

        $sqlVenta = "
            SELECT
                v.id_venta,
                v.fo_cliente,
                c.nombre AS cliente,
                v.fo_vendedor,
                u.nombre AS vendedor,
                v.subtotal,
                v.fecha,
                v.total
            FROM ventas v
            LEFT JOIN clientes c
                ON c.id = v.fo_cliente
            LEFT JOIN usuarios u
                ON u.id_usuario = v.fo_vendedor
            WHERE v.id_venta = ?
        ";

        $stmtVenta = $this->conexion->prepare($sqlVenta);

        if (!$stmtVenta) {
            return [
                "estado" => "error",
                "mensaje" => "Error preparando la consulta de venta."
            ];
        }

        $stmtVenta->bind_param("i", $id);
        $stmtVenta->execute();

        $resultadoVenta = $stmtVenta->get_result();
        $venta = $resultadoVenta->fetch_assoc();

        $stmtVenta->close();

        if (!$venta) {
            return [
                "estado" => "error",
                "mensaje" => "La venta no existe."
            ];
        }

        // -------------------------------
        // PRODUCTOS DE LA VENTA
        // -------------------------------

        $sqlDetalle = "
            SELECT
                d.id,
                d.venta_id,
                d.producto_id,
                p.nombre AS producto,
                p.precio,
                d.cantidad,
                d.subtotal
            FROM detalle_ventas d
            LEFT JOIN productos p
                ON p.id = d.producto_id
            WHERE d.venta_id = ?
            ORDER BY d.id ASC
        ";

        $stmtDetalle = $this->conexion->prepare($sqlDetalle);

        if (!$stmtDetalle) {
            return [
                "estado" => "error",
                "mensaje" => "Error preparando el detalle de la venta."
            ];
        }

        $stmtDetalle->bind_param("i", $id);
        $stmtDetalle->execute();

        $resultadoDetalle = $stmtDetalle->get_result();

        $productos = [];

        while ($fila = $resultadoDetalle->fetch_assoc()) {

            $productos[] = [
                "id" => (int)$fila["id"],
                "venta_id" => (int)$fila["venta_id"],
                "producto_id" => (int)$fila["producto_id"],
                "producto" => $fila["producto"] ?? "Producto",
                "precio" => (float)$fila["precio"],
                "cantidad" => (int)$fila["cantidad"],
                "subtotal" => (float)$fila["subtotal"]
            ];
        }

        $stmtDetalle->close();

        return [
            "estado" => "ok",
            "venta" => [
                "id_venta" => (int)$venta["id_venta"],
                "cliente" => $venta["cliente"] ?? "Sin cliente",
                "vendedor" => $venta["vendedor"] ?? "Sin vendedor",
                "subtotal" => (float)$venta["subtotal"],
                "fecha" => $venta["fecha"],
                "total" => (float)$venta["total"]
            ],
            "productos" => $productos
        ];
    }


    // =====================================================
    // INSERTAR VENTA
    // =====================================================

    public function insertar($datos)
    {
        if (
            !isset($datos["fo_cliente"]) ||
            !isset($datos["fo_vendedor"]) ||
            !isset($datos["productos"]) ||
            !is_array($datos["productos"])
        ) {
            return [
                "estado" => "error",
                "mensaje" => "Faltan datos para registrar la venta."
            ];
        }

        $cliente = (int)$datos["fo_cliente"];
        $vendedor = (int)$datos["fo_vendedor"];

        $subtotal = isset($datos["subtotal"])
            ? (float)$datos["subtotal"]
            : 0;

        $total = isset($datos["total"])
            ? (float)$datos["total"]
            : 0;

        $productos = $datos["productos"];

        if ($cliente <= 0) {
            return [
                "estado" => "error",
                "mensaje" => "Cliente inválido."
            ];
        }

        if (count($productos) === 0) {
            return [
                "estado" => "error",
                "mensaje" => "Debe seleccionar al menos un producto."
            ];
        }

        // -----------------------------------------
        // INICIAR TRANSACCIÓN
        // -----------------------------------------

        $this->conexion->begin_transaction();

        try {

            // -----------------------------------------
            // GUARDAR PRODUCTOS COMO JSON EN ventas
            // -----------------------------------------

            $productosJSON = json_encode(
                $productos,
                JSON_UNESCAPED_UNICODE
            );

            if ($productosJSON === false) {
                throw new Exception(
                    "No se pudieron convertir los productos."
                );
            }

            // -----------------------------------------
            // INSERTAR EN ventas
            // -----------------------------------------

            $sqlVenta = "
                INSERT INTO ventas
                (
                    fo_cliente,
                    productos,
                    subtotal,
                    total,
                    fecha,
                    fo_vendedor
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ";

            $stmtVenta = $this->conexion->prepare($sqlVenta);

            if (!$stmtVenta) {
                throw new Exception(
                    "Error preparando la venta: " .
                    $this->conexion->error
                );
            }

            // La fecha puede venir del formulario.
            // Si no viene, MySQL utiliza current_timestamp().
            $fecha = !empty($datos["fecha"])
                ? $datos["fecha"]
                : date("Y-m-d H:i:s");

            $stmtVenta->bind_param(
                "isddsi",
                $cliente,
                $productosJSON,
                $subtotal,
                $total,
                $fecha,
                $vendedor
            );

            if (!$stmtVenta->execute()) {
                throw new Exception(
                    "Error insertando la venta: " .
                    $stmtVenta->error
                );
            }

            $ventaId = $this->conexion->insert_id;

            $stmtVenta->close();

            // -----------------------------------------
            // INSERTAR DETALLE
            // -----------------------------------------

            $sqlDetalle = "
                INSERT INTO detalle_ventas
                (
                    venta_id,
                    producto_id,
                    cantidad,
                    subtotal
                )
                VALUES (?, ?, ?, ?)
            ";

            $stmtDetalle = $this->conexion->prepare($sqlDetalle);

            if (!$stmtDetalle) {
                throw new Exception(
                    "Error preparando el detalle: " .
                    $this->conexion->error
                );
            }

            foreach ($productos as $producto) {

                $productoId = isset($producto["producto_id"])
                    ? (int)$producto["producto_id"]
                    : 0;

                $cantidad = isset($producto["cantidad"])
                    ? (int)$producto["cantidad"]
                    : 0;

                $subtotalProducto = isset($producto["subtotal"])
                    ? (float)$producto["subtotal"]
                    : 0;

                if ($productoId <= 0 || $cantidad <= 0) {
                    throw new Exception(
                        "Uno de los productos tiene datos inválidos."
                    );
                }

                $stmtDetalle->bind_param(
                    "iiid",
                    $ventaId,
                    $productoId,
                    $cantidad,
                    $subtotalProducto
                );

                if (!$stmtDetalle->execute()) {
                    throw new Exception(
                        "Error insertando detalle: " .
                        $stmtDetalle->error
                    );
                }
            }

            $stmtDetalle->close();

            // -----------------------------------------
            // CONFIRMAR
            // -----------------------------------------

            $this->conexion->commit();

            return [
                "estado" => "ok",
                "mensaje" => "Venta guardada correctamente.",
                "id_venta" => $ventaId
            ];

        } catch (Exception $e) {

            $this->conexion->rollback();

            return [
                "estado" => "error",
                "mensaje" => $e->getMessage()
            ];
        }
    }


    // =====================================================
    // ELIMINAR VENTA
    // =====================================================

    public function eliminar($id)
    {
        if ($id <= 0) {
            return [
                "estado" => "error",
                "mensaje" => "ID de venta inválido."
            ];
        }

        $this->conexion->begin_transaction();

        try {

            // Primero eliminamos los detalles porque
            // detalle_ventas tiene FK hacia ventas.

            $sqlDetalle = "
                DELETE FROM detalle_ventas
                WHERE venta_id = ?
            ";

            $stmtDetalle = $this->conexion->prepare($sqlDetalle);

            if (!$stmtDetalle) {
                throw new Exception(
                    "Error preparando eliminación del detalle."
                );
            }

            $stmtDetalle->bind_param("i", $id);

            if (!$stmtDetalle->execute()) {
                throw new Exception(
                    "Error eliminando el detalle: " .
                    $stmtDetalle->error
                );
            }

            $stmtDetalle->close();

            // -----------------------------------------
            // ELIMINAR VENTA
            // -----------------------------------------

            $sqlVenta = "
                DELETE FROM ventas
                WHERE id_venta = ?
            ";

            $stmtVenta = $this->conexion->prepare($sqlVenta);

            if (!$stmtVenta) {
                throw new Exception(
                    "Error preparando eliminación de venta."
                );
            }

            $stmtVenta->bind_param("i", $id);

            if (!$stmtVenta->execute()) {
                throw new Exception(
                    "Error eliminando la venta: " .
                    $stmtVenta->error
                );
            }

            if ($stmtVenta->affected_rows === 0) {
                throw new Exception(
                    "La venta no existe."
                );
            }

            $stmtVenta->close();

            $this->conexion->commit();

            return [
                "estado" => "ok",
                "mensaje" => "Venta eliminada correctamente."
            ];

        } catch (Exception $e) {

            $this->conexion->rollback();

            return [
                "estado" => "error",
                "mensaje" => $e->getMessage()
            ];
        }
    }
}