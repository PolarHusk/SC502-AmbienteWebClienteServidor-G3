<?php
require_once '../app/core/Controller.php';
require_once '../app/models/Producto.php';

class AdminController extends Controller {
    private $adminModel;

    public function __construct() {
        $this->adminModel = $this->model('Admin');
    }


    public function index() {

        $productoModel = new Producto();

        $productos = $productoModel->getAll();

        $totalProductos = $this->adminModel->contarProductos();
        $stockTotal = $this->adminModel->obtenerStockTotal();
        $totalPedidos = $this->adminModel->contarPedidos();
        $inventarioBajo = $this->adminModel->obtenerInventarioBajo();
        $pedidos = $this->adminModel->obtenerPedidosRecientes();

        $this->view('admin/dashboard', [
            'productos' => $productos,
            'totalProductos' => $totalProductos,
            'stockTotal' => $stockTotal,
            'totalPedidos' => $totalPedidos,
            'inventarioBajo' => $inventarioBajo,
            'pedidos' => $pedidos
        ]);
    }


    public function crear() {

        $productoModel = new Producto();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'categoria_id' => $_POST['categoria_id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'imagen_principal_url' => $_POST['imagen_principal_url'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? 0,
                'es_nuevo_lanzamiento' =>
                    isset($_POST['es_nuevo_lanzamiento']) ? 1 : 0
            ];

            if (
                empty($data['categoria_id']) ||
                empty($data['nombre']) ||
                empty($data['descripcion']) ||
                $data['precio'] <= 0
            ) {

                $categorias = $productoModel->getCategorias();

                $this->view('admin/crear', [
                    'categorias' => $categorias,
                    'error' => 'Complete los campos obligatorios.'
                ]);

                return;
            }

            $creado = $productoModel->crearProducto($data);

            if ($creado) {
                $this->redirect('/admin/index');
                return;
            }

            $categorias = $productoModel->getCategorias();

            $this->view('admin/crear', [
                'categorias' => $categorias,
                'error' => 'No se pudo crear el producto.'
            ]);

        } else {

            $categorias = $productoModel->getCategorias();

            $this->view('admin/crear', [
                'categorias' => $categorias
            ]);
        }
    }


    public function editar() {

        $productoModel = new Producto();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['id'] ?? 0;

            $data = [
                'categoria_id' => $_POST['categoria_id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'imagen_principal_url' => $_POST['imagen_principal_url'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'descuento_porcentaje' => $_POST['descuento_porcentaje'] ?? 0,
                'es_nuevo_lanzamiento' =>
                    isset($_POST['es_nuevo_lanzamiento']) ? 1 : 0
            ];

            if (
                !$id ||
                empty($data['categoria_id']) ||
                empty($data['nombre']) ||
                empty($data['descripcion']) ||
                $data['precio'] <= 0
            ) {

                $this->redirect('/admin/index');
                return;
            }

            $actualizado = $productoModel->actualizarProducto(
                $id,
                $data
            );

            if ($actualizado) {
                $this->redirect('/admin/index');
                return;
            }

            $this->redirect('/admin/index');

        } else {

            $id = $_GET['id'] ?? 0;

            if (!$id) {
                $this->redirect('/admin/index');
                return;
            }

            $producto = $productoModel->getById($id);

            if (!$producto) {
                $this->redirect('/admin/index');
                return;
            }

            $categorias = $productoModel->getCategorias();

            $this->view('admin/editar', [
                'producto' => $producto,
                'categorias' => $categorias
            ]);
        }
    }


    public function eliminar() {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('/admin/index');
            return;
        }

        $id = $_POST['id'] ?? 0;

        if (!$id) {
            $this->redirect('/admin/index');
            return;
        }

        $productoModel = new Producto();

        $productoModel->eliminarProducto($id);

        $this->redirect('/admin/index');
    }
}

