<?php

require_once '../app/config/Database.php';

class Pedido {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }


    public function crearPedido($usuarioId) {

        $stmt = $this->db->prepare(
            "SELECT id
             FROM carritos
             WHERE usuario_id = ?
             AND estado = 'activo'
             LIMIT 1"
        );

        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $carrito = $resultado->fetch_assoc();

        if (!$carrito) {
            return false;
        }

        $carritoId = (int) $carrito['id'];


        $stmt = $this->db->prepare(
            "SELECT
                cd.producto_id,
                cd.cantidad,
                cd.precio_unitario,
                cd.subtotal,
                p.nombre,
                p.categoria_id,
                p.descuento_porcentaje,
                p.stock

             FROM carrito_detalles cd

             INNER JOIN productos p
             ON p.id = cd.producto_id

             WHERE cd.carrito_id = ?"
        );

        $stmt->bind_param('i', $carritoId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);


        if (empty($productos)) {
            return false;
        }

        $subtotal = 0;

        foreach ($productos as $producto) {

            if (
                (int) $producto['cantidad'] >
                (int) $producto['stock']
            ) {
                return false;
            }

            $subtotal += (float) $producto['subtotal'];
        }


        $descuentoTotal = 0;
        $impuestoTotal = 0;
        $total = $subtotal;


        $numeroPedido = 'PED-' . date('YmdHis');

        $stmt = $this->db->prepare(
            "INSERT INTO pedidos
            (
                usuario_id,
                carrito_id,
                numero_pedido,
                subtotal,
                descuento_total,
                impuesto_total,
                total,
                estado
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, 'completado'
            )"
        );

        $stmt->bind_param(
            'iisdddd',
            $usuarioId,
            $carritoId,
            $numeroPedido,
            $subtotal,
            $descuentoTotal,
            $impuestoTotal,
            $total
        );

        $stmt->execute();

        $pedidoId = $this->db->insert_id;


        foreach ($productos as $producto) {

            $stmt = $this->db->prepare(
                "INSERT INTO pedido_detalles
                (
                    pedido_id,
                    producto_id,
                    categoria_id,
                    nombre_producto,
                    cantidad,
                    precio_unitario,
                    descuento_porcentaje,
                    subtotal
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?, ?, ?
                )"
            );

            $stmt->bind_param(
                'iiisidid',
                $pedidoId,
                $producto['producto_id'],
                $producto['categoria_id'],
                $producto['nombre'],
                $producto['cantidad'],
                $producto['precio_unitario'],
                $producto['descuento_porcentaje'],
                $producto['subtotal']
            );

            $stmt->execute();


            $stmtStock = $this->db->prepare(
                "UPDATE productos
                 SET stock = stock - ?
                 WHERE id = ?
                 AND stock >= ?"
            );

            $stmtStock->bind_param(
                'iii',
                $producto['cantidad'],
                $producto['producto_id'],
                $producto['cantidad']
            );

            $stmtStock->execute();
        }


        $numeroFactura =
            'FAC-' .
            str_pad(
                $pedidoId,
                6,
                '0',
                STR_PAD_LEFT
            );



        $stmt = $this->db->prepare(
            "INSERT INTO facturas
            (
                pedido_id,
                usuario_id,
                numero_factura,
                subtotal,
                descuento_total,
                impuesto_total,
                total
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?
            )"
        );

        $stmt->bind_param(
            'iisdddd',
            $pedidoId,
            $usuarioId,
            $numeroFactura,
            $subtotal,
            $descuentoTotal,
            $impuestoTotal,
            $total
        );

        $stmt->execute();

        $facturaId = $this->db->insert_id;


        foreach ($productos as $producto) {

            $stmt = $this->db->prepare(
                "INSERT INTO factura_detalles
                (
                    factura_id,
                    producto_id,
                    descripcion,
                    cantidad,
                    precio_unitario,
                    subtotal
                )
                VALUES
                (
                    ?, ?, ?, ?, ?, ?
                )"
            );

            $stmt->bind_param(
                'iisidd',
                $facturaId,
                $producto['producto_id'],
                $producto['nombre'],
                $producto['cantidad'],
                $producto['precio_unitario'],
                $producto['subtotal']
            );

            $stmt->execute();
        }

        $stmt = $this->db->prepare(
            "DELETE FROM carrito_detalles
             WHERE carrito_id = ?"
        );

        $stmt->bind_param('i', $carritoId);
        $stmt->execute();


        return [
            'pedido_id' => $pedidoId,
            'numero_pedido' => $numeroPedido,
            'factura_id' => $facturaId,
            'numero_factura' => $numeroFactura
        ];
    }


    public function obtenerFactura($facturaId, $usuarioId) {

        $stmt = $this->db->prepare(
            "SELECT
                f.id,
                f.numero_factura,
                f.subtotal,
                f.descuento_total,
                f.impuesto_total,
                f.total,
                f.fecha_emision,
                p.numero_pedido,
                u.nombre AS cliente,
                u.correo

             FROM facturas f

             INNER JOIN pedidos p
             ON p.id = f.pedido_id

             INNER JOIN usuarios u
             ON u.id = f.usuario_id

             WHERE f.id = ?
             AND f.usuario_id = ?

             LIMIT 1"
        );

        $stmt->bind_param(
            'ii',
            $facturaId,
            $usuarioId
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }


    public function obtenerDetallesFactura($facturaId) {

        $stmt = $this->db->prepare(
            "SELECT
                descripcion,
                cantidad,
                precio_unitario,
                subtotal

             FROM factura_detalles

             WHERE factura_id = ?"
        );

        $stmt->bind_param(
            'i',
            $facturaId
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }


    public function obtenerPedidosUsuario($usuarioId) {

        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.numero_pedido,
                p.subtotal,
                p.total,
                p.estado,
                p.creado_en,
                f.id AS factura_id,
                f.numero_factura

             FROM pedidos p

             LEFT JOIN facturas f
             ON f.pedido_id = p.id

             WHERE p.usuario_id = ?

             ORDER BY p.creado_en DESC"
        );

        $stmt->bind_param(
            'i',
            $usuarioId
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }
}