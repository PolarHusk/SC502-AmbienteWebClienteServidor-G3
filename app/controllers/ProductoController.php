<?php
require_once __DIR__ . '/../core/Controller.php';

class ProductoController extends Controller {
    private $productoModel;

    public function __construct() {
        $this->productoModel = $this->model('Producto');
    }

    public function index() {
        $this->catalogo();
    }

public function catalogo()
{
    $this->view('productos/catalogo');
}
public function apiProductos()
{
    header('Content-Type: application/json; charset=utf-8');

    $categoriaSlug = trim($_GET['categoria'] ?? '');
    $productos = [];

    if ($categoriaSlug === '') {
        $productos = $this->productoModel->getAll();
    } else {
        $categorias = $this->productoModel->getCategorias();
        $categoriaId = null;

        foreach ($categorias as $categoria) {
            if ($categoria['slug'] === $categoriaSlug) {
                $categoriaId = (int) $categoria['id'];
                break;
            }
        }

        if ($categoriaId !== null) {
            $productos = $this->productoModel
                ->getByCategoria($categoriaId);
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $productos
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

public function apiCategorias()
{
    header('Content-Type: application/json; charset=utf-8');

    $categorias = $this->productoModel->getCategorias();

    echo json_encode([
        'success' => true,
        'data' => $categorias
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

public function detalle($id = null)
{
    if ($id === null) {
        $id = $_GET['id'] ?? null;
    }

    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
        http_response_code(404);
        echo 'Producto no encontrado.';
        return;
    }

    $this->view('productos/detalle', [
        'productoId' => $id
    ]);
}
    public function apiProducto($id = null)
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $id ?? ($_GET['id'] ?? null);
        $id = filter_var($id, FILTER_VALIDATE_INT);


        if (!$id) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'ID de producto inválido.'
            ]);

            exit;
        }

        $producto = $this->productoModel->getById($id);

        if (!$producto) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ]);

            exit;
        }

        $producto['imagenes'] = $this->productoModel
            ->getImagenesByProductoId($id);

        echo json_encode([
            'success' => true,
            'data' => $producto
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }
}