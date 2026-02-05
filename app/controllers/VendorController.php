<?php
/**
 * VendorController: Gestiona el directorio de proveedores de la cadena.
 * Administra la relación con las entidades externas que suministran productos,
 * integrando paginación, búsqueda avanzada y borrado lógico.
 */
class VendorController extends Controller {
    private $vendorModel;

    public function __construct() {
        // Inicialización del modelo Vendor para interactuar con la persistencia de datos
        $this->vendorModel = $this->model('Vendor');
        
        /**
         * Middleware de Seguridad:
         * Valida la existencia de una sesión activa antes de permitir cualquier
         * operación sobre el catálogo de proveedores.
         */
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/login');
            exit();
        }
    }

    /**
     * Lista maestra de proveedores.
     * Implementa un sistema de paginación de alto rendimiento para manejar 
     * grandes directorios sin comprometer la velocidad de carga.
     */
    public function index() {
        // 1. Configuración de Paginación: Estándar operativo de 10 registros por página
        $limit = 10; 
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Filtro de Búsqueda: Captura de criterios para filtrado dinámico en tiempo real
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        /**
         * 3. Cálculo de Metadatos de Navegación:
         * Determina el volumen total de registros filtrados para construir la paginación.
         */
        $totalRows = $this->vendorModel->getTotalVendorsCount($searchTerm);
        $totalPages = ceil($totalRows / $limit);

        // 4. Extracción de Datos: Recupera el segmento específico de proveedores (LIMIT/OFFSET)
        $vendors = $this->vendorModel->getVendorsPaginated($searchTerm, $limit, $offset);
        
        $data = [
            'vendors'     => $vendors,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'searchTerm'  => $searchTerm
        ];

        // Renderiza la vista administrativa con el set de datos procesado
        $this->view('admin/all_vendors', $data);
    }

    /**
     * Registro de nuevos proveedores.
     * Implementa sanitización de caracteres especiales para prevenir inyecciones de script.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitización exhaustiva de inputs
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'name'        => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            // Ejecución de inserción con feedback mediante parámetros de URL
            if ($this->vendorModel->addVendor($data)) {
                header('location: ' . URLROOT . '/vendors?success=created');
                exit();
            }
        } else {
            // Despliegue del formulario de alta
            $this->view('admin/create_vendor');
        }
    }

    /**
     * Edición de perfiles de proveedor.
     * Permite la actualización de metadatos manteniendo la integridad del ID.
     */
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'id'          => $id,
                'name'        => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            if ($this->vendorModel->updateVendor($data)) {
                header('location: ' . URLROOT . '/vendors?success=updated');
                exit();
            } else {
                die('Error crítico: No se pudo actualizar el proveedor.');
            }
        } else {
            // Recuperación de estado actual para pre-poblar el formulario de edición
            $vendor = $this->vendorModel->getVendorById($id);
            
            $data = [
                'id'          => $vendor->id,
                'name'        => $vendor->name,
                'description' => $vendor->description
            ];
            
            $this->view('admin/edit_vendor', $data);
        }
    }

    /**
     * Gestión de estado del proveedor (Borrado Lógico).
     * En lugar de eliminar físicamente el registro (lo cual rompería la integridad de 
     * las órdenes históricas), el sistema utiliza una desactivación lógica.
     */
    public function delete($id) {
        if ($this->vendorModel->disableVendor($id)) {
            header('location: ' . URLROOT . '/vendors?success=disabled');
            exit();
        } else {
            die('Error crítico: No se pudo desactivar el proveedor.');
        }
    }
}