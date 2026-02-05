<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Vendors List</h1>
                    <p>Manage your active suppliers. Deactivated vendors are hidden but their records remain.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/vendors/create" class="btn-primary">
                    <i class="fas fa-plus"></i> Add New Vendor
                </a>
            </header>

            <section class="search-container" style="margin-bottom: 25px;">
                <form action="<?php echo URLROOT; ?>/vendors" method="GET" style="display: flex; gap: 10px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="flex: 1; position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #aaa;"></i>
                        <input type="text" name="search" 
                               placeholder="Search vendor by name..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                               style="width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                    </div>
                    <button type="submit" class="btn-primary" style="padding: 0 25px;">Search</button>
                    <?php if(!empty($_GET['search'])): ?>
                        <a href="<?php echo URLROOT; ?>/vendors" class="btn-secondary" style="display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #333; border: 1px solid #ddd; padding: 0 15px; border-radius: 4px; text-decoration: none;">Clear</a>
                    <?php endif; ?>
                </form>
            </section>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success" style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeeba;">
                    <i class="fas fa-info-circle"></i> 
                    <?php 
                        if($_GET['success'] == 'created') echo "Vendor created successfully.";
                        if($_GET['success'] == 'disabled') echo "Vendor has been deactivated and archived.";
                        if($_GET['success'] == 'updated') echo "Vendor updated successfully.";
                    ?>
                </div>
            <?php endif; ?>

            <section class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Description</th>
                            <th style="text-align: center;">Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['vendors'])): ?>
                            <?php foreach($data['vendors'] as $vendor): ?>
                                <tr>
                                    <td><?php echo $vendor->id; ?></td>
                                    <td><strong><?php echo $vendor->name; ?></strong></td>
                                    <td style="max-width: 350px; color: #666; font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?php echo $vendor->description ? $vendor->description : '<span class="text-muted">No description</span>'; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge" style="background: #e9ecef; color: #495057; padding: 5px 12px; border-radius: 15px; font-weight: 600;">
                                            <?php echo $vendor->product_count; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions" style="display: flex; gap: 15px;">
                                            <a href="<?php echo URLROOT; ?>/vendors/edit/<?php echo $vendor->id; ?>" class="edit-link" title="Edit">
                                                <i class="fas fa-edit" style="color: #3498db;"></i>
                                            </a>
                                            <form action="<?php echo URLROOT; ?>/vendors/delete/<?php echo $vendor->id; ?>" method="POST" onsubmit="return confirm('Are you sure you want to deactivate <?php echo $vendor->name; ?>?')">
                                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;" title="Deactivate">
                                                    <i class="fas fa-ban" style="color: #e67e22;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                                    <i class="fas fa-box-open" style="font-size: 2.2rem; display: block; margin-bottom: 10px;"></i>
                                    No vendors found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <?php if($data['totalPages'] > 1): ?>
                <div class="pagination" style="margin-top: 20px; display: flex; justify-content: center; gap: 5px;">
                    <?php for($i = 1; $i <= $data['totalPages']; $i++): ?>
                        <a href="<?php echo URLROOT; ?>/vendors?page=<?php echo $i; ?><?php echo !empty($data['searchTerm']) ? '&search='.$data['searchTerm'] : ''; ?>" 
                           style="padding: 8px 16px; border-radius: 4px; border: 1px solid #ddd; text-decoration: none; <?php echo ($i == $data['currentPage']) ? 'background: #3498db; color: #fff;' : 'background: #fff; color: #333;'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>