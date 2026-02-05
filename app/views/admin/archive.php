<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Archived Items</h1>
                    <p>Restore deactivated products and vendors to the system.</p>
                </div>
            </header>

            <div class="stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
                <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 10px; border-left: 5px solid #f39c12; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <span style="color: #777; font-size: 0.9rem;">Archived Products</span>
                    <h2 style="margin: 5px 0; color: #333;"><?php echo count($data['products']); ?></h2>
                </div>
                <div class="stat-card" style="background: #fff; padding: 20px; border-radius: 10px; border-left: 5px solid #3498db; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <span style="color: #777; font-size: 0.9rem;">Archived Vendors</span>
                    <h2 style="margin: 5px 0; color: #333;"><?php echo count($data['vendors']); ?></h2>
                </div>
            </div>

            <section class="table-container" style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                
                <div style="margin-bottom: 25px;">
                    <h3 style="color: #2c3e50; margin-bottom: 15px;"><i class="fas fa-box" style="margin-right: 10px;"></i> Deactivated Products</h3>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Vendor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['products'])): ?>
                                <?php foreach($data['products'] as $product): ?>
                                    <tr>
                                        <td><strong><?php echo $product->item_name; ?></strong></td>
                                        <td><?php echo $product->category; ?></td>
                                        <td><?php echo $product->vendor_name; ?></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/archive/restoreProduct/<?php echo $product->id; ?>" 
                                            class="btn-primary" style="background: #27ae60; padding: 5px 12px; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: #fff;">
                                                <i class="fas fa-undo"></i> Reactivate
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" style="text-align: center; color: #999; padding: 20px;">No archived products.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">

                <div>
                    <h3 style="color: #2c3e50; margin-bottom: 15px;"><i class="fas fa-truck" style="margin-right: 10px;"></i> Deactivated Vendors</h3>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['vendors'])): ?>
                                <?php foreach($data['vendors'] as $vendor): ?>
                                    <tr>
                                        <td><strong><?php echo $vendor->name; ?></strong></td>
                                        <td><span class="status-badge inactive" style="background: #fadbd8; color: #e74c3c; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">Inactive</span></td>
                                        <td>
                                            <a href="<?php echo URLROOT; ?>/archive/restoreVendor/<?php echo $vendor->id; ?>" 
                                            class="btn-primary" style="background: #27ae60; padding: 5px 12px; font-size: 0.8rem; text-decoration: none; border-radius: 4px; color: #fff;">
                                                <i class="fas fa-undo"></i> Reactivate
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" style="text-align: center; color: #999; padding: 20px;">No archived vendors.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>