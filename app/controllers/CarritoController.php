<?php

class CarritoController extends Controller {

    private $carritoModel;

    public function __construct() {
        $this->carritoModel = $this->model('Carrito');
    }

    public function index() {

        $usuarioId = $this->obtenerUsuarioId();

        if (!$usuarioId) {
            $this->guardarMensaje(
                'Debes iniciar sesión para usar el carrito.',
                'error'
            );

            $this->redirect('/auth/index');
            return;
        }

        $carrito = $this->carritoModel->obtenerItems($usuarioId);
        $totales = $this->carritoModel->obtenerTotales($usuarioId);

        // Contar la cantidad REAL de artículos
        $cantidadTotal = 0;

        foreach ($carrito as $item) {
            $cantidadTotal += (int) $item['cantidad'];
        }

        
        $_SESSION['carrito_cantidad'] = $cantidadTotal;

        $data = [
            'carrito' => $carrito,
            'subtotal' => (float) ($totales['subtotal'] ?? 0),
            'total' => (float) ($totales['total'] ?? 0),
            'mensaje' => $_SESSION['carrito_mensaje'] ?? null,
            'tipoMensaje' => $_SESSION['carrito_tipo_mensaje'] ?? null
        ];

        unset(
            $_SESSION['carrito_mensaje'],
            $_SESSION['carrito_tipo_mensaje']
        );

        $this->view('carrito/index', $data);
    }


    public function agregar() {

        $this->soloPost();

        $usuarioId = $this->obtenerUsuarioId();

        if (!$usuarioId) {

            $this->guardarMensaje(
                'Debes iniciar sesión para agregar productos.',
                'error'
            );

            $this->redirect('/auth/index');
            return;
        }

        $productoId = filter_input(
            INPUT_POST,
            'producto_id',
            FILTER_VALIDATE_INT
        );

        $cantidad = filter_input(
            INPUT_POST,
            'cantidad',
            FILTER_VALIDATE_INT
        );

        if (!$productoId || !$cantidad || $cantidad < 1) {

            $this->guardarMensaje(
                'Los datos del producto no son válidos.',
                'error'
            );

            $this->redirect('/producto/catalogo');
            return;
        }

        $resultado = $this->carritoModel->agregarProducto(
            $usuarioId,
            $productoId,
            $cantidad
        );

        $this->guardarMensaje(
            $resultado['mensaje'],
            $resultado['ok'] ? 'exito' : 'error'
        );

        $this->actualizarContador($usuarioId);

        $this->redirect('/carrito');
    }


    public function actualizar() {

        $this->soloPost();

        $usuarioId = $this->obtenerUsuarioId();

        if (!$usuarioId) {

            $this->guardarMensaje(
                'Debes iniciar sesión para modificar el carrito.',
                'error'
            );

            $this->redirect('/auth/index');
            return;
        }

        $productoId = filter_input(
            INPUT_POST,
            'producto_id',
            FILTER_VALIDATE_INT
        );

        $cantidad = filter_input(
            INPUT_POST,
            'cantidad',
            FILTER_VALIDATE_INT
        );

        if (!$productoId || !$cantidad || $cantidad < 1) {

            $this->guardarMensaje(
                'La cantidad indicada no es válida.',
                'error'
            );

            $this->redirect('/carrito');
            return;
        }

        $resultado = $this->carritoModel->actualizarCantidad(
            $usuarioId,
            $productoId,
            $cantidad
        );

        $this->guardarMensaje(
            $resultado['mensaje'],
            $resultado['ok'] ? 'exito' : 'error'
        );

        $this->actualizarContador($usuarioId);

        $this->redirect('/carrito');
    }


    public function eliminar() {

        $this->soloPost();

        $usuarioId = $this->obtenerUsuarioId();

        if (!$usuarioId) {

            $this->guardarMensaje(
                'Debes iniciar sesión para modificar el carrito.',
                'error'
            );

            $this->redirect('/auth/index');
            return;
        }

        $productoId = filter_input(
            INPUT_POST,
            'producto_id',
            FILTER_VALIDATE_INT
        );

        if (!$productoId) {

            $this->guardarMensaje(
                'El producto indicado no es válido.',
                'error'
            );

            $this->redirect('/carrito');
            return;
        }

        $eliminado = $this->carritoModel->eliminarProducto(
            $usuarioId,
            $productoId
        );

        $this->guardarMensaje(
            $eliminado
                ? 'Producto eliminado del carrito.'
                : 'No se pudo eliminar el producto.',
            $eliminado
                ? 'exito'
                : 'error'
        );

        $this->actualizarContador($usuarioId);

        $this->redirect('/carrito');
    }


    private function obtenerUsuarioId() {

        if (isset($_SESSION['usuario']['id'])) {
            return (int) $_SESSION['usuario']['id'];
        }

        return null;
    }


    private function soloPost() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/carrito');
            exit;
        }
    }


    private function guardarMensaje($mensaje, $tipo) {

        $_SESSION['carrito_mensaje'] = $mensaje;
        $_SESSION['carrito_tipo_mensaje'] = $tipo;
    }


    private function actualizarContador($usuarioId) {

        $carrito = $this->carritoModel->obtenerItems($usuarioId);

        $cantidadTotal = 0;

        foreach ($carrito as $item) {
            $cantidadTotal += (int) $item['cantidad'];
        }

        $_SESSION['carrito_cantidad'] = $cantidadTotal;
    }
}