<?php
/**
 * UserController: Gestiona la administración de usuarios y contextos de tienda.
 * Maneja el ciclo de vida de las cuentas de staff, la asignación de permisos 
 * por sucursal y las funciones de recuperación de datos archivados.
 */
class UserController extends Controller {
    private $userModel;
    private $storeModel;
    private $productModel;
    private $vendorModel;

    public function __construct() {
        // Inicialización de modelos requeridos para la gestión integral de usuarios y activos
        $this->userModel = $this->model('User');
        $this->storeModel = $this->model('Store');
        $this->productModel = $this->model('Product');
        $this->vendorModel = $this->model('Vendor');

        /**
         * Middleware de Autenticación:
         * Bloquea el acceso a cualquier funcionalidad de este controlador
         * si no existe una sesión de usuario válida.
         */
        if (!isset($_SESSION['user_id'])) {
            header('location: ' . URLROOT . '/login');
            exit();
        }
    }

    // --- SECCIÓN DE ARCHIVO (ADMIN) ---

    /**
     * Gestión del Archivo de Sistema:
     * Carga una vista unificada con productos y proveedores desactivados
     * para permitir su restauración inmediata.
     */
    public function archive() {
        $this->isAdmin(); // Validación de privilegios
        
        $data = [
            'products' => $this->productModel->getArchivedProducts(),
            'vendors'  => $this->vendorModel->getArchivedVendors()
        ];

        $this->view('admin/archive', $data);
    }

    /**
     * Restauración de Productos:
     * Reintegra un producto al catálogo activo actualizando su bandera de estado.
     */
    public function restoreProduct($id) {
        $this->isAdmin();
        
        if ($this->productModel->restoreProduct($id)) {
            header('location: ' . URLROOT . '/users/archive?success=restored');
            exit();
        }
    }

    /**
     * Restauración de Proveedores:
     * Habilita de nuevo a un proveedor para que pueda recibir órdenes de compra.
     */
    public function restoreVendor($id) {
        $this->isAdmin();
        
        if ($this->vendorModel->restoreVendor($id)) {
            header('location: ' . URLROOT . '/users/archive?success=restored');
            exit();
        }
    }

    // --- SECCIÓN PARA OPERACIONES (Selección de Tienda) ---

    /**
     * Interfaz de Selección de Tienda:
     * Determina a qué sucursales tiene permiso el usuario y gestiona el flujo de trabajo:
     * 1. Si no tiene tiendas: Bloquea el acceso.
     * 2. Si tiene una sola tienda: La activa automáticamente para agilizar el login.
     * 3. Si tiene múltiples: Muestra el selector de contexto. [cite: 2026-02-03]
     */
    public function select_store() {
        $userStoreIds = $this->userModel->getUserStoreIds($_SESSION['user_id']);
        
        if (empty($userStoreIds)) {
            die("Acceso denegado: No cuenta con sucursales asignadas.");
        }

        // UX: Auto-activación si solo existe una opción disponible
        if (count($userStoreIds) === 1) {
            $this->activate_store($userStoreIds[0]);
            return;
        }

        $stores = [];
        foreach($userStoreIds as $storeId) {
            $storeData = $this->storeModel->getStoreById($storeId);
            if ($storeData) $stores[] = $storeData;
        }

        $data = ['stores' => $stores];
        $this->view('manager/select_store', $data);
    }

    /**
     * Establece el contexto de tienda activa en la sesión global.
     * Valida que el usuario tenga permiso explícito sobre la tienda solicitada.
     */
    public function activate_store($id) {
        $store = $this->storeModel->getStoreById($id);
        $allowedStores = $this->userModel->getUserStoreIds($_SESSION['user_id']);
        
        // Verificación de integridad: Evita que un usuario manipule el ID en la URL
        if ($store && (in_array($id, $allowedStores) || $_SESSION['user_role'] === 'admin')) {
            $_SESSION['active_store_id'] = $store->id;
            $_SESSION['active_store_name'] = $store->name;
            
            header('location: ' . URLROOT . '/orders/dashboard');
            exit();
        } else {
            die('Error de seguridad: No tiene permisos sobre esta sucursal.');
        }
    }

    // --- SECCIÓN ADMINISTRATIVA (Solo Admin) ---

    /**
     * Middleware interno para validación de Rol Administrativo.
     */
    private function isAdmin() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('location: ' . URLROOT . '/dashboard');
            exit();
        }
    }

    /**
     * CRUD: Listado de usuarios con búsqueda y paginación.
     */
    public function index() {
        $this->isAdmin();
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $totalRows = $this->userModel->getTotalUsersCount($searchTerm);
        $totalPages = ceil($totalRows / $limit);
        $users = $this->userModel->getUsersPaginated($searchTerm, $limit, $offset);
        
        $data = [
            'users'       => $users,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'searchTerm'  => $searchTerm
        ];

        $this->view('admin/users_list', $data);
    }

    /**
     * CRUD: Registro de nuevos usuarios y asignación de permisos de tienda.
     */
    public function create() {
        $this->isAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'username' => trim($_POST['username']),
                'email'    => trim($_POST['email']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hashing seguro
                'role'     => $_POST['role'],
                'stores'   => isset($_POST['stores']) ? $_POST['stores'] : []
            ];

            $userId = $this->userModel->registerAndReturnId($data);

            if ($userId) {
                // Lógica de asociación de tiendas (M:M)
                if ($data['role'] === 'user' && !empty($data['stores'])) {
                    foreach ($data['stores'] as $storeId) {
                        $this->userModel->assignStore($userId, $storeId);
                    }
                }
                header('location: ' . URLROOT . '/users?success=created');
                exit();
            }
        } else {
            $data = [
                'stores' => $this->storeModel->getStores(),
                'role'   => 'user'
            ];
            $this->view('admin/create_user', $data);
        }
    }

    /**
     * CRUD: Actualización de perfiles y recalibración de permisos.
     */
    public function edit($id) {
        $this->isAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'id'       => $id,
                'username' => trim($_POST['username']),
                'email'    => trim($_POST['email']),
                'role'     => $_POST['role'],
                'stores'   => isset($_POST['stores']) ? $_POST['stores'] : [],
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null
            ];

            if ($this->userModel->updateUser($data)) {
                // Sincronización de tiendas: Limpiar y re-asignar para evitar inconsistencias
                $this->userModel->clearUserStores($id);
                if ($data['role'] === 'user' && !empty($data['stores'])) {
                    foreach ($data['stores'] as $storeId) {
                        $this->userModel->assignStore($id, $storeId);
                    }
                }
                header('location: ' . URLROOT . '/users?success=updated');
                exit();
            }
        } else {
            $user = $this->userModel->getUserById($id);
            if (!$user) { header('location: ' . URLROOT . '/users'); exit(); }

            $data = [
                'user'           => $user,
                'stores'         => $this->storeModel->getStores(),
                'current_stores' => $this->userModel->getUserStoreIds($id)
            ];
            $this->view('admin/edit_user', $data);
        }
    }

    /**
     * CRUD: Eliminación de cuentas.
     * Protección de Autoeleminación: Impide que un Admin borre su propia cuenta activa.
     */
    public function delete($id) {
        $this->isAdmin();
        if ($id == $_SESSION['user_id']) {
            header('location: ' . URLROOT . '/users?error=self_delete');
            exit();
        }

        if ($this->userModel->deleteUser($id)) {
            header('location: ' . URLROOT . '/users?success=deleted');
            exit();
        }
    }
}