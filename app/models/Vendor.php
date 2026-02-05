<?php
/**
 * Modelo Vendor: Gestiona la persistencia y lógica de los proveedores.
 * Administra el catálogo de entidades suministradoras, incluyendo el conteo 
 * dinámico de su inventario asociado y la gestión de su ciclo de vida (status).
 */
class Vendor {
    private $db;

    public function __construct() {
        // Inicializa la conexión mediante el wrapper PDO
        $this->db = new Database;
    }

    /**
     * Recupera todos los proveedores operativos.
     * Utilizado para poblar selectores dinámicos en formularios de productos 
     * y configuraciones de compra.
     */
    public function getVendors() {
        $this->db->query("SELECT * FROM vendors WHERE status = 'active' ORDER BY name ASC");
        return $this->db->resultSet();
    }

    /**
     * Calcula el volumen total de proveedores activos.
     * Soporta filtrado por nombre para alimentar el motor de paginación del Admin.
     */
    public function getTotalVendorsCount($search = '') {
        $sql = "SELECT COUNT(*) as total FROM vendors WHERE status = 'active'";
        
        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
        }

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }

        $row = $this->db->single();
        return $row->total;
    }

    /**
     * Obtiene una lista paginada de proveedores con métricas de inventario.
     * Nota técnica: Utiliza una subconsulta para calcular 'product_count' en tiempo real,
     * permitiendo al administrador ver cuántos artículos activos tiene cada proveedor
     * sin necesidad de múltiples consultas por fila.
     */
    public function getVendorsPaginated($search, $limit, $offset) {
        $sql = "SELECT v.*, 
                (SELECT COUNT(*) FROM products p WHERE p.vendor_id = v.id AND p.is_active = 1) as product_count
                FROM vendors v 
                WHERE v.status = 'active'";

        if (!empty($search)) {
            $sql .= " AND v.name LIKE :search";
        }

        $sql .= " ORDER BY v.name ASC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);

        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    /**
     * Registra un nuevo proveedor en el sistema con estado inicial activo.
     */
    public function addVendor($data) {
        $this->db->query("INSERT INTO vendors (name, description, status) VALUES (:name, :description, 'active')");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    /**
     * Recupera la ficha técnica de un proveedor por su ID.
     */
    public function getVendorById($id) {
        $this->db->query("SELECT * FROM vendors WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Actualiza la información básica del proveedor.
     */
    public function updateVendor($data) {
        $this->db->query("UPDATE vendors SET name = :name, description = :description WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    /**
     * Desactivación Lógica (Archivado):
     * Cambia el estatus a 'inactive' para preservar la integridad referencial
     * de órdenes de compra históricas en lugar de eliminar el registro.
     */
    public function disableVendor($id) {
        $this->db->query("UPDATE vendors SET status = 'inactive' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Recupera el listado de proveedores archivados para su posible restauración.
     */
    public function getArchivedVendors() {
        $this->db->query("SELECT * FROM vendors WHERE status = 'inactive' ORDER BY name ASC");
        return $this->db->resultSet();
    }

    /**
     * Restaura el acceso operativo de un proveedor previamente archivado.
     */
    public function restoreVendor($id) {
        $this->db->query("UPDATE vendors SET status = 'active' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Métrica para Dashboard: Conteo total de socios comerciales activos.
     */
    public function getTotalVendors() {
        $this->db->query("SELECT id FROM vendors WHERE status = 'active'");
        $this->db->execute();
        return $this->db->rowCount();
    }
}