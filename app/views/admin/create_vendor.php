<?php require APPROOT . '/views/layouts/header.php'; ?>
<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <h1>Add New Vendor</h1>
            </header>

            <section class="card-form-container">
                <form action="<?php echo URLROOT; ?>/vendors/create" method="POST" class="styled-form">
                    <div class="form-grid" style="display: block;">
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label>Vendor Name</label>
                            <input type="text" name="name" required placeholder="Enter vendor or company name" style="width: 100%;">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="5" placeholder="Enter a brief description of the vendor..." style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"></textarea>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 25px;">
                        <button type="submit" class="btn-primary">Save Vendor</button>
                        <a href="<?php echo URLROOT; ?>/dashboard" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>