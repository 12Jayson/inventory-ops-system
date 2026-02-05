<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Manager Order Reports</h1>
                    <p class="welcome-msg">View and filter all store submissions</p>
                </div>
            </header>

            <section class="details-card shadow-sm" style="margin-bottom: 2rem;">
                <form action="<?php echo URLROOT; ?>/orders/reports" method="GET" class="filter-form-grid">
                    <div class="meta-item">
                        <label><i class="fas fa-user"></i> Search Manager</label>
                        <input type="text" name="username" class="form-control"
                               value="<?php echo $data['filters']['username'] ?? ''; ?>" 
                               placeholder="Search by name...">
                    </div>
                    
                    <div class="meta-item">
                        <label><i class="fas fa-calendar-day"></i> Filter by Date</label>
                        <input type="date" name="date" class="form-control"
                               value="<?php echo $data['filters']['date'] ?? ''; ?>">
                    </div>

                    <div class="form-actions" style="margin-top: 0; border: none; align-items: flex-end;">
                        <button type="submit" class="btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?php echo URLROOT; ?>/orders/reports" class="btn-secondary btn-sm">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </form>
            </section>

            <section class="table-container shadow-sm">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Manager</th>
                            <th>Store</th>
                            <th>Vendor</th> 
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])) : ?>
                            <?php foreach($data['orders'] as $order): ?>
                                <tr>
                                    <td>
                                        <span class="d-block"><strong><?php echo date('M d, Y', strtotime($order->created_at)); ?></strong></span>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($order->created_at)); ?></small>
                                    </td>
                                    <td><?php echo $order->username; ?></td>
                                    <td><?php echo $order->store_name; ?></td>
                                    <td>
                                        <span class="day-badge"><?php echo $order->vendor_name ?? 'N/A'; ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                            $currentStatus = $order->status ?? 'submitted';
                                            $statusClass = ($currentStatus == 'submitted') ? 'success' : 'info';
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($currentStatus); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" 
                                           class="btn-icon" 
                                           title="View Items">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">No reports found for the selected filters.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
        
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>