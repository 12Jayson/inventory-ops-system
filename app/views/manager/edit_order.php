<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Edit Order: <?php echo $data['order']->vendor_name; ?></h1>
                    <p class="text-muted">Review items in your order or add more from the catalog.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/orders/dashboard" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </header>

            <form action="<?php echo URLROOT; ?>/orders/submit" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $data['order']->id; ?>">
                <input type="hidden" name="vendor_id" value="<?php echo $data['order']->vendor_id; ?>">

                <section class="current-items shadow-sm" style="background: #fff; border-radius: 8px; margin-bottom: 20px;">
                    <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f8fafc;">
                        <h3 style="font-size: 1rem; margin: 0; color: #2d3748;"><i class="fas fa-shopping-cart"></i> Items in Order</h3>
                    </div>
                    <div class="table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Code</th>
                                    <th style="width: 120px;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $has_items = false;
                                foreach($data['products'] as $product): 
                                    $qty = $data['current_items'][$product->id] ?? 0;
                                    if($qty > 0): 
                                        $has_items = true;
                                ?>
                                    <tr>
                                        <td><strong><?php echo $product->item_name; ?></strong><br><small class="text-muted"><?php echo $product->category; ?></small></td>
                                        <td><code><?php echo $product->item_code; ?></code></td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo $product->id; ?>]" value="<?php echo $qty; ?>" min="0" step="0.01" class="form-control" style="border: 1px solid #cbd5e0;">
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>
                                
                                <?php if(!$has_items): ?>
                                    <tr><td colspan="3" class="text-center">No items added yet. Use the catalog below.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="vendor-catalog shadow-sm" style="background: #fff; border-radius: 8px;">
                    <details style="width: 100%;">
                        <summary style="padding: 15px; cursor: pointer; background: #edf2f7; font-weight: bold; color: #4a5568; list-style: none; display: flex; justify-content: space-between; align-items: center; border-radius: 8px;">
                            <span><i class="fas fa-plus-circle"></i> Add More Items (Vendor Catalog)</span>
                            <i class="fas fa-chevron-down"></i>
                        </summary>
                        <div style="padding: 10px; border-top: 1px solid #eee;">
                            <table class="styled-table">
                                <tbody id="catalog-body">
                                    <?php foreach($data['products'] as $product): 
                                        $qty = $data['current_items'][$product->id] ?? 0;
                                        if($qty <= 0): // Solo mostrar los que NO están en la orden
                                    ?>
                                        <tr>
                                            <td><?php echo $product->item_name; ?> (<?php echo $product->item_code; ?>)</td>
                                            <td style="width: 120px;">
                                                <input type="number" name="quantity[<?php echo $product->id; ?>]" value="" min="0" step="0.01" class="form-control" placeholder="0.00">
                                            </td>
                                        </tr>
                                    <?php endif; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </details>
                </section>

                <div class="form-actions" style="margin-top: 30px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-primary" style="background: #805ad5; padding: 12px 40px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 6px rgba(128, 90, 213, 0.2);">
                        <i class="fas fa-save"></i> Update Order
                    </button>
                </div>
            </form>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>

<style>
/* Estilo simple para el efecto de acordeón */
details summary::-webkit-details-marker { display: none; }
details[open] summary i.fa-chevron-down { transform: rotate(180deg); }
.form-control:focus { border-color: #805ad5; outline: none; box-shadow: 0 0 0 3px rgba(128, 90, 213, 0.1); }
</style>