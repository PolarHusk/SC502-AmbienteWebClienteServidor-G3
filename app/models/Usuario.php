<?php
require_once '../app/config/Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT u.*, r.nombre AS rol_nombre 
                  FROM usuarios u
                  JOIN roles r ON r.id = u.rol_id
                  ORDER BY u.id DESC";
        $result = $this->db->query($query);
        $usuarios = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }

    public function getById($id) {
        $query = "SELECT u.*, r.nombre AS rol_nombre 
                  FROM usuarios u
                  JOIN roles r ON r.id = u.rol_id
                  WHERE u.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getByCorreo($correo) {
        $query = "SELECT u.*, r.nombre AS rol_nombre 
                  FROM usuarios u
                  JOIN roles r ON r.id = u.rol_id
                  WHERE u.correo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($data) {
   
        $rolNombre = $data['rol'] ?? 'cliente';

        $query = "INSERT INTO usuarios (rol_id, nombre, correo, contrasena_hash)
                  VALUES ((SELECT id FROM roles WHERE nombre = ?), ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $rolNombre,$data['nombre'], $data['correo'], $hashedPassword );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data) {
        if (!empty($data['contrasena'])) {
            $query = "UPDATE usuarios SET nombre = ?, correo = ?, contrasena_hash = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $hashedPassword = password_hash($data['contrasena'], PASSWORD_DEFAULT);
            $stmt->bind_param("sssi", $data['nombre'], $data['correo'], $hashedPassword, $id);
        } else {
            $query = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssi", $data['nombre'], $data['correo'], $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}