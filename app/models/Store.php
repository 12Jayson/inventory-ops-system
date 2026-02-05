<?php
/**
 * Modelo Store: Gestiona la persistencia de las sucursales físicas.
 * Este modelo es fundamental para el sistema multi-tienda, permitiendo
 * contextualizar las órdenes, el inventario y los permisos de usuario
 * según la ubicación seleccionada. [cite: 2026-02-03]
 */
class Store {
    private $db;

    public function __construct() {
        // Inicializa la capa de acceso a datos personalizada
        $this->db = new Database();
    }

    /**
     * Recupera el listado maestro de sucursales.
     * Utilizado principalmente por el Administrador para la gestión de usuarios
     * y por el sistema de selección de tienda al iniciar sesión.
     * * @return array Conjunto de objetos Store ordenados alfabéticamente.
     */
    public function getStores() {
        $this->db->query("SELECT * FROM stores ORDER BY name ASC");
        return $this->db->resultSet();
    }

    /**
     * Localiza una sucursal específica mediante su identificador único.
     * Este método es crítico para la lógica de redirección automática:
     * Si un Manager solo tiene una tienda asignada, el sistema utiliza este 
     * método para cargar el contexto de sesión instantáneamente. [cite: 2026-02-03]
     * * @param int $id Identificador de la tienda.
     * @return object|bool Objeto con los datos de la tienda o falso si no existe.
     */
    public function getStoreById($id) {
        $this->db->query("SELECT * FROM stores WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}