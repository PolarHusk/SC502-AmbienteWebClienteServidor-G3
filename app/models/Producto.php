<?php
require_once __DIR__ . '/../config/Database.php';
class Producto {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(){
        $query = "
            SELECT p.*, c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            WHERE p.estado = 'activo' AND c.estado = 'activo'
            ORDER BY p.id DESC
        ";
        $result = $this->db->query($query);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public function getById($id){
        
        $query = "
            SELECT 
            p.*, 
            c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c 
                ON p.categoria_id = c.id
            WHERE p.id = ?
                AND p.estado = 'activo'
                AND c.estado = 'activo'
            LIMIT 1
        ";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        
        $stmt->execute();

        $result = $stmt->get_result();
        
        return $result->fetch_assoc() ?: null;
    }

    public function getCategorias(){
        
        $query = "
        SELECT id,nombre,slug
        FROM categorias 
        WHERE estado = 'activo' 
        ORDER BY nombre ASC";
        
        $result = $this->db->query($query);

        if (!$result) {
            return [];
        }

        $categorias = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = $row;
            }
        }
        return $categorias;
    }

    public function getImagenesByProductoId($productoId){
        $query = "
            SELECT *
            FROM producto_imagenes
            WHERE producto_id = ?
            ORDER BY es_principal DESC, id ASC";
        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $productoId);
        
        $stmt->execute();

        $result = $stmt->get_result();
        
        $imagenes = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagenes[] = $row;
            }
        }
        return $imagenes;
    }

    public function getByCategoria($categoriaId){
        $query = "
            SELECT p.*, c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c 
                ON p.categoria_id = c.id
            WHERE p.categoria_id = ?
                AND p.estado = 'activo'
                AND c.estado = 'activo'
            ORDER BY p.id DESC
        ";

        $stmt = $this->db->prepare($query);
        
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $categoriaId);
        
        $stmt->execute();

        $result = $stmt->get_result();
        
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }
}