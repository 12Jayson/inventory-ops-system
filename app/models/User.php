<?php
/**
 * Modelo User: Gestiona la entidad de usuario y sus privilegios de acceso.
 * Implementa la lógica de autenticación, administración de perfiles y la 
 * compleja relación Many-to-Many entre empleados y sucursales. [cite: 2026-02-03]
 */
class User {
    private $db;

    public function __construct() {
        // Inicializa la capa de abstracción de datos personalizada
        $this->db = new Database();
    }

    // --- MÉTODOS DE PAGINACIÓN Y BÚSQUEDA ---

    /**
     * Calcula el total de usuarios para la paginación administrativa.
     * Soporta filtrado por nombre de usuario o correo electrónico.
     */
    public function getTotalUsersCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM users";
        
        if (!empty($search)) {
            $sql .= " WHERE username LIKE :search OR email LIKE :search";
        }

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }

        $row = $this->db->single();
        return $row->total;
    }

    /**
     * Recupera usuarios con sus respectivas tiendas asignadas.
     * Nota técnica: Utiliza GROUP_CONCAT para aplanar la relación M:M en un solo 
     * string legible, optimizando el rendimiento de la vista administrativa.
     */
    public function getUsersPaginated($search, $limit, $offset) {
        $sql = "SELECT u.*, GROUP_CONCAT(s.name SEPARATOR ', ') as assigned_stores 
                FROM users u 
                LEFT JOIN user_stores us ON u.id = us.user_id 
                LEFT JOIN stores s ON us.store_id = s.id";

        if (!empty($search)) {
            $sql .= " WHERE u.username LIKE :search OR u.email LIKE :search";
        }

        $sql .= " GROUP BY u.id ORDER BY u.username ASC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    // --- MÉTODOS DE AUTENTICACIÓN Y REGISTRO ---

    /**
     * Localiza un usuario por email para el proceso de Login.
     */
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Registra un nuevo usuario y retorna su ID generado.
     * Este ID es crucial para la posterior asignación de tiendas en el controlador.
     */
    public function registerAndReturnId($data) {
        $this->db->query("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); 
        $this->db->bind(':role', $data['role']);
        
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // --- MÉTODOS DE GESTIÓN DE TIENDAS (M:M) ---

    /**
     * Vincula un usuario con una tienda específica en la tabla pivot 'user_stores'.
     */
    public function assignStore($userId, $storeId) {
        $this->db->query("INSERT INTO user_stores (user_id, store_id) VALUES (:user_id, :store_id)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':store_id', $storeId);
        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            return false; 
        }
    }

    /**
     * Recupera todos los IDs de tiendas asociados a un usuario.
     * Esencial para validar permisos de acceso y flujos de trabajo del Manager. [cite: 2026-02-03]
     */
    public function getUserStoreIds($userId) {
        $this->db->query("SELECT store_id FROM user_stores WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $results = $this->db->resultSet();
        
        return array_map(function($row) { return $row->store_id; }, $results);
    }

    /**
     * Limpia todas las asociaciones de tiendas de un usuario.
     */
    public function clearUserStores($userId) {
        $this->db->query("DELETE FROM user_stores WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // --- GESTIÓN DE PERFIL Y CRUD ---

    /**
     * Localiza un usuario específico por su ID.
     */
    public function getUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Actualiza la información del usuario con lógica de password condicional.
     */
    public function updateUser($data) {
        if (!empty($data['password'])) {
            $this->db->query("UPDATE users SET username = :username, email = :email, role = :role, password = :password WHERE id = :id");
            $this->db->bind(':password', $data['password']);
        } else {
            $this->db->query("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        }
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);

        return $this->db->execute();
    }

    /**
     * Elimina un usuario y limpia sus dependencias de tiendas.
     */
    public function deleteUser($id) {
        $this->clearUserStores($id); 
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * SOLUCIÓN AL FATAL ERROR:
     * KPI para Dashboard: Obtiene el conteo total de usuarios registrados.
     * Este método es el que DashboardController llama en la línea 62.
     */
    public function getTotalUsers() {
        $this->db->query("SELECT id FROM users");
        $this->db->execute();
        return $this->db->rowCount();
    }
}