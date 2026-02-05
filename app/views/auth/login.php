<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="login-screen">
    <div class="login-card">
        <h2>Shahs Inventory</h2>
        
        <?php if(isset($_GET['error'])): ?>
            <div style="color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;">
                <?php 
                    if ($_GET['error'] == 'user_not_found') {
                        echo "❌ El correo electrónico no está registrado.";
                    } elseif ($_GET['error'] == 'wrong_password') {
                        echo "🔑 La contraseña es incorrecta.";
                    } else {
                        echo "⚠️ Error al intentar iniciar sesión.";
                    }
                ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/auth/authenticate" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required 
                       placeholder="admin@shahs.com"
                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required 
                       placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-primary" style="margin-top: 1.5rem; width: 100%; cursor: pointer;">
                Entrar al Sistema
            </button>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>