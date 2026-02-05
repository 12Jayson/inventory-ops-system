<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Edit User</h1>
                    <p>Modify details for <strong><?php echo $data['user']->username; ?></strong></p>
                </div>
                <a href="<?php echo URLROOT; ?>/users" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </header>

            <section class="card-form-container">
                <form action="<?php echo URLROOT; ?>/users/edit/<?php echo $data['user']->id; ?>" method="POST" class="styled-form">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">Full Name / Username</label>
                            <input type="text" id="username" name="username" 
                                   value="<?php echo $data['user']->username; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo $data['user']->email; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="role">System Role</label>
                            <select name="role" id="role" onchange="toggleStoreSelection()">
                                <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                <option value="manager" <?php echo ($data['user']->role == 'manager') ? 'selected' : ''; ?>>Store Manager</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">New Password <small>(Leave blank to keep current)</small></label>
                            <input type="password" id="password" name="password" placeholder="••••••••">
                        </div>
                    </div>

                    <div id="store-selection-area" class="store-assignment-section" 
                         style="<?php echo ($data['user']->role == 'admin') ? 'display:none;' : ''; ?>">
                        <h3>Assign Locations</h3>
                        <p class="field-info">Select the stores this manager will oversee:</p>
                        
                        <div class="checkbox-grid">
                            <?php foreach($data['stores'] as $store): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="stores[]" value="<?php echo $store->id; ?>"
                                        <?php echo (in_array($store->id, $data['current_stores'])) ? 'checked' : ''; ?>>
                                    <span class="checkbox-label"><?php echo $store->name; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Update User Account</button>
                    </div>
                </form>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>

<script>
    function toggleStoreSelection() {
        const role = document.getElementById('role').value;
        const area = document.getElementById('store-selection-area');
        area.style.display = (role === 'admin') ? 'none' : 'block';
    }
</script>