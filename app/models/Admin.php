<?php


require_once '../app/config/Database.php';

class Admin {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }



    public function contarProductos() {

    $resultado = $this->db->query(
        "SELECT COUNT(*) AS total
         FROM productos
         WHERE estado = 'activo'"
    );

    $fila = $resultado->fetch_assoc();

    return (int) $fila['total'];
}

   public function obtenerStockTotal() {

    $resultado = $this->db->query(
        "SELECT SUM(stock) AS total
         FROM productos
         WHERE estado = 'activo'"
    );

    $fila = $resultado->fetch_assoc();

    return (int) ($fila['total'] ?? 0);
}


    public function contarPedidos() {

        $resultado = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM pedidos"
        );

        $fila = $resultado->fetch_assoc();

        return (int) $fila['total'];
    }

    public function obtenerProductos() {

        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.nombre,
                p.precio,
                p.stock,
                c.nombre AS categoria

             FROM productos p

             INNER JOIN categorias c
             ON c.id = p.categoria_id

             ORDER BY p.id DESC"
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }


    public function obtenerInventarioBajo() {

        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.nombre,
                p.stock

             FROM productos p

             WHERE p.stock <= 3

             ORDER BY p.stock ASC"
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }


    public function obtenerPedidosRecientes() {

        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.numero_pedido,
                u.nombre AS cliente,
                p.total,
                p.estado,
                p.creado_en

             FROM pedidos p

             INNER JOIN usuarios u
             ON u.id = p.usuario_id

             ORDER BY p.creado_en DESC"
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }

}