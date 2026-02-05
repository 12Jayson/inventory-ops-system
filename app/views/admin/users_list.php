<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>All Users</h1>
                    <p>Manage system access and store assignments.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/users/create" class="btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </header>

            <section class="search-container" style="margin-bottom: 25px;">
                <form action="<?php echo URLROOT; ?>/users" method="GET" style="display: flex; gap: 10px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="flex: 1; position: relative;">
                        <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #aaa;"></i>
                        <input type="text" name="search" 
                               placeholder="Search user by username or email..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                               style="width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                    </div>
                    <button type="submit" class="btn-primary" style="padding: 0 25px;">
                        Search
                    </button>
                    <?php if(!empty($_GET['search'])): ?>
                        <a href="<?php echo URLROOT; ?>/users" class="btn-secondary" style="display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #333; border: 1px solid #ddd; padding: 0 15px; border-radius: 4px; text-decoration: none;">
                            Clear
                        </a>
                    <?php endif; ?>
                </form>
            </section>

            <section class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Assigned Stores</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['users'])): ?>
                            <?php foreach($data['users'] as $user): ?>
                                <tr>
                                    <td><strong><?php echo $user->username; ?></strong></td>
                                    <td><?php echo $user->email; ?></td>
                                    <td>
                                        <span class="badge <?php echo strtolower($user->role); ?>">
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="stores-list">
                                            <?php echo $user->assigned_stores ? $user->assigned_stores : '<span class="text-muted">No stores assigned</span>'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="<?php echo URLROOT; ?>/users/edit/<?php echo $user->id; ?>" class="edit-link" title="Edit">
                                                <i class="fas fa-edit" style="color: #3498db;"></i>
                                            </a>
                                            
                                            <a href="<?php echo URLROOT; ?>/users/delete/<?php echo $user->id; ?>" 
                                            class="delete-link" 
                                            title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash" style="color: #e74c3c;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px; color: #777;">
                                    No users match your criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <?php if($data['totalPages'] > 1): ?>
                <div class="pagination-container" style="margin-top: 25px; display: flex; justify-content: center; gap: 8px;">
                    
                    <?php if($data['currentPage'] > 1): ?>
                        <a href="?page=<?php echo $data['currentPage'] - 1; ?><?php echo !empty($data['searchTerm']) ? '&search='.urlencode($data['searchTerm']) : ''; ?>" 
                           style="padding: 8px 16px; border-radius: 6px; border: 1px solid #ddd; text-decoration: none; color: #333; background: #fff;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $data['totalPages']; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($data['searchTerm']) ? '&search='.urlencode($data['searchTerm']) : ''; ?>" 
                           style="padding: 8px 16px; border-radius: 6px; border: 1px solid #ddd; text-decoration: none; 
                           <?php echo ($i == $data['currentPage']) ? 'background: #3498db; color: #fff; border-color: #3498db;' : 'background: #fff; color: #333;'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if($data['currentPage'] < $data['totalPages']): ?>
                        <a href="?page=<?php echo $data['currentPage'] + 1; ?><?php echo !empty($data['searchTerm']) ? '&search='.urlencode($data['searchTerm']) : ''; ?>" 
                           style="padding: 8px 16px; border-radius: 6px; border: 1px solid #ddd; text-decoration: none; color: #333; background: #fff;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>