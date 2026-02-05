<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Order Report #<?php echo $data['order']->id; ?></h1>
                    <p class="text-muted">Detailed breakdown of the inventory request</p>
                </div>
                <div class="header-actions">
                    <button onclick="window.print()" class="btn-secondary">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                    <a href="<?php echo URLROOT; ?>/orders/reports" class="btn-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </header>

            <div class="details-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div class="info-card shadow-sm" style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #3498db;">
                    <h4 style="color: #7f8c8d; margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase;">Origin</h4>
                    <p style="margin: 0; font-weight: bold; font-size: 1.1rem;"><?php echo $data['order']->store_name; ?></p>
                    <small class="text-muted">Manager: <?php echo $data['order']->username; ?></small>
                </div>

                <div class="info-card shadow-sm" style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #e67e22;">
                    <h4 style="color: #7f8c8d; margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase;">Vendor</h4>
                    <p style="margin: 0; font-weight: bold; font-size: 1.1rem;"><?php echo $data['order']->vendor_name; ?></p>
                    <small class="text-muted">Request sent via System</small>
                </div>

                <div class="info-card shadow-sm" style="background: white; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #2ecc71;">
                    <h4 style="color: #7f8c8d; margin-bottom: 0.5rem; font-size: 0.9rem; text-transform: uppercase;">Submission Date</h4>
                    <p style="margin: 0; font-weight: bold; font-size: 1.1rem;"><?php echo date('M d, Y', strtotime($data['order']->created_at)); ?></p>
                    <small class="text-muted">Time: <?php echo date('h:i A', strtotime($data['order']->created_at)); ?></small>
                </div>
            </div>

            <section class="table-container shadow-sm" style="background: white; border-radius: 8px; overflow: hidden;">
                <table class="styled-table" style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 1.2rem; text-align: left;">Product Description</th>
                            <th style="padding: 1.2rem; text-align: center;">Unit/Code</th>
                            <th style="padding: 1.2rem; text-align: center;">Quantity Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['items'] as $item): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1.2rem;">
                                    <strong><?php echo $item['name']; ?></strong>
                                </td>
                                <td style="padding: 1.2rem; text-align: center;" class="text-muted">
                                    <?php echo $item['unit']; ?>
                                </td>
                                <td style="padding: 1.2rem; text-align: center;">
                                    <span style="background: #edf2f7; padding: 0.5rem 1rem; border-radius: 20px; font-weight: bold; color: #2d3748;">
                                        <?php echo number_format($item['quantity'], 2); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="order-footer" style="padding: 1.5rem; background: #fdfdfd; text-align: right; border-top: 2px solid #f1f1f1;">
                    <p style="margin: 0; color: #7f8c8d;">Total Items in Order: <strong><?php echo count($data['items']); ?></strong></p>
                </div>
            </section>
        </main>
        
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>