<?php
/**
 * SearchController: Motor de búsqueda global de la aplicación.
 * Este componente centraliza la lógica de filtrado dinámico para diferentes 
 * entidades del sistema, permitiendo búsquedas transversales mediante PDO.
 */
class SearchController {
    private $db;

    /**
     * Inyección de dependencia de la base de datos.
     * @param PDO $db_connection Conexión activa a la base de datos.
     */
    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    /**
     * Procesa la solicitud de búsqueda basada en el tipo de entidad y el término.
     * * @param string $type Entidad en la cual buscar (products, vendors, users).
     * @param string $query Término o palabra clave de búsqueda.
     * @return array Conjunto de resultados como objetos anónimos.
     */
    public function handleSearch($type, $query) {
        // Preparación del término para concordancia parcial en SQL
        $searchTerm = "%$query%";
        
        switch ($type) {
            case 'products':
                /**
                 * Búsqueda Avanzada de Productos:
                 * Realiza un JOIN con proveedores para permitir encontrar artículos
                 * por nombre, categoría, código SKU o el nombre del fabricante.
                 */
                $sql = "SELECT p.*, v.name as vendor_name 
                        FROM products p 
                        JOIN vendors v ON p.vendor_id = v.id 
                        WHERE p.item_name LIKE :search 
                        OR p.category LIKE :search 
                        OR p.item_code LIKE :search 
                        OR v.name LIKE :search";
                break;

            case 'vendors':
                /**
                 * Búsqueda de Proveedores:
                 * Filtrado simple por nombre comercial.
                 */
                $sql = "SELECT * FROM vendors WHERE name LIKE :search";
                break;

            case 'users':
                /**
                 * Búsqueda de Usuarios/Staff:
                 * Permite la localización de personal por nombre o correo electrónico.
                 */
                $sql = "SELECT * FROM users WHERE name LIKE :search OR email LIKE :search";
                break;

            default:
                // Retorna un array vacío si el tipo de búsqueda no está definido
                return [];
        }

        /**
         * Ejecución Segura:
         * Utiliza Prepared Statements para mitigar riesgos de Inyección SQL (SQLi).
         */
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', $searchTerm);
        $stmt->execute();
        
        // Retorno de resultados formateados como objetos para consistencia con el MVC
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}