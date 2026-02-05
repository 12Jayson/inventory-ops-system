<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div class="header-info">
                    <h1 style="font-size: 1.8rem; color: #2d3748; margin: 0;">Order Details #<?php echo $data['order']->id; ?></h1>
                    <p class="text-muted" style="margin: 5px 0 0 0;">
                        Vendor: <strong style="color: #2d3748;"><?php echo $data['order']->vendor_name; ?></strong> | 
                        Date: <?php echo date('M d, Y - h:i A', strtotime($data['order']->created_at)); ?>
                    </p>
                </div>
                <a href="<?php echo URLROOT; ?>/orders/history" class="btn-back" style="padding: 10px 20px; background: #edf2f7; color: #4a5568; border-radius: 8px; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chevron-left"></i> Back to History
                </a>
            </header>

            <section class="details-card shadow-sm" style="background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 30px;">
                <table class="styled-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; text-align: left;">
                            <th style="padding: 15px; border-bottom: 2px solid #edf2f7; color: #4a5568;">Product Name</th>
                            <th style="padding: 15px; border-bottom: 2px solid #edf2f7; color: #4a5568;">Category</th>
                            <th style="padding: 15px; border-bottom: 2px solid #edf2f7; color: #4a5568;">Unit/Code</th>
                            <th style="padding: 15px; border-bottom: 2px solid #edf2f7; text-align: center; color: #4a5568;">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['items'] as $item): ?>
                            <tr>
                                <td style="padding: 15px; border-bottom: 1px solid #edf2f7; color: #2d3748;"><strong><?php echo $item['name']; ?></strong></td>
                                <td style="padding: 15px; border-bottom: 1px solid #edf2f7;">
                                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; color: #64748b;">
                                        <?php echo $item['category']; ?>
                                    </span>
                                </td>
                                <td style="padding: 15px; border-bottom: 1px solid #edf2f7;"><code style="color: #e53e3e;"><?php echo $item['unit']; ?></code></td>
                                <td style="padding: 15px; border-bottom: 1px solid #edf2f7; text-align: center; font-weight: bold; color: #2b6cb0; font-size: 1.1rem;">
                                    <?php echo number_format($item['quantity'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <div class="action-footer" style="display: flex; justify-content: flex-end; padding-top: 10px;">
                <button onclick="window.print();" class="btn-print-action">
                    <i class="fas fa-print"></i> Print Purchase Order
                </button>
            </div>
        </main>
    </div>
</div>

<style>
/* Estilo del botón para que resalte sobre el fondo gris */
.btn-print-action {
    background-color: #2d3748; /* Azul muy oscuro casi negro */
    color: white !important;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    transition: background 0.2s ease, transform 0.1s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-print-action:hover {
    background-color: #1a202c;
    transform: translateY(-1px);
}

.btn-back:hover {
    background-color: #e2e8f0 !important;
}

/* Reglas de Impresión: Limpia el documento para el papel */
@media print {
    .sidebar, .btn-back, .btn-print-action, .dashboard-wrapper .sidebar {
        display: none !important;
    }
    
    .main-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    
    .dashboard-content {
        padding: 0 !important;
    }

    .details-card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }

    body {
        background: white !important;
    }
}
</style>