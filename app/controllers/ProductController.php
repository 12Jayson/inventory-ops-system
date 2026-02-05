<?php
/**
 * ProductController: Gestiona el catálogo maestro de productos.
 * Este controlador permite a los administradores administrar el inventario,
 * implementando búsqueda avanzada, paginación de resultados y sanitización de datos.
 */
class ProductController extends Controller {
    private $productModel;
    private $vendorModel;

    public function __construct() {
        // Inicialización de modelos para la gestión de productos y sus proveedores asociados
        $this->productModel = $this->model('Product');
        $this->vendorModel = $this->model('Vendor');

        /**
         * Middleware de Autenticación Simple:
         * Protege las rutas de gestión de productos de accesos no autorizados.
         */
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/login');
            exit();
        }
    }

    /**
     * Lista todos los productos con soporte para búsqueda y paginación.
     * Optimiza la carga de datos mediante el uso de LIMIT y OFFSET en SQL.
     */
    public function index() {
        // Configuración de Paginación: Define el volumen de datos por vista
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Captura del término de búsqueda para filtrado dinámico
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        /**
         * Recuperación de metadatos de paginación y conjunto de resultados.
         * Se calcula el total de páginas basado en el conteo de filas que coinciden con la búsqueda.
         */
        $totalRows = $this->productModel->getTotalProductsCount($searchTerm);
        $totalPages = ceil($totalRows / $limit);
        $products = $this->productModel->getProductsPaginated($searchTerm, $limit, $offset);
        
        $data = [
            'products'    => $products,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'searchTerm'  => $searchTerm
        ];

        $this->view('admin/all_products', $data);
    }

    /**
     * Registra un nuevo producto en el sistema.
     * Incluye una capa de sanitización de entradas para prevenir ataques XSS.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitización global de los datos recibidos por POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'vendor_id' => $_POST['vendor_id'],
                'item_name' => trim($_POST['item_name']),
                'category'  => trim($_POST['category']),
                'item_code' => trim($_POST['item_code'])
            ];

            // Persistencia en base de datos y redirección con feedback
            if ($this->productModel->addProduct($data)) {
                header('location: ' . URLROOT . '/products?success=added');
                exit();
            }
        } else {
            /**
             * Carga los proveedores activos para poblar el selector dinámico 
             * en el formulario de creación.
             */
            $data = ['vendors' => $this->vendorModel->getVendors() ?? []];
            $this->view('admin/create_product', $data);
        }
    }

    /**
     * Edita la información de un producto existente.
     * Mantiene la integridad referencial al permitir cambiar el proveedor asociado.
     */
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'id' => $id,
                'vendor_id' => $_POST['vendor_id'],
                'item_name' => trim($_POST['item_name']),
                'category'  => trim($_POST['category']),
                'item_code' => trim($_POST['item_code'])
            ];

            if ($this->productModel->updateProduct($data)) {
                header('location: ' . URLROOT . '/products?success=updated');
                exit();
            }
        } else {
            // Recupera los datos actuales del producto para precargar el formulario
            $product = $this->productModel->getProductById($id);
            $vendors = $this->vendorModel->getVendors();
            
            $data = [
                'id' => $product->id,
                'vendor_id' => $product->vendor_id,
                'item_name' => $product->item_name,
                'category' => $product->category,
                'item_code' => $product->item_code,
                'vendors' => $vendors
            ];
            $this->view('admin/edit_product', $data);
        }
    }

    /**
     * Elimina (o archiva) un producto del catálogo.
     */
    public function delete($id) {
        if ($this->productModel->deleteProduct($id)) {
            header('location: ' . URLROOT . '/products?success=deleted');
            exit();
        }
    }
}