<?php
require_once '../app/core/Controller.php';
require_once '../app/models/Producto.php';
require_once '../app/models/Categoria.php';

class AdminController extends Controller {
    private $adminModel;

    public function __construct() {
        $this->adminModel = $this->model('Admin');
    }

    public function index(){
        $productoModel = new Producto();
        $productos = $productoModel->getAll();

        $categoriaModel = new Categoria();
        $categorias = $categoriaModel->getAll();

        $this->view('admin/dashboard', [
            'productos' => $productos,
            'categorias'=> $categorias
        ]);
    }
}

