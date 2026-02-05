<?php
/**
 * Clase Database: Wrapper personalizado para PDO (PHP Data Objects).
 * Esta clase centraliza la conexión y ejecución de consultas, proporcionando
 * una interfaz segura mediante Prepared Statements y gestión de transacciones.
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;   // Database Handler
    private $stmt;  // Statement Handler
    private $error;

    public function __construct() {
        // Configuración del Data Source Name (DSN)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        
        /**
         * Opciones de PDO:
         * - ATTR_PERSISTENT: Aumenta el rendimiento manteniendo conexiones abiertas.
         * - ATTR_ERRMODE: Lanza excepciones en caso de error para una depuración precisa.
         */
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Intento de conexión con manejo de excepciones
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("❌ ERROR DE CONEXIÓN A BASE DE DATOS: " . $this->error);
        }
    }

    /**
     * Prepara una consulta SQL para su ejecución segura.
     */
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Vincula parámetros a la consulta preparada (Binding).
     * Detecta automáticamente el tipo de dato para aplicar el filtro de seguridad correcto.
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):  $type = PDO::PARAM_INT;  break;
                case is_bool($value): $type = PDO::PARAM_BOOL; break;
                case is_null($value): $type = PDO::PARAM_NULL; break;
                default:              $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Ejecuta la sentencia previamente preparada.
     */
    public function execute() {
        return $this->stmt->execute();
    }

    /**
     * Obtiene el conjunto de resultados como un array de objetos anónimos.
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Recupera un único registro. Ideal para procesos de autenticación o perfiles.
     */
    public function single() {
        try {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die("❌ ERROR EN CONSULTA (single): " . $e->getMessage());
        }
    }

    /**
     * Devuelve el ID generado en la última operación de inserción.
     */
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    /**
     * Retorna el número de filas afectadas por la última consulta.
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // --- GESTIÓN DE TRANSACCIONES (ACID Compliance) ---

    /**
     * Inicia una transacción. Bloquea las operaciones hasta que se confirme o revierta.
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    /**
     * Confirma las operaciones pendientes en la base de datos de forma permanente.
     */
    public function commit() {
        return $this->dbh->commit();
    }

    /**
     * Revierte cualquier cambio realizado durante la transacción en caso de error.
     */
    public function rollBack() {
        return $this->dbh->rollBack();
    }
}