<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Admin Dashboard</h1>
                    <p class="welcome-msg">Welcome back, <strong><?php echo $data['username']; ?></strong></p>
                </div>
                <div class="header-status">
                    <span class="role-badge"><?php echo strtoupper($data['role']); ?></span>
                </div>
            </header>

            <section class="purchase-alerts">
                <div class="details-card shadow-sm">
                    <div class="order-card-header">
                        <h3>
                            <i class="fas fa-calendar-check text-accent"></i> 
                            Orders to Place Today
                        </h3>
                        <span class="badge info">
                            <?php echo date('l, F jS'); ?>
                        </span>
                    </div>

                    <?php if(!empty($data['todays_purchases'])): ?>
                        <div class="task-grid">
                            <?php foreach($data['todays_purchases'] as $purchase): ?>
                                <div class="task-card">
                                    <div class="task-accent"></div>
                                    <h4><?php echo $purchase->vendor_name; ?></h4>
                                    <p class="task-meta">
                                        <strong>Frequency:</strong> <?php echo ucwords(str_replace('_', ' ', $purchase->frequency)); ?>
                                    </p>
                                    <?php if($purchase->notes): ?>
                                        <p class="task-note">
                                            "<?php echo $purchase->notes; ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-coffee"></i>
                            <p>No purchases scheduled for today. Relax!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="stats-grid">
                <div class="stat-card products">
                    <h4>Total Products</h4>
                    <p class="number"><?php echo $data['total_products']; ?></p>
                    <a href="<?php echo URLROOT; ?>/products" class="stat-link">View Inventory</a>
                </div>
                
                <div class="stat-card vendors">
                    <h4>Total Vendors</h4>
                    <p class="number"><?php echo $data['total_vendors']; ?></p>
                    <a href="<?php echo URLROOT; ?>/vendors" class="stat-link">View Vendors</a>
                </div>

                <div class="stat-card users">
                    <h4>System Users</h4>
                    <p class="number"><?php echo $data['total_users']; ?></p>
                    <a href="<?php echo URLROOT; ?>/users" class="stat-link">Manage Access</a>
                </div>

                <div class="stat-card orders">
                    <h4>Order Reports</h4>
                    <p class="number"><?php echo $data['total_orders'] ?? '0'; ?></p>
                    <a href="<?php echo URLROOT; ?>/orders/reports" class="stat-link">View All Reports</a>
                </div>
            </section>

            <section class="quick-actions-section">
                <h3 class="section-subtitle">Quick Actions</h3>
                <div class="form-actions" style="justify-content: flex-start; border: none; padding: 0;">
                    <a href="<?php echo URLROOT; ?>/products/create" class="btn-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                    <a href="<?php echo URLROOT; ?>/vendors/create" class="btn-secondary">
                        <i class="fas fa-truck"></i> Add Vendor
                    </a>
                    <a href="<?php echo URLROOT; ?>/purchase/settings" class="btn-outline">
                        <i class="fas fa-calendar-alt"></i> Set Schedule
                    </a>
                </div>
            </section>
        </main>

        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>