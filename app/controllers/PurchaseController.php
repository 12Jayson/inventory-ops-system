<?php
/**
 * PurchaseController: Gestiona la programación automatizada de compras.
 * Este controlador permite definir cuándo se deben realizar pedidos a cada proveedor,
 * soportando frecuencias semanales y mensuales para automatizar los recordatorios del sistema.
 */
class PurchaseController extends Controller {
    private $purchaseModel;
    private $vendorModel;

    public function __construct() {
        /**
         * Control de Acceso Estricto:
         * Esta funcionalidad es crítica para la operación logística, por lo que
         * se restringe exclusivamente al rol de Administrador. [cite: 2026-02-03]
         */
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('location: ' . URLROOT . '/login');
            exit();
        }

        $this->purchaseModel = $this->model('PurchaseSetting');
        $this->vendorModel = $this->model('Vendor');
    }

    /**
     * Gestiona las configuraciones de compra (Calendario de Pedidos).
     * Procesa tanto la visualización de la tabla como el almacenamiento de nuevas reglas.
     */
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitización de entradas para asegurar la integridad de los datos
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            /**
             * Lógica de Negocio Dinámica:
             * Se normaliza el valor del día de compra dependiendo de la frecuencia seleccionada.
             * - Mensual: Almacena un valor numérico único (ej: "15").
             * - Semanal: Serializa una lista de días (ej: "Monday, Wednesday") mediante implode.
             */
            $purchase_day_value = '';
            if ($_POST['frequency'] === 'Monthly' || $_POST['frequency'] === 'per_month') {
                $purchase_day_value = $_POST['purchase_day'] ?? '';
            } else {
                // Manejo de arreglos provenientes de checkboxes en el frontend
                $purchase_day_value = isset($_POST['days']) ? implode(', ', $_POST['days']) : '';
            }

            $data = [
                'vendor_id'    => $_POST['vendor_id'],
                'frequency'    => $_POST['frequency'],
                'purchase_day' => $purchase_day_value,
                'notes'        => trim($_POST['notes'])
            ];

            /**
             * Validación de Capa de Controlador:
             * Asegura que los campos obligatorios para el algoritmo del dashboard estén presentes.
             */
            if (empty($data['vendor_id']) || empty($data['purchase_day'])) {
                die("Error: Vendor and Schedule are required.");
            }

            // Persistencia mediante el modelo PurchaseSetting
            if ($this->purchaseModel->saveSetting($data)) {
                header('location: ' . URLROOT . '/purchase/settings?success=1');
                exit();
            } else {
                die("Error: Failure to save configuration to database.");
            }
        } else {
            /**
             * Carga de Contexto para la Vista:
             * Recupera la lista de proveedores para poblar el selector y las
             * configuraciones actuales para la tabla de administración.
             */
            $data = [
                'settings' => $this->purchaseModel->getSettings(),
                'vendors'  => $this->vendorModel->getVendors()
            ];
            
            $this->view('admin/purchase_settings', $data);
        }
    }

    /**
     * Elimina una regla de programación específica.
     * Requiere método POST para prevenir acciones accidentales mediante URLs directas.
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->purchaseModel->deleteSetting($id)) {
                header('location: ' . URLROOT . '/purchase/settings?deleted=1');
                exit();
            }
        }
    }
}