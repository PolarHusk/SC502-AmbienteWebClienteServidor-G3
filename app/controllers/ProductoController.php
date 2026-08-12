<?php
require_once '../app/core/Controller.php';

class ProductoController extends Controller {
    private $productoModel;
    private $db;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM productos");
        return $this->db->resultSet(); 
    }
}