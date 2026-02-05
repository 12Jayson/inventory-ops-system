<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="store-selection-container">
    <div class="selection-card shadow">
        <header class="selection-header">
            <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
            <p>Please select the store you want to manage today</p>
        </header>

        <div class="store-grid">
            <?php foreach($data['stores'] as $store): ?>
                <a href="<?php echo URLROOT; ?>/users/activate_store/<?php echo $store->id; ?>" class="store-option-card">
                    <div class="store-icon">
                        <i class="fas fa-store-alt"></i>
                    </div>
                    <div class="store-info">
                        <h3><?php echo $store->name; ?></h3>
                        <p><?php echo $store->location ?? 'Main Branch'; ?></p>
                    </div>
                    <div class="selection-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="selection-footer">
            <a href="<?php echo URLROOT; ?>/logout" class="text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<style>
/* Estilo rápido para la selección */
.store-selection-container {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f7f6;
}
.selection-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    text-align: center;
}
.store-grid {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.store-option-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px solid #eee;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
}
.store-option-card:hover {
    border-color: #2c3e50;
    background: #f8fafb;
    transform: translateY(-2px);
}
.store-icon {
    font-size: 1.5rem;
    margin-right: 1rem;
    color: #2c3e50;
}
.store-info { flex-grow: 1; text-align: left; }
.store-info h3 { margin: 0; font-size: 1.1rem; }
.store-info p { margin: 0; font-size: 0.85rem; color: #777; }
</style>

<?php require APPROOT . '/views/layouts/footer.php'; ?>