<?php
session_start();

// 1. CARGA DE ARCHIVOS BASE
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/Store.php';

// 2. DETECTAR LA URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'login';

// 3. ENRUTADOR DE RUTAS

// --- RUTAS DE AUTENTICACIÓN ---
if ($url == 'login') {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->login();
} 
elseif ($url == 'auth/authenticate') {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->authenticate();
} 
elseif ($url == 'logout') {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    $auth = new AuthController();
    $auth->logout();
}

// --- DASHBOARD PRINCIPAL ---
elseif ($url == 'dashboard') {
    require_once __DIR__ . '/app/controllers/DashboardController.php';
    $dash = new DashboardController();
    $dash->index();
} 

// --- RUTAS DE GESTIÓN DE ÓRDENES (MANAGER/ADMIN) ---
elseif ($url == 'orders/dashboard') {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->dashboard();
}
elseif ($url == 'orders/history') {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->history(); 
}
elseif (preg_match('/^orders\/new\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->new($matches[1]);
}
// --- NUEVA RUTA PARA EDITAR ÓRDENES ---
elseif (preg_match('/^orders\/edit\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->edit($matches[1]);
}
// ---------------------------------------
elseif ($url == 'orders/submit') {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->submit();
}
elseif ($url == 'orders/reports') {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->history(); 
}
elseif (preg_match('/^orders\/details\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/OrderController.php';
    $orderCtrl = new OrderController();
    $orderCtrl->details($matches[1]);
}

// --- RUTAS DE USUARIOS Y TIENDAS ---
elseif ($url == 'users/select_store') { 
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->select_store();
}
elseif (preg_match('/^users\/activate_store\/([0-9]+)$/', $url, $matches)) { 
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->activate_store($matches[1]);
}
elseif ($url == 'users') {
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->index();
} 
elseif ($url == 'users/create') {
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->create();
} 
elseif (preg_match('/^users\/edit\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->edit($matches[1]);
}
elseif (preg_match('/^users\/delete\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/UserController.php';
    $userCtrl = new UserController();
    $userCtrl->delete($matches[1]);
}

// --- RUTAS DE PRODUCTOS ---
elseif ($url == 'products') {
    require_once __DIR__ . '/app/controllers/ProductController.php';
    $productCtrl = new ProductController();
    $productCtrl->index();
}
elseif ($url == 'products/create') {
    require_once __DIR__ . '/app/controllers/ProductController.php';
    $productCtrl = new ProductController();
    $productCtrl->create();
}
elseif (preg_match('/^products\/edit\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/ProductController.php';
    $productCtrl = new ProductController();
    $productCtrl->edit($matches[1]);
}
elseif (preg_match('/^products\/delete\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/ProductController.php';
    $productCtrl = new ProductController();
    $productCtrl->delete($matches[1]);
}

// --- RUTAS DE VENDORS ---
elseif ($url == 'vendors') {
    require_once __DIR__ . '/app/controllers/VendorController.php';
    $vendorCtrl = new VendorController();
    $vendorCtrl->index();
}
elseif ($url == 'vendors/create') {
    require_once __DIR__ . '/app/controllers/VendorController.php';
    $vendorCtrl = new VendorController();
    $vendorCtrl->create();
}
elseif (preg_match('/^vendors\/edit\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/VendorController.php';
    $vendorCtrl = new VendorController();
    $vendorCtrl->edit($matches[1]);
}
elseif (preg_match('/^vendors\/delete\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/VendorController.php';
    $vendorCtrl = new VendorController();
    $vendorCtrl->delete($matches[1]);
}

// --- CONFIGURACIÓN DE COMPRAS ---
elseif ($url == 'purchase/settings') {
    require_once __DIR__ . '/app/controllers/PurchaseController.php';
    $purchaseCtrl = new PurchaseController();
    $purchaseCtrl->settings();
}
// --- RUTAS DE ELEMENTOS ARCHIVADOS (SOLO ADMIN) ---
elseif ($url == 'archive') {
    require_once __DIR__ . '/app/controllers/ArchiveController.php';
    $archiveCtrl = new Archive();
    $archiveCtrl->index();
}
elseif (preg_match('/^archive\/restoreProduct\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/ArchiveController.php';
    $archiveCtrl = new Archive();
    $archiveCtrl->restoreProduct($matches[1]);
}
elseif (preg_match('/^archive\/restoreVendor\/([0-9]+)$/', $url, $matches)) {
    require_once __DIR__ . '/app/controllers/ArchiveController.php';
    $archiveCtrl = new Archive();
    $archiveCtrl->restoreVendor($matches[1]);
}
// --- REDIRECCIÓN POR DEFECTO ---
else {
    header('location: ' . URLROOT . '/login');
    exit();
}