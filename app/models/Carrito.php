<?php
require_once __DIR__ . '/../config/Database.php';

class Carrito {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerOCrearCarrito($usuarioId) {
        $carrito = $this->obtenerCarritoActivo($usuarioId);

        if ($carrito) {
            return (int) $carrito['id'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO carritos (usuario_id, estado) VALUES (?, 'activo')"
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        return (int) $this->db->insert_id;
    }

    public function obtenerCarritoActivo($usuarioId) {
        $stmt = $this->db->prepare(
            "SELECT id, usuario_id, estado
             FROM carritos
             WHERE usuario_id = ? AND estado = 'activo'
             LIMIT 1"
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerItems($usuarioId) {
        $stmt = $this->db->prepare(
            "SELECT
                cd.id,
                cd.producto_id,
                cd.cantidad,
                cd.precio_unitario AS precio,
                cd.subtotal,
                p.nombre,
                p.stock,
                p.imagen_principal_url,
                c.nombre AS categoria
             FROM carritos ca
             INNER JOIN carrito_detalles cd ON cd.carrito_id = ca.id
             INNER JOIN productos p ON p.id = cd.producto_id
             INNER JOIN categorias c ON c.id = p.categoria_id
             WHERE ca.usuario_id = ? AND ca.estado = 'activo'
             ORDER BY cd.id DESC"
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarProducto($usuarioId, $productoId, $cantidad = 1) {
        $cantidad = max(1, (int) $cantidad);
        $producto = $this->obtenerProducto($productoId);

        if (!$producto || $producto['estado'] !== 'activo') {
            return ['ok' => false, 'mensaje' => 'El producto no está disponible.'];
        }

        if ((int) $producto['stock'] <= 0) {
            return ['ok' => false, 'mensaje' => 'El producto no tiene stock disponible.'];
        }

        $carritoId = $this->obtenerOCrearCarrito($usuarioId);
        $detalle = $this->obtenerDetalle($carritoId, $productoId);
        $cantidadNueva = $cantidad + ($detalle ? (int) $detalle['cantidad'] : 0);

        if ($cantidadNueva > (int) $producto['stock']) {
            return [
                'ok' => false,
                'mensaje' => 'No puedes agregar más unidades que el stock disponible.'
            ];
        }

        $precio = $this->calcularPrecioFinal($producto);
        $subtotal = $precio * $cantidadNueva;

        if ($detalle) {
            $stmt = $this->db->prepare(
                "UPDATE carrito_detalles
                 SET cantidad = ?, precio_unitario = ?, subtotal = ?
                 WHERE carrito_id = ? AND producto_id = ?"
            );
            $stmt->bind_param('iddii', $cantidadNueva, $precio, $subtotal, $carritoId, $productoId);
        } else {
            $subtotal = $precio * $cantidad;
            $stmt = $this->db->prepare(
                "INSERT INTO carrito_detalles
                 (carrito_id, producto_id, cantidad, precio_unitario, subtotal)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('iiidd', $carritoId, $productoId, $cantidad, $precio, $subtotal);
        }

        $stmt->execute();
        return ['ok' => true, 'mensaje' => 'Producto agregado al carrito.'];
    }

    public function actualizarCantidad($usuarioId, $productoId, $cantidad) {
        $cantidad = (int) $cantidad;

        if ($cantidad < 1) {
            return ['ok' => false, 'mensaje' => 'La cantidad debe ser al menos 1.'];
        }

        $carrito = $this->obtenerCarritoActivo($usuarioId);
        if (!$carrito) {
            return ['ok' => false, 'mensaje' => 'No se encontró un carrito activo.'];
        }

        $producto = $this->obtenerProducto($productoId);
        if (!$producto || $producto['estado'] !== 'activo') {
            return ['ok' => false, 'mensaje' => 'El producto no está disponible.'];
        }

        if ($cantidad > (int) $producto['stock']) {
            return [
                'ok' => false,
                'mensaje' => 'La cantidad supera el stock disponible.'
            ];
        }

        $detalle = $this->obtenerDetalle((int) $carrito['id'], $productoId);
        if (!$detalle) {
            return ['ok' => false, 'mensaje' => 'El producto no está en el carrito.'];
        }

        $precio = $this->calcularPrecioFinal($producto);
        $subtotal = $precio * $cantidad;

        $stmt = $this->db->prepare(
            "UPDATE carrito_detalles
             SET cantidad = ?, precio_unitario = ?, subtotal = ?
             WHERE carrito_id = ? AND producto_id = ?"
        );
        $carritoId = (int) $carrito['id'];
        $stmt->bind_param('iddii', $cantidad, $precio, $subtotal, $carritoId, $productoId);
        $stmt->execute();

        return ['ok' => true, 'mensaje' => 'Cantidad actualizada.'];
    }

    public function eliminarProducto($usuarioId, $productoId) {
        $carrito = $this->obtenerCarritoActivo($usuarioId);
        if (!$carrito) {
            return false;
        }

        $stmt = $this->db->prepare(
            "DELETE FROM carrito_detalles
             WHERE carrito_id = ? AND producto_id = ?"
        );
        $carritoId = (int) $carrito['id'];
        $stmt->bind_param('ii', $carritoId, $productoId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function obtenerTotales($usuarioId) {
        $stmt = $this->db->prepare(
            "SELECT
                COALESCE(SUM(cd.subtotal), 0) AS subtotal,
                COALESCE(SUM(cd.subtotal), 0) AS total,
                COALESCE(SUM(cd.cantidad), 0) AS cantidad_productos
             FROM carritos ca
             LEFT JOIN carrito_detalles cd ON cd.carrito_id = ca.id
             WHERE ca.usuario_id = ? AND ca.estado = 'activo'"
        );
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function contarProductos($usuarioId) {
        $totales = $this->obtenerTotales($usuarioId);
        return (int) ($totales['cantidad_productos'] ?? 0);
    }

    private function obtenerProducto($productoId) {
        $stmt = $this->db->prepare(
            "SELECT id, precio, stock, descuento_porcentaje, estado
             FROM productos
             WHERE id = ?
             LIMIT 1"
        );
        $stmt->bind_param('i', $productoId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    private function obtenerDetalle($carritoId, $productoId) {
        $stmt = $this->db->prepare(
            "SELECT id, cantidad
             FROM carrito_detalles
             WHERE carrito_id = ? AND producto_id = ?
             LIMIT 1"
        );
        $stmt->bind_param('ii', $carritoId, $productoId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    private function calcularPrecioFinal($producto) {
        $precio = (float) $producto['precio'];
        $descuento = (int) $producto['descuento_porcentaje'];

        if ($descuento > 0) {
            $precio -= $precio * ($descuento / 100);
        }

        return round($precio, 2);
    }
}
