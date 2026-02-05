<?php require APPROOT . '/views/layouts/header.php'; ?>
<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <h1>Add New Product</h1>
            </header>

            <section class="card-form-container">
                <form action="<?php echo URLROOT; ?>/products/create" method="POST" class="styled-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="item_name">Vendor Item Name</label>
                            <input type="text" id="item_name" name="item_name" required placeholder="e.g. Basmati Rice">
                        </div>

                        <div class="form-group">
                            <label for="item_code">Item Code</label>
                            <input type="text" id="item_code" name="item_code" required placeholder="e.g. SKU-1001">
                        </div>

                        <div class="form-group">
                            <label for="vendor_id">Vendor / Supplier</label>
                            <select name="vendor_id" id="vendor_id" required>
                                <option value="" disabled selected>Select a vendor...</option>
                                <?php foreach($data['vendors'] as $vendor): ?>
                                    <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" required placeholder="e.g. Grains / Dry Goods">
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 20px;">
                        <button type="submit" class="btn-primary">Save Product</button>
                        <a href="<?php echo URLROOT; ?>/products" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>