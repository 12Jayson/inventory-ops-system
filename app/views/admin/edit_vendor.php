<?php require APPROOT . '/views/layouts/header.php'; ?>
<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <h1>Edit Vendor</h1>
                <p>Modifying details for: <strong><?php echo $data['name']; ?></strong></p>
            </header>

            <section class="card-form-container">
                <form action="<?php echo URLROOT; ?>/vendors/edit/<?php echo $data['id']; ?>" method="POST" class="styled-form">
                    <div class="form-grid" style="display: block;">
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="name">Vendor Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>" required style="width: 100%;">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="5" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"><?php echo $data['description']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 25px;">
                        <button type="submit" class="btn-primary">Update Vendor</button>
                        <a href="<?php echo URLROOT; ?>/vendors" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>