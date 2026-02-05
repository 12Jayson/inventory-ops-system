<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Create New User</h1>
                    <p class="welcome-msg">Register a new administrator or store manager</p>
                </div>
            </header>

            <section class="card-form-container">
                <form action="<?php echo URLROOT; ?>/users/create" method="POST" class="styled-form" id="userForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" required placeholder="Enter full name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required placeholder="user@shahs.com">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" required placeholder="••••••••">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" id="roleSelect" required>
                                <option value="" disabled selected>Select a role...</option>
                                <option value="admin">Admin</option>
                                <option value="user">User (Manager)</option>
                            </select>
                        </div>
                    </div>

                    <div id="managementTypeSection" class="form-group" style="margin-top: 20px; display: none;">
                        <label>Will this manager handle a single location or multiple?</label>
                        <select id="mgmtType">
                            <option value="" disabled selected>Select an option...</option>
                            <option value="single">Single Location</option>
                            <option value="multiple">Multiple Locations</option>
                        </select>
                    </div>

                    <div id="storesSection" class="stores-selection" style="display: none;">
                        <h3 id="storesTitle">Assign Stores</h3>
                        <p class="help-text" id="storesHelp">Select the locations to assign:</p>
                        <div class="stores-grid">
                            <?php foreach($data['stores'] as $store): ?>
                                <label class="store-checkbox">
                                    <input type="checkbox" name="stores[]" value="<?php echo $store->id; ?>" class="store-check">
                                    <span><?php echo $store->name; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
                        <a href="<?php echo URLROOT; ?>/users" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save User</button>
                    </div>
                </form>
            </section>
        </main>

        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>

<script>
// Tu lógica de JS original que ya funciona perfectamente
const roleSelect = document.getElementById('roleSelect');
const mgmtType = document.getElementById('mgmtType');
const mgmtSection = document.getElementById('managementTypeSection');
const storesSection = document.getElementById('storesSection');
const checkboxes = document.querySelectorAll('.store-check');

roleSelect.addEventListener('change', function() {
    if (this.value === 'user') {
        mgmtSection.style.display = 'flex'; // Cambiado a flex para mantener consistencia con .form-group
    } else {
        mgmtSection.style.display = 'none';
        storesSection.style.display = 'none';
        mgmtType.value = ""; 
        checkboxes.forEach(cb => cb.checked = false);
    }
});

mgmtType.addEventListener('change', function() {
    storesSection.style.display = 'block';
    const isSingle = this.value === 'single';
    
    document.getElementById('storesTitle').innerText = isSingle ? 'Select Location' : 'Select Locations';
    document.getElementById('storesHelp').innerText = isSingle ? 'You can only select one store:' : 'You can select multiple stores:';

    checkboxes.forEach(cb => {
        cb.checked = false;
        cb.onclick = isSingle ? function() {
            checkboxes.forEach(other => { if(other !== cb) other.checked = false; });
        } : null;
    });
});

document.getElementById('userForm').onsubmit = function() {
    if (roleSelect.value === 'user') {
        if (mgmtType.value === "") {
            alert("Please select management type."); return false;
        }
        if (document.querySelectorAll('.store-check:checked').length === 0) {
            alert("Please select at least one store."); return false;
        }
    }
    return true;
};
</script>