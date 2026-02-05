# 📦 Inventory & Procurement Management System

Un sistema robusto de gestión de inventarios y compras diseñado para operaciones de restaurantes multi-unidad (basado en el flujo de Shah's Kabob). Este proyecto implementa una arquitectura **MVC (Modelo-Vista-Controlador)** personalizada en PHP puro.

## 🚀 Características Principales

### 🔐 Control de Acceso basado en Roles (RBAC)
El sistema adapta su interfaz y permisos según el tipo de usuario:
* **Administradores**: Poseen control total sobre el sistema, incluyendo la gestión de usuarios, proveedores, productos y configuraciones globales [cite: 2026-02-03].
* **Usuarios (Managers)**: Tienen una interfaz simplificada y enfocada en la operación diaria. El sidebar para ellos está restringido y solo pueden ver [cite: 2026-02-03]:
    * **Order Dashboard**: Resumen de pedidos.
    * **Place Order**: Formulario para realizar nuevos pedidos a proveedores.
    * **My Order History**: Historial detallado de sus transacciones pasadas.

### 📊 Funcionalidades Técnicas
* **Arquitectura MVC**: Separación clara de responsabilidades para un código mantenible y escalable.
* **Base de Datos Relacional**: Gestión de relaciones Many-to-Many entre usuarios y sucursales (tiendas) para filtrar datos por ubicación.
* **Seguridad**: Uso de **PDO con Prepared Statements** para mitigar ataques de inyección SQL y manejo seguro de sesiones.

## 🛠️ Stack Tecnológico
* **Backend**: PHP 8.x
* **Base de Datos**: MySQL / MariaDB
* **Frontend**: HTML5, CSS3 (SCSS), JavaScript (ES6)
* **Arquitectura**: Custom MVC Pattern

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio**:
   ```bash
   git clone [https://github.com/12Jayson/inventory-ops-system.git](https://github.com/12Jayson/inventory-ops-system.git)