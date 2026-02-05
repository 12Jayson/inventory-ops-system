<?php
/**
 * DashboardController: El núcleo informativo de la aplicación.
 * Este controlador consolida estadísticas globales para la administración y
 * gestiona el flujo de trabajo diario para los usuarios operativos (Managers).
 */
class DashboardController extends Controller {
    // Definición de modelos requeridos para la agregación de datos
    private $userModel;
    private $productModel;
    private $vendorModel;
    private $orderModel;
    private $purchaseModel;

    public function __construct() {
        /**
         * Middleware de Autenticación:
         * Protege todas las rutas del dashboard asegurando que solo usuarios
         * con una sesión activa puedan acceder.
         */
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/login');
            exit();
        }

        // Inyección de dependencias (Modelos)
        $this->userModel = $this->model('User');
        $this->productModel = $this->model('Product');
        $this->vendorModel = $this->model('Vendor');
        $this->orderModel = $this->model('Order');
        $this->purchaseModel = $this->model('PurchaseSetting');
    }

    /**
     * Punto de entrada principal al sistema tras el login.
     * Coordina la carga de métricas y decide qué interfaz mostrar según el rol.
     */
    public function index() {
        /**
         * Validación de contexto de tienda:
         * Para roles operativos, es obligatorio haber seleccionado una tienda activa.
         * Si no existe en sesión, se redirige al selector de tiendas. [cite: 2026-02-03]
         */
        if (($_SESSION['user_role'] === 'manager' || $_SESSION['user_role'] === 'user') && !isset($_SESSION['active_store_id'])) {
            header('location: ' . URLROOT . '/users/select_store');
            exit();
        }

        /**
         * Preparación del Data Object:
         * Recopila KPIs (Key Performance Indicators) del sistema para las "Summary Cards"
         * y recupera la agenda de compras programada para el día actual. [cite: 2026-02-04]
         */
        $data = [
            'title' => 'Dashboard - Shahs Inventory',
            'username' => $_SESSION['username'],
            'role' => $_SESSION['user_role'],
            
            // Métricas cuantitativas para el panel administrativo
            'total_products' => $this->productModel->getTotalProducts(),
            'total_vendors'  => $this->vendorModel->getTotalVendors(),
            'total_users'    => $this->userModel->getTotalUsers(),
            'total_orders'   => $this->orderModel->getTotalOrders(),
            
            // Lógica de recordatorios: Identifica proveedores que requieren orden hoy [cite: 2026-02-04]
            'today_vendors'  => $this->purchaseModel->getTodaysPurchases(), 
            'full_schedule'  => $this->purchaseModel->getSettingsByStore()
        ];

        /**
         * Enrutamiento de Vistas por Rol:
         * Los administradores acceden al panel de control global con analíticas.
         * Los usuarios operativos son dirigidos directamente a su panel de gestión de órdenes. [cite: 2026-02-03]
         */
        if ($_SESSION['user_role'] === 'admin') {
            $this->view('admin/dashboard', $data);
        } else {
            // Interfaz simplificada enfocada en la operación diaria de la tienda [cite: 2026-02-03]
            $this->view('manager/order_dashboard', $data);
        }
    }
}