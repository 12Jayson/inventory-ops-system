<?php
/**
 * Clase Base Controller: El motor del patrón MVC.
 * Esta clase proporciona los métodos fundamentales para cargar modelos y 
 * renderizar vistas, permitiendo que todos los controladores hijos hereden 
 * una funcionalidad estandarizada y limpia.
 */
class Controller {
    
    /**
     * Carga de Modelos:
     * Instancia dinámicamente el modelo solicitado desde la carpeta 'models'.
     * * @param string $model Nombre de la clase del modelo a cargar.
     * @return object Una nueva instancia del modelo solicitado.
     */
    public function model($model) {
        // Localización dinámica del archivo del modelo basado en la convención de nombres
        require_once __DIR__ . '/../models/' . $model . '.php';
        
        // Retorno de la instancia para ser inyectada en las propiedades del controlador hijo
        return new $model();
    }

    /**
     * Renderización de Vistas:
     * Carga el archivo de interfaz de usuario y transfiere los datos necesarios
     * para la visualización dinámica.
     * * @param string $view Nombre de la vista (ruta relativa a la carpeta 'views').
     * @param array $data Conjunto de datos opcional para pasar a la interfaz.
     */
    public function view($view, $data = []) {
        // Verificación de integridad: Comprueba la existencia física del template antes de cargarlo
        if (file_exists(__DIR__ . '/../views/' . $view . '.php')) {
            
            /**
             * Función extract(): 
             * Transforma las llaves de un array asociativo en variables individuales.
             * Ejemplo: ['title' => 'Home'] se convierte en la variable $title dentro de la vista.
             */
            extract($data);
            
            // Inclusión del archivo de vista para su procesamiento por el motor de PHP
            require_once __DIR__ . '/../views/' . $view . '.php';
        } else {
            /**
             * Gestión de Errores Críticos:
             * Si la vista no se localiza, se detiene la ejecución para evitar fallos silenciosos.
             */
            die('Error crítico: La capa de presentación solicitada no existe.');
        }
    }
}