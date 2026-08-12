<?php
require_once '../app/core/Controller.php';
require_once '../app/config/Mailer.php';

class AuthController extends Controller {
    public function __construct() {
        session_start();
    }

    public function index() {
        if (isset($_SESSION['usuario'])) {
            $this->redirectSegunRol();
        }
        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';

            $usuarioModel = $this->model('Usuario');
            $usuario = $usuarioModel->getByCorreo($correo);

            if (!$usuario) {

                $this->redirect(
                    '/auth/registro?correo=' . urlencode($correo)
                );

                return;
            }

            if (!password_verify(
                $contrasena,
                $usuario['contrasena_hash']
            )) {

                $this->view('auth/login', [
                    'error' => 'Credenciales incorrectas'
                ]);

                return;
            }

            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'rol' => $usuario['rol_nombre']
            ];

           
            $carritoModel = $this->model('Carrito');

            $_SESSION['carrito_cantidad'] =
                $carritoModel->contarProductos(
                    $usuario['id']
                );

            $this->redirectSegunRol();
        } else {

            $this->redirect('/auth/index');
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nombre'     => $_POST['nombre'] ?? '',
                'correo'     => $_POST['correo'] ?? '',
                'contrasena' => $_POST['contrasena'] ?? ''
            ];

            if (empty($data['nombre']) || empty($data['correo']) || empty($data['contrasena'])) {
                $this->view('auth/registro', ['error' => 'Todos los campos son obligatorios']);
                return;
            }

            $usuarioModel = $this->model('Usuario');

            if ($usuarioModel->getByCorreo($data['correo'])) {
                $this->view('auth/registro', ['error' => 'El correo ya está registrado']);

                return;
            }


           
            $creado = $usuarioModel->create($data);


            if ($creado) {

                
                Mailer::enviarBienvenida(
                    $data['correo'],
                    $data['nombre']
                );

                
                $this->redirect('/auth/index');

                return;
            }


           
            $this->view('auth/registro', [
                'error' => 'No se pudo crear la cuenta'
            ]);

        } else {

            $correoPrellenado = $_GET['correo'] ?? '';

            $this->view('auth/registro', [
                'correo' => $correoPrellenado
            ]);
        }
    }


    public function logout() {

        session_destroy();

        $this->redirect('/auth/index');
    }


    private function redirectSegunRol() {

        if ($_SESSION['usuario']['rol'] === 'admin') {

            $this->redirect('/admin/index');

        } else {

            $this->redirect('/producto/catalogo'); //esto queda pendiente de crear un metodo catalogo en prodcutos controller
        }
    }
}

        }
    }
}