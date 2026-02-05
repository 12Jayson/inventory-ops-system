<?php
/**
 * AuthController: Gestiona el ciclo de vida de la sesión del usuario.
 * Se encarga de la autenticación, el control de acceso inicial y el cierre 
 * seguro de sesiones, aplicando redirecciones basadas en privilegios.
 */
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        // Inicializa el modelo de usuario para consultas de credenciales
        $this->userModel = $this->model('User');
    }

    /**
     * Muestra la interfaz de inicio de sesión.
     * Incluye una validación para evitar que usuarios ya autenticados 
     * vuelvan a ver el formulario de login.
     */
    public function login() {
        // Redirección proactiva: Si el usuario ya está logueado, lo enviamos a su área de trabajo
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . URLROOT . '/dashboard');
                exit();
            } elseif (isset($_SESSION['active_store_id'])) {
                // Usuario regular con tienda ya seleccionada [cite: 2026-02-03]
                header('Location: ' . URLROOT . '/orders/dashboard');
                exit();
            }
        }
        $this->view('auth/login');
    }

    /**
     * Procesa las credenciales enviadas por el formulario.
     * Implementa password_verify para manejar el hashing seguro de contraseñas.
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitización básica de inputs
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $user = $this->userModel->findUserByEmail($email);

            // Verificación de existencia de usuario y validación de hash
            if ($user && password_verify($password, $user->password)) {
                
                /**
                 * Seguridad: Regeneramos el ID de sesión tras un login exitoso
                 * para mitigar ataques de fijación de sesión (Session Fixation).
                 */
                session_regenerate_id(true);

                // Persistencia de datos esenciales en la sesión global
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['user_role'] = $user->role; 

                // Ejecuta la lógica de redirección según el nivel de privilegios
                $this->redirectByUserRole($user->role);
                
            } else {
                // Gestión de errores descriptivos para la interfaz de usuario
                $error = !$user ? 'user_not_found' : 'wrong_password';
                header('location: ' . URLROOT . '/login?error=' . $error . '&email=' . $email);
                exit();
            }
        }
    }

    /**
     * Lógica de Redirección Post-Autenticación.
     * Separa el flujo operativo: Administradores a métricas, Usuarios a selección de tienda.
     */
    private function redirectByUserRole($role) {
        if ($role === 'admin') {
            header('Location: ' . URLROOT . '/dashboard');
        } else {
            /**
             * El flujo para usuarios regulares requiere la selección de una tienda activa.
             * UserController manejará si el usuario tiene una o varias tiendas asignadas.
             */
            header('Location: ' . URLROOT . '/users/select_store');
        }
        exit();
    }

    /**
     * Cierre de sesión seguro.
     * Limpia variables de sesión, destruye la cookie del navegador y finaliza el proceso en servidor.
     */
    public function logout() {
        if(session_status() === PHP_SESSION_NONE) session_start();
        
        // Vaciado completo del array de sesión
        $_SESSION = array();
        
        // Eliminación de la cookie de sesión en el cliente para mayor seguridad
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrucción física del archivo de sesión en el servidor
        session_destroy();
        header('location: ' . URLROOT . '/login');
        exit();
    }
}