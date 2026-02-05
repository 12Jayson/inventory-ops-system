<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>Shahs Inventory</h2>
    </div>

    <?php if (isset($_SESSION['active_store_name'])): ?>
    <div class="active-store-panel"> <div class="store-info">
            <span class="store-label">Current Store</span>
            <span class="store-name"><?php echo $_SESSION['active_store_name']; ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <nav class="sidebar-nav">
        <a href="<?php echo ($_SESSION['user_role'] === 'admin') ? URLROOT.'/dashboard' : URLROOT.'/orders/dashboard'; ?>" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <?php if ($_SESSION['user_role'] === 'admin') : ?>
            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/orders/reports" class="nav-link">
                    <i class="fas fa-file-invoice"></i>
                    <span>Order Reports</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/purchase/settings" class="nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Purchase Schedule</span>
                </a>
            </li>

<li class="nav-item">
    <a href="<?php echo URLROOT; ?>/archive" class="nav-link">
        <i class="fas fa-archive"></i>
        <span>Archived Items</span>
    </a>
</li>

            <div class="nav-group dropdown">
                <div class="group-title dropdown-toggle">
                    <span>User Management</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="<?php echo URLROOT; ?>/users/create" class="nav-link">Create User</a>
                    <a href="<?php echo URLROOT; ?>/users" class="nav-link">All Users</a>
                </div>
            </div>

            <div class="nav-group dropdown">
                <div class="group-title dropdown-toggle">
                    <span>Product Management</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="<?php echo URLROOT; ?>/products/create" class="nav-link">Create Product</a>
                    <a href="<?php echo URLROOT; ?>/products" class="nav-link">All Products</a>
                </div>
            </div>

            <div class="nav-group dropdown">
                <div class="group-title dropdown-toggle">
                    <span>Vendor Management</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="<?php echo URLROOT; ?>/vendors/create" class="nav-link">Create Vendor</a>
                    <a href="<?php echo URLROOT; ?>/vendors" class="nav-link">All Vendors</a>
                </div>
            </div>

        <?php else : ?>
            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/orders/dashboard" class="nav-link">
                    <i class="fas fa-shopping-basket"></i>
                    <span>Place New Order</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/orders/history" class="nav-link">
                    <i class="fas fa-history"></i>
                    <span>My Order History</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo URLROOT; ?>/users/select_store" class="nav-link change-store-link btn-switch-store">
                    <i class="fas fa-random"></i>
                    <span>Switch Store</span>
                </a>
            </li>
        <?php endif; ?>

        <div class="sidebar-footer">
            <a href="<?php echo URLROOT; ?>/logout" class="logout-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
</aside>