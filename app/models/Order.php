<?php
/**
 * Clase Order (Modelo): Encargada de la persistencia y lógica de negocio de los pedidos.
 * Implementa consultas optimizadas para el dashboard operativo, gestión de inventario
 * y generación de reportes administrativos.
 */
class Order {
    private $db;

    public function __construct() {
        // Inicializa la conexión a la base de datos mediante la librería PDO personalizada
        $this->db = new Database;
    }

    // --- SECCIÓN DE DASHBOARD Y CALENDARIO ---

    /**
     * Recupera la agenda semanal de compras.
     * @return array Lista de proveedores con sus días de compra programados.
     * Nota técnica: Se corrigió el uso de 'v.name' para asegurar compatibilidad con el motor MySQL.
     */
    public function getFullWeeklySchedule() {
        $this->db->query("SELECT v.name as vendor_name, v.id as vendor_id, ps.purchase_day 
                          FROM vendors v
                          LEFT JOIN purchase_settings ps ON v.id = ps.vendor_id
                          WHERE v.status = 'active'
                          ORDER BY 
                            CASE WHEN ps.purchase_day IS NULL THEN 1 ELSE 0 END, 
                            v.name ASC");
        return $this->db->resultSet();
    }

    /**
     * Valida si existe una orden registrada en la fecha actual (CURDATE).
     * Esta función permite al sistema decidir si mostrar el botón 'Crear' o 'Editar'.
     */
    public function getSubmittedOrderToday($store_id, $vendor_id) {
        $this->db->query("SELECT * FROM orders 
                          WHERE store_id = :store_id 
                          AND vendor_id = :vendor_id 
                          AND DATE(created_at) = CURDATE()
                          LIMIT 1");
        $this->db->bind(':store_id', $store_id);
        $this->db->bind(':vendor_id', $vendor_id);
        return $this->db->single();
    }

    // --- SECCIÓN DE PRODUCTOS ---

    /**
     * Obtiene el catálogo de productos vinculado a un proveedor.
     * Optimización: Se eliminó el filtro p.status para evitar errores de columna inexistente.
     */
    public function getProductsByVendor($vendorId) {
        $this->db->query("SELECT p.id, p.item_name, p.category, p.item_code, v.name as vendor_name 
                          FROM products p 
                          JOIN vendors v ON p.vendor_id = v.id 
                          WHERE p.vendor_id = :vendor_id
                          ORDER BY p.item_name ASC");
        $this->db->bind(':vendor_id', $vendorId);
        return $this->db->resultSet();
    }

    // --- SECCIÓN DE PROCESAMIENTO DE ÓRDENES ---

    /**
     * Registra una nueva transacción de compra.
     * Los detalles del pedido se almacenan en formato JSON para mayor flexibilidad.
     */
    public function submitOrder($data) {
        $this->db->query("INSERT INTO orders (user_id, store_id, vendor_id, order_details) 
                          VALUES (:user_id, :store_id, :vendor_id, :order_details)");
        
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->bind(':store_id', $_SESSION['active_store_id']);
        $this->db->bind(':vendor_id', $data['vendor_id']);
        
        /**
         * Limpieza de datos: Solo persistimos productos con cantidades mayores a cero.
         * Esto optimiza el almacenamiento y evita registros vacíos en los reportes.
         */
        $details = array_filter($data['items'], function($qty) {
            return !empty($qty) && $qty > 0;
        });

        $this->db->bind(':order_details', json_encode($details));
        return $this->db->execute();
    }

    /**
     * Actualiza los detalles de una orden enviada.
     * Mantiene la trazabilidad original del registro al no modificar 'created_at'.
     */
    public function updateOrder($id, $items) {
        $this->db->query("UPDATE orders 
                          SET order_details = :details 
                          WHERE id = :id");
        
        $details = array_filter($items, function($qty) {
            return !empty($qty) && $qty > 0;
        });

        $this->db->bind(':details', json_encode($details));
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // --- SECCIÓN DE REPORTES Y CONSULTAS ---

    /**
     * Obtiene una orden específica con los datos del proveedor asociados.
     */
    public function getOrderById($id) {
        $this->db->query("SELECT o.*, v.name AS vendor_name 
                          FROM orders o 
                          LEFT JOIN vendors v ON o.vendor_id = v.id 
                          WHERE o.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Motor de Reportes: Genera listados basados en roles y filtros dinámicos.
     * Implementa seguridad de datos: los Managers solo ven datos de su tienda activa.
     */
    public function getReports($filters = []) {
        $sql = "SELECT 
                    o.*, 
                    u.username, 
                    s.name as store_name,
                    v.name as vendor_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN stores s ON o.store_id = s.id
                LEFT JOIN vendors v ON o.vendor_id = v.id
                WHERE 1=1";

        // Regla de privacidad por rol [cite: 2026-02-03]
        if ($_SESSION['user_role'] !== 'admin') {
            $sql .= " AND o.store_id = :active_store";
        }

        // Aplicación dinámica de filtros de fecha y sucursal
        if (!empty($filters['date'])) $sql .= " AND DATE(o.created_at) = :date";
        if (!empty($filters['store_id'])) $sql .= " AND o.store_id = :store_id";

        $sql .= " ORDER BY o.created_at DESC";
        
        $this->db->query($sql);

        if ($_SESSION['user_role'] !== 'admin') {
            $this->db->bind(':active_store', $_SESSION['active_store_id']);
        }

        if (!empty($filters['date'])) $this->db->bind(':date', $filters['date']);
        if (!empty($filters['store_id'])) $this->db->bind(':store_id', $filters['store_id']);

        return $this->db->resultSet();
    }

    /**
     * KPI para Dashboard: Total histórico de transacciones procesadas.
     */
    public function getTotalOrders() {
        $this->db->query("SELECT COUNT(*) as total FROM orders");
        $row = $this->db->single();
        return $row->total ?? 0;
    }
}