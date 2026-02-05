<?php
/**
 * Clase Product (Modelo): Gestiona la persistencia de la tabla de productos.
 * Implementa lógica de filtrado avanzado, paginación y un sistema de archivado (Soft Delete)
 * para mantener la integridad referencial en las órdenes históricas.
 */
class Product {
    private $db;

    public function __construct() {
        // Inicializa la capa de abstracción de base de datos personalizada
        $this->db = new Database;
    }

    /**
     * Calcula el volumen total de productos activos para el motor de paginación.
     * @param string $search Término opcional para filtrar por nombre, categoría o código.
     * @return int Cantidad total de registros que coinciden con el criterio.
     */
    public function getTotalProductsCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM products WHERE is_active = 1";
        
        // Aplicación dinámica de filtros de búsqueda
        if (!empty($search)) {
            $sql .= " AND (item_name LIKE :search OR category LIKE :search OR item_code LIKE :search)";
        }

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }

        $row = $this->db->single();
        return $row->total;
    }

    /**
     * Recupera un segmento específico de productos para la visualización paginada.
     * Realiza un LEFT JOIN con la tabla de proveedores para enriquecer los datos.
     * @param string $search Criterio de búsqueda.
     * @param int $limit Cantidad de registros por página.
     * @param int $offset Punto de inicio de la consulta.
     */
    public function getProductsPaginated($search, $limit, $offset) {
        $sql = "SELECT p.*, v.name as vendor_name 
                FROM products p 
                LEFT JOIN vendors v ON p.vendor_id = v.id 
                WHERE p.is_active = 1";

        if (!empty($search)) {
            // Capacidad de búsqueda transversal: Producto o Proveedor
            $sql .= " AND (p.item_name LIKE :search OR p.category LIKE :search OR p.item_code LIKE :search OR v.name LIKE :search)";
        }

        $sql .= " ORDER BY p.item_name ASC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    /**
     * DESACTIVACIÓN LÓGICA (Soft Delete):
     * Cambia el estado a inactivo para ocultar el producto sin eliminarlo físicamente.
     * Esto previene que las órdenes de compra antiguas pierdan su información relacionada.
     */
    public function deleteProduct($id) {
        $this->db->query("UPDATE products SET is_active = 0 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- OPERACIONES CRUD ESTÁNDAR ---

    /**
     * Registra un nuevo producto vinculándolo a un proveedor y activándolo por defecto.
     */
    public function addProduct($data) {
        $this->db->query("INSERT INTO products (vendor_id, item_name, category, item_code, is_active) 
                          VALUES (:vendor_id, :item_name, :category, :item_code, 1)");
        $this->db->bind(':vendor_id', $data['vendor_id']);
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':item_code', $data['item_code']);
        return $this->db->execute();
    }

    /**
     * Localiza un producto específico mediante su ID único.
     */
    public function getProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id AND is_active = 1");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Actualiza la información del producto en la base de datos.
     */
    public function updateProduct($data) {
        $this->db->query("UPDATE products SET vendor_id = :vendor_id, item_name = :item_name, category = :category, item_code = :item_code WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':vendor_id', $data['vendor_id']);
        $this->db->bind(':item_name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':item_code', $data['item_code']);
        return $this->db->execute();
    }

    /**
     * KPI: Retorna la suma total de productos operativos en el sistema.
     */
    public function getTotalProducts() {
        $this->db->query("SELECT id FROM products WHERE is_active = 1");
        $this->db->execute();
        return $this->db->rowCount();
    }

    // --- GESTIÓN DE ARCHIVO Y RECUPERACIÓN ---

    /**
     * Lista todos los productos que han sido desactivados lógicamente.
     */
    public function getArchivedProducts() {
        $this->db->query("SELECT p.*, v.name as vendor_name 
                          FROM products p 
                          LEFT JOIN vendors v ON p.vendor_id = v.id 
                          WHERE p.is_active = 0 
                          ORDER BY p.item_name ASC");
        return $this->db->resultSet();
    }

    /**
     * Reactivación: Revierte el proceso de archivado devolviendo el producto al catálogo activo.
     */
    public function restoreProduct($id) {
        $this->db->query("UPDATE products SET is_active = 1 WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}