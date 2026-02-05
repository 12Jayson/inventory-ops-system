<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>

    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <div class="header-title">
                    <h1>Store: <?php echo $_SESSION['active_store_name'] ?? 'Select Store'; ?></h1>
                    <p class="welcome-msg">Welcome, <strong><?php echo $_SESSION['username']; ?></strong></p>
                </div>
            </header>

            <div class="date-display" style="margin-bottom: 25px;">
                <span style="background: #fff; padding: 10px 15px; border-radius: 8px; border-left: 4px solid #3182ce; box-shadow: var(--shadow-sm);">
                    <i class="far fa-calendar-alt"></i> Today is <strong><?php echo date('l, F jS'); ?></strong>
                </span>
            </div>

            <section class="weekly-schedule">
                <h3 class="section-subtitle"><i class="fas fa-tasks"></i> Order Management</h3>
                <p class="text-muted">Orders unlock 2 days before the scheduled date.</p>
                
                <div class="table-container shadow-sm" style="background: #fff; border-radius: 8px; overflow: hidden;">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Target Day(s)</th>
                                <th>Status / Countdown</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['full_schedule'] as $sched): ?>
                                <tr <?php echo $sched->is_due_today ? 'style="background-color: #f0fff4;"' : ''; ?>>
                                    <td><strong><?php echo $sched->vendor_name; ?></strong></td>
                                    <td>
                                        <span class="day-badge" style="color: #2b6cb0; font-weight: 500;">
                                            <?php echo !empty($sched->purchase_day) ? str_replace(',', ', ', $sched->purchase_day) : 'Not Scheduled'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($sched->is_due_today): ?>
                                            <span class="badge" style="background: #c6f6d5; color: #22543d; padding: 4px 8px; border-radius: 4px; font-weight: bold;">DUE TODAY</span>
                                        <?php elseif($sched->days_until !== null): ?>
                                            <span class="badge" style="background: <?php echo $sched->is_unlocked ? '#bee3f8' : '#edf2f7'; ?>; color: #2c5282; padding: 4px 8px; border-radius: 4px;">
                                                In <?php echo $sched->days_until; ?> days 
                                                <?php if($sched->is_unlocked) echo '<i class="fas fa-unlock" style="font-size: 0.7rem; margin-left: 5px;"></i>'; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-style: italic;">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($sched->existing_order): ?>
                                            <a href="<?php echo URLROOT; ?>/orders/edit/<?php echo $sched->existing_order->id; ?>" class="btn-primary" style="background: #805ad5; padding: 8px 15px; border-radius: 6px; text-decoration: none; color: white; display: inline-block;">
                                                <i class="fas fa-edit"></i> Edit Order
                                            </a>
                                        <?php elseif($sched->is_unlocked): ?>
                                            <a href="<?php echo URLROOT; ?>/orders/new/<?php echo $sched->vendor_id; ?>" class="btn-primary" style="background: #3182ce; padding: 8px 15px; border-radius: 6px; text-decoration: none; color: white; display: inline-block;">
                                                <i class="fas fa-shopping-basket"></i> Start Order
                                            </a>
                                        <?php else: ?>
                                            <button class="btn-secondary" disabled style="opacity: 0.5; cursor: not-allowed; padding: 8px 15px; border-radius: 6px; border: 1px solid #cbd5e0;">
                                                <i class="fas fa-lock"></i> Locked
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <?php require APPROOT . '/views/layouts/footer.php'; ?>
    </div>
</div>