<?php
require_once '../app/core/Controller.php';

class UserController extends Controller {
    private $usuarioModel;

 public function __construct() {
    session_start();
    if (!isset($_SESSION['usuario']['id'])) {
        $this->redirect('/auth/index');
    }
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        $this->redirect('/productos/catalogo'); 
    }
    $this->usuarioModel = $this->model('Usuario');
}

    public function index() {
        $this->view('admin/dashboard');
    }

    public function apiList(){
        header('Content-Type: application/json');
        $usuarios = $this->usuarioModel->getAll();
        echo json_encode($usuarios);
    }

    public function apiStore(){
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nombre']) || empty($data['correo']) || empty($data['contrasena'])) {
            echo json_encode(["success" => false, "message" => 'Todos los campos son requeridos']);
            return;
        }

        if ($this->usuarioModel->getByCorreo($data['correo'])) {
            echo json_encode(["success" => false, "message" => 'El correo ya está registrado']);
            return;
        }

        $result = $this->usuarioModel->create($data);

        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario creado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al crear el usuario']);
        }
    }

    public function apiShow($id){
        header('Content-Type: application/json');
        $usuario = $this->usuarioModel->getById($id);
        if ($usuario) {
            echo json_encode(["success" => true, "data" => $usuario]);
        } else {
            echo json_encode(["success" => false, "message" => 'Usuario no encontrado']);
        }
    }

    public function apiUpdate($id){
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nombre']) || empty($data['correo'])) {
            echo json_encode(["success" => false, "message" => 'Nombre y correo son requeridos']);
            return;
        }
        $result = $this->usuarioModel->update($id, $data);
        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario actualizado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al actualizar el usuario']);
        }
    }

    public function apiDelete($id){
        header('Content-Type: application/json');

        if ($id == $_SESSION['usuario']['id']) {
            echo json_encode(["success" => false, "message" => 'No podés eliminar tu propio usuario']);
            return;
        }

        $result = $this->usuarioModel->delete($id);
        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario eliminado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al eliminar el usuario']);
        }
    }
}
<?php
require_once '../app/core/Controller.php';

class UserController extends Controller {
    private $usuarioModel;

 public function __construct() {
    session_start();
    if (!isset($_SESSION['usuario']['id'])) {
        $this->redirect('/auth/index');
    }
    if ($_SESSION['usuario']['rol'] !== 'admin') {
        $this->redirect('/admin/dashboard'); 
    }
    $this->usuarioModel = $this->model('Usuario');
}

    public function index() {
        $this->view('users/index');
    }

    public function apiList(){
        header('Content-Type: application/json');
        $usuarios = $this->usuarioModel->getAll();
        echo json_encode($usuarios);
    }

    public function apiStore(){
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nombre']) || empty($data['correo']) || empty($data['contrasena'])) {
            echo json_encode(["success" => false, "message" => 'Todos los campos son requeridos']);
            return;
        }

        if ($this->usuarioModel->getByCorreo($data['correo'])) {
            echo json_encode(["success" => false, "message" => 'El correo ya está registrado']);
            return;
        }

        $result = $this->usuarioModel->create($data);

        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario creado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al crear el usuario']);
        }
    }

    public function apiShow($id){
        header('Content-Type: application/json');
        $usuario = $this->usuarioModel->getById($id);
        if ($usuario) {
            echo json_encode(["success" => true, "data" => $usuario]);
        } else {
            echo json_encode(["success" => false, "message" => 'Usuario no encontrado']);
        }
    }

    public function apiUpdate($id){
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nombre']) || empty($data['correo'])) {
            echo json_encode(["success" => false, "message" => 'Nombre y correo son requeridos']);
            return;
        }
        $result = $this->usuarioModel->update($id, $data);
        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario actualizado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al actualizar el usuario']);
        }
    }

    public function apiDelete($id){
        header('Content-Type: application/json');

        if ($id == $_SESSION['usuario']['id']) {
            echo json_encode(["success" => false, "message" => 'No podés eliminar tu propio usuario']);
            return;
        }

        $result = $this->usuarioModel->delete($id);
        if ($result) {
            echo json_encode(["success" => true, "message" => 'Usuario eliminado correctamente']);
        } else {
            echo json_encode(["success" => false, "message" => 'Error al eliminar el usuario']);
        }
    }
}