<?php
/**
 * OrderController: Gestiona el ciclo de vida completo de las órdenes de compra.
 * Este controlador orquestra la visualización del dashboard operativo, la creación 
 * de pedidos con lógica de tiempo restringido y la generación de reportes detallados.
 */
class OrderController extends Controller {
    private $orderModel;
    private $vendorModel;
    private $productModel;

    public function __construct() {
        /**
         * Inicialización de modelos necesarios para la gestión de pedidos.
         */
        $this->orderModel = $this->model('Order');
        $this->vendorModel = $this->model('Vendor');
        $this->productModel = $this->model('Product');
        
        /**
         * Middleware de Autenticación:
         * Garantiza que solo usuarios con sesión activa puedan interactuar con el sistema de órdenes.
         */
        if (!isset($_SESSION['user_id'])) { 
            header('location: ' . URLROOT . '/login'); 
            exit(); 
        }
    }

    /**
     * Dashboard Operativo (Manager):
     * Calcula dinámicamente el tiempo restante para cada proveedor y gestiona 
     * el acceso a la creación/edición de órdenes basado en el calendario de compras.
     */
    public function dashboard() {
        // Asegura que el usuario tenga un contexto de tienda seleccionado [cite: 2026-02-03]
        if (!isset($_SESSION['active_store_id'])) {
            header('location: ' . URLROOT . '/users/select_store');
            exit();
        }

        $todayName = date('l'); 
        $storeId = $_SESSION['active_store_id'];
        $full_schedule = $this->orderModel->getFullWeeklySchedule();
        
        foreach ($full_schedule as $s) {
            $scheduled_days = !empty($s->purchase_day) ? array_map('trim', explode(',', $s->purchase_day)) : [];
            
            // Algoritmo para determinar la proximidad del próximo día de pedido
            $s->days_until = null;
            if (!empty($scheduled_days)) {
                $distances = [];
                foreach ($scheduled_days as $day) {
                    if ($todayName === $day) {
                        $distances[] = 0;
                    } else {
                        $target = strtotime("next $day");
                        $distances[] = ceil(($target - time()) / 86400);
                    }
                }
                $s->days_until = min($distances);
            }

            /**
             * REGLA DE NEGOCIO: 
             * Las órdenes se desbloquean 2 días antes de la fecha oficial para permitir preparación. [cite: 2026-02-04]
             */
            $s->is_unlocked = ($s->days_until !== null && $s->days_until <= 2);
            $s->is_due_today = ($s->days_until === 0);

            // Permite al usuario editar una orden si ya fue enviada hoy, evitando duplicados [cite: 2026-02-04]
            $s->existing_order = $this->orderModel->getSubmittedOrderToday($storeId, $s->vendor_id);
        }

        $this->view('manager/order_dashboard', ['full_schedule' => $full_schedule]);
    }

    /**
     * Renderiza el formulario para realizar un nuevo pedido.
     */
    public function new($vendorId) {
        $vendor = $this->vendorModel->getVendorById($vendorId);
        if (!$vendor) {
            header('location: ' . URLROOT . '/orders/dashboard');
            exit();
        }

        $products = $this->orderModel->getProductsByVendor($vendorId);
        
        $data = [
            'vendor' => $vendor,
            'products' => $products
        ];
        $this->view('manager/place_order', $data);
    }

    /**
     * Permite la modificación de órdenes existentes.
     * Restricción de seguridad: Solo se permiten ediciones en el mismo día de creación. [cite: 2026-02-04]
     */
    public function edit($orderId) {
        $order = $this->orderModel->getOrderById($orderId);
        
        if (!$order || (date('Y-m-d', strtotime($order->created_at)) !== date('Y-m-d'))) {
            header('location: ' . URLROOT . '/orders/dashboard?error=edit_expired');
            exit();
        }

        $products = $this->orderModel->getProductsByVendor($order->vendor_id);
        $current_items = json_decode($order->order_details, true);

        $data = [
            'order' => $order,
            'products' => $products,
            'current_items' => $current_items
        ];
        $this->view('manager/edit_order', $data);
    }

    /**
     * Procesa la persistencia de datos (Creación o Actualización).
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'order_id'  => $_POST['order_id'] ?? null,
                'vendor_id' => $_POST['vendor_id'],
                'store_id'  => $_SESSION['active_store_id'],
                'items'     => $_POST['quantity']
            ];

            // Lógica de bifurcación: Actualiza si existe ID, de lo contrario inserta nuevo registro
            $success = !empty($data['order_id']) 
                ? $this->orderModel->updateOrder($data['order_id'], $data['items'])
                : $this->orderModel->submitOrder($data);

            if ($success) {
                header('location: ' . URLROOT . '/orders/history?success=saved');
                exit();
            }
        }
    }

    /**
     * Visualización del Historial/Reportes.
     * Aplica filtros de privacidad: Los managers solo ven datos de su propia tienda. [cite: 2026-02-03]
     */
    public function history() {
        $filters = ($_SESSION['user_role'] !== 'admin') ? ['store_id' => $_SESSION['active_store_id']] : [];
        $orders = $this->orderModel->getReports($filters);
        
        $viewPath = ($_SESSION['user_role'] === 'admin') ? 'admin/order_reports' : 'manager/order_history';
        $this->view($viewPath, ['orders' => $orders]);
    }

    /**
     * Genera la vista detallada de una orden específica.
     * Mapea el almacenamiento JSON a objetos de producto legibles para el usuario. [cite: 2026-02-04]
     */
    public function details($id) {
        $order = $this->orderModel->getOrderById($id);

        if (!$order) {
            die("Order not found.");
        }

        // Validación de pertenencia de datos: Previene acceso cruzado entre tiendas
        if ($_SESSION['user_role'] !== 'admin' && $order->store_id != $_SESSION['active_store_id']) {
            header('location: ' . URLROOT . '/orders/history');
            exit();
        }

        $items_with_names = [];
        if (!empty($order->order_details)) {
            $decoded_items = json_decode($order->order_details, true);
            foreach ($decoded_items as $productId => $quantity) {
                $product = $this->productModel->getProductById($productId); 
                $items_with_names[] = [
                    'name'     => $product ? $product->item_name : 'Unknown Product',
                    'unit'     => $product ? $product->item_code : 'N/A', 
                    'category' => $product ? $product->category : 'N/A',
                    'quantity' => $quantity
                ];
            }
        }

        $data = [
            'order' => $order,
            'items' => $items_with_names
        ];

        $viewPath = ($_SESSION['user_role'] === 'admin') ? 'admin/order_details' : 'manager/order_details';
        $this->view($viewPath, $data);
    }
}