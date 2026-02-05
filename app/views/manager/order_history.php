<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>My Order History</h1>
                    <p class="welcome-msg">Review your past inventory requests</p>
                </div>
            </header>

            <div class="table-container shadow-sm">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date & Time</th>
                            <th>Vendor</th>
                            <th>Total Items</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['orders'])): ?>
                            <?php foreach($data['orders'] as $order): ?>
                                <tr>
                                    <td>#<?php echo $order->id; ?></td>
                                    <td>
                                        <span class="d-block"><?php echo date('M d, Y', strtotime($order->created_at)); ?></span>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($order->created_at)); ?></small>
                                    </td>
                                    <td><strong><?php echo $order->vendor_name; ?></strong></td>
                                    <td>
                                        <?php 
                                            $details = json_decode($order->order_details, true);
                                            $count = is_array($details) ? count($details) : 0;
                                            echo $count . ($count === 1 ? ' item' : ' items');
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge success">Submitted</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo URLROOT; ?>/orders/details/<?php echo $order->id; ?>" 
                                           class="btn-secondary btn-sm">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="empty-row">
                                <td colspan="6" class="text-center">You haven't placed any orders yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>