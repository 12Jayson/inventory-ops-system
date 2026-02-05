<?php
/**
 * Modelo PurchaseSetting: Gestiona las reglas de programación de compras.
 * Este modelo es el núcleo del sistema de recordatorios, permitiendo definir
 * frecuencias de pedido y días específicos de compra por proveedor.
 */
class PurchaseSetting {
    private $db;

    public function __construct() {
        // Inicializa la conexión mediante el wrapper PDO personalizado
        $this->db = new Database;
    }

    /**
     * Recupera el catálogo completo de configuraciones activas.
     * Realiza un JOIN con la tabla de proveedores para facilitar la lectura administrativa.
     */
    public function getSettings() {
        $this->db->query("SELECT ps.*, v.name as vendor_name 
                          FROM purchase_settings ps 
                          JOIN vendors v ON ps.vendor_id = v.id
                          ORDER BY v.name ASC");
        return $this->db->resultSet();
    }

    /**
     * Lógica Dinámica de Dashboard: Identifica compras programadas para hoy.
     * Utiliza la función FIND_IN_SET para buscar el día actual dentro de una lista 
     * separada por comas, optimizando la consulta sin necesidad de múltiples OR.
     * [cite: 2026-02-04]
     */
    public function getTodaysPurchases() {
        $dayOfWeek = date('l'); // Captura el día actual (ej: "Wednesday")
        
        $this->db->query("SELECT ps.*, v.name as vendor_name, v.id as vendor_id 
                          FROM purchase_settings ps
                          JOIN vendors v ON ps.vendor_id = v.id
                          WHERE FIND_IN_SET(:dayOfWeek, REPLACE(ps.purchase_day, ' ', ''))");
        
        $this->db->bind(':dayOfWeek', $dayOfWeek);
        return $this->db->resultSet();
    }

    /**
     * Localiza una regla de configuración específica por su ID.
     */
    public function getSettingById($id) {
        $this->db->query("SELECT * FROM purchase_settings WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Persistencia Inteligente: Guarda o actualiza configuraciones de compra.
     * Implementa la cláusula ON DUPLICATE KEY UPDATE para manejar de forma atómica 
     * la creación y edición de reglas basadas en el vendor_id.
     * [cite: 2026-02-04]
     */
    public function saveSetting($data) {
        $this->db->query("INSERT INTO purchase_settings (vendor_id, frequency, purchase_day, notes) 
                          VALUES (:vendor_id, :frequency, :purchase_day, :notes)
                          ON DUPLICATE KEY UPDATE 
                          frequency = :frequency, 
                          purchase_day = :purchase_day, 
                          notes = :notes");
        
        $this->db->bind(':vendor_id', $data['vendor_id']);
        $this->db->bind(':frequency', $data['frequency']);
        $this->db->bind(':purchase_day', $data['purchase_day']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    /**
     * Elimina una regla de programación.
     */
    public function deleteSetting($id) {
        $this->db->query("DELETE FROM purchase_settings WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Genera la agenda semanal unificada para el Dashboard del Manager.
     * Utiliza un LEFT JOIN para asegurar que todos los proveedores activos 
     * aparezcan en el calendario, incluso si aún no tienen una regla definida.
     */
    public function getSettingsByStore() {
        $this->db->query("SELECT v.id as vendor_id, v.name as vendor_name, ps.purchase_day, ps.frequency
                          FROM vendors v
                          LEFT JOIN purchase_settings ps ON v.id = ps.vendor_id 
                          WHERE v.status = 'active'
                          ORDER BY v.name ASC");
        return $this->db->resultSet();
    }
}