<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Place New Order</h1>
                    <p class="welcome-msg">Vendor: <strong><?php echo $data['vendor']->name; ?></strong></p>
                </div>
            </header>

            <div class="order-selection-container">
                
                <section class="search-panel shadow-sm">
                    <h3><i class="fas fa-search"></i> Add Products</h3>
                    
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" id="productSearch" placeholder="Search product...">
                    </div>

                    <ul class="product-search-list" id="searchResults">
                        <?php foreach($data['products'] as $product): ?>
                            <li class="product-card-item" 
                                onclick="addProduct('<?php echo $product->id; ?>', '<?php echo addslashes($product->item_name); ?>', '<?php echo $product->item_code; ?>')"
                                data-name="<?php echo strtolower($product->item_name); ?>">
                                <div class="info">
                                    <h4><?php echo $product->item_name; ?></h4>
                                    <span>Code: <?php echo $product->item_code ?? 'N/A'; ?></span>
                                </div>
                                <i class="fas fa-plus-circle add-icon"></i>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <section class="card order-card shadow-sm">
                    <form action="<?php echo URLROOT; ?>/orders/submit" method="POST">
                        <input type="hidden" name="vendor_id" value="<?php echo $data['vendor']->id; ?>">
                        
                        <div class="order-card-header">
                            <h3><i class="fas fa-list-check"></i> Selected Items</h3>
                            <span id="itemCount" class="badge info">0 Items</span>
                        </div>

                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Product Details</th>
                                    <th width="150px">Quantity</th>
                                    <th width="50px"></th>
                                </tr>
                            </thead>
                            <tbody id="orderTableBody">
                                <tr id="noItemsRow">
                                    <td colspan="3" class="no-items-placeholder">
                                        <i class="fas fa-shopping-basket"></i>
                                        Search and select products to start.
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="form-actions">
                            <a href="<?php echo URLROOT; ?>/orders/dashboard" class="btn-secondary">Cancel</a>
                            <button type="submit" class="btn-primary" id="btnSubmit" disabled>
                                <i class="fas fa-paper-plane"></i> Submit Order
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
</div>

<script>
let selectedProducts = new Set();

function addProduct(id, name, code) {
    if(selectedProducts.has(id)) return;

    const tbody = document.getElementById('orderTableBody');
    const noItemsRow = document.getElementById('noItemsRow');
    if(noItemsRow) noItemsRow.remove();

    selectedProducts.add(id);
    updateUI();

    const tr = document.createElement('tr');
    tr.id = `row-${id}`;
    tr.innerHTML = `
        <td>
            <div class="selected-product-info">
                <span class="product-name">${name}</span>
                <span class="product-code">Code: ${code}</span>
            </div>
        </td>
        <td>
            <input type="number" name="quantity[${id}]" class="form-control" step="0.01" min="0.01" required placeholder="0.00">
        </td>
        <td class="text-center">
            <button type="button" onclick="removeProduct('${id}')" class="btn-remove">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function removeProduct(id) {
    document.getElementById(`row-${id}`).remove();
    selectedProducts.delete(id);
    updateUI();
    
    if(selectedProducts.size === 0) {
        location.reload(); 
    }
}

function updateUI() {
    document.getElementById('itemCount').innerText = `${selectedProducts.size} Items`;
    document.getElementById('btnSubmit').disabled = selectedProducts.size === 0;
}

document.getElementById('productSearch').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('.product-card-item').forEach(item => {
        item.style.display = item.getAttribute('data-name').includes(value) ? 'flex' : 'none';
    });
});
</script>