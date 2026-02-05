<?php
/**
 * Controlador Archive: Gestiona la recuperación de elementos desactivados.
 * Proporciona una interfaz para que el administrador pueda restaurar productos 
 * y proveedores que fueron archivados previamente.
 */
class Archive extends Controller {
    // Definición de modelos para acceso a datos
    private $productModel;
    private $vendorModel;

    public function __construct() {
        // Inicialización de modelos de negocio
        $this->productModel = $this->model('Product');
        $this->vendorModel = $this->model('Vendor');

        /**
         * Control de Acceso (RBAC):
         * Restringe el acceso exclusivamente a usuarios con rol 'admin'.
         * Si un usuario no autorizado intenta entrar, es redirigido al Dashboard. [cite: 2026-02-03]
         */
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
    }

    /**
     * Vista principal del Archivo.
     * Carga y lista todos los productos y proveedores cuyo estado es 'archived'.
     */
    public function index() {
        $data = [
            'products' => $this->productModel->getArchivedProducts(),
            'vendors' => $this->vendorModel->getArchivedVendors()
        ];
        
        // Renderiza la vista específica para administración de archivos
        $this->view('admin/archive', $data);
    }

    /**
     * Proceso de restauración de productos.
     * Cambia el estado del producto de 'archived' a 'active'.
     */
    public function restoreProduct($id) {
        if($this->productModel->restoreProduct($id)) {
            // Redirección con parámetro de éxito para notificar al usuario
            header('location: ' . URLROOT . '/archive?success=restored');
            exit();
        }
    }

    /**
     * Proceso de restauración de proveedores.
     * Reintegra al proveedor al flujo de trabajo del sistema.
     */
    public function restoreVendor($id) {
        if($this->vendorModel->restoreVendor($id)) {
            // Notificación visual de éxito tras la operación en DB
            header('location: ' . URLROOT . '/archive?success=restored');
            exit();
        }
    }
}