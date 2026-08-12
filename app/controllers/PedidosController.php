<?php


class PedidosController extends Controller {


    private $pedidoModel;


    public function __construct() {

        $this->pedidoModel =
            $this->model('Pedido');
    }




    public function checkout() {


        if (
            !isset($_SESSION['usuario']['id'])
        ) {

            $this->redirect('/auth/index');

            return;
        }


        $usuarioId =
            (int) $_SESSION['usuario']['id'];


        $resultado =
            $this->pedidoModel
                 ->crearPedido($usuarioId);



        if (!$resultado) {

            $this->redirect('/carrito');

            return;
        }



      
        $_SESSION['carrito_cantidad'] = 0;



        $_SESSION['ultima_factura'] =
            $resultado['factura_id'];



        $this->redirect(
            '/pedidos/factura'
        );
    }



  

    public function factura() {


        if (
            !isset($_SESSION['usuario']['id'])
        ) {

            $this->redirect('/auth/index');

            return;
        }



        if (
            !isset($_SESSION['ultima_factura'])
        ) {

            $this->redirect(
                '/pedidos/historial'
            );

            return;
        }



        $usuarioId =
            (int) $_SESSION['usuario']['id'];


        $facturaId =
            (int) $_SESSION['ultima_factura'];



        $factura =
            $this->pedidoModel
                 ->obtenerFactura(
                     $facturaId,
                     $usuarioId
                 );



        if (!$factura) {

            $this->redirect(
                '/pedidos/historial'
            );

            return;
        }



        $detalles =
            $this->pedidoModel
                 ->obtenerDetallesFactura(
                     $facturaId
                 );



        $data = [

            'factura' => $factura,

            'detalles' => $detalles

        ];



        $this->view(
            'pedidos/factura',
            $data
        );
    }



   
    public function historial() {


        if (
            !isset($_SESSION['usuario']['id'])
        ) {

            $this->redirect('/auth/index');

            return;
        }



        $usuarioId =
            (int) $_SESSION['usuario']['id'];



        $pedidos =
            $this->pedidoModel
                 ->obtenerPedidosUsuario(
                     $usuarioId
                 );



        $data = [

            'pedidos' => $pedidos

        ];



        $this->view(
            'pedidos/historial',
            $data
        );
    }



  

    public function verFactura(
        $facturaId = null
    ) {


        if (
            !isset($_SESSION['usuario']['id'])
        ) {

            $this->redirect('/auth/index');

            return;
        }



        if (!$facturaId) {

            $this->redirect(
                '/pedidos/historial'
            );

            return;
        }



        $usuarioId =
            (int) $_SESSION['usuario']['id'];


        $facturaId =
            (int) $facturaId;



        $factura =
            $this->pedidoModel
                 ->obtenerFactura(
                     $facturaId,
                     $usuarioId
                 );



        if (!$factura) {

            $this->redirect(
                '/pedidos/historial'
            );

            return;
        }



        $detalles =
            $this->pedidoModel
                 ->obtenerDetallesFactura(
                     $facturaId
                 );



        $data = [

            'factura' => $factura,

            'detalles' => $detalles

        ];



        $this->view(
            'pedidos/factura',
            $data
        );
    }
}