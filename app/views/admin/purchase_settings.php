<?php require APPROOT . '/views/layouts/header.php'; ?>
<div class="dashboard-wrapper">
    <?php require APPROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-wrapper">
        <main class="dashboard-content">
            <header class="content-header">
                <h1>Global Purchase Scheduling</h1>
                <p>Set order cycles that apply to all stores.</p>
            </header>

            <section class="card-form-container">
                <form id="scheduleForm" action="<?php echo URLROOT; ?>/purchase/settings" method="POST" class="styled-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Vendor</label>
                            <select name="vendor_id" id="vendor_id" required>
                                <option value="" disabled selected>Select vendor...</option>
                                <?php foreach($data['vendors'] as $vendor): ?>
                                    <option value="<?php echo $vendor->id; ?>"><?php echo $vendor->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Frequency</label>
                            <select name="frequency" id="freqSelector" onchange="updateHybridSelector()" required>
                                <option value="per_week">Weekly</option>
                                <option value="bi_weekly">Bi-Weekly (Every 2nd Week)</option>
                                <option value="per_month">Monthly</option>
                            </select>
                        </div>

                        <div id="hybridContainer" style="grid-column: span 2;"></div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label>Notes / Remarks</label>
                            <textarea name="notes" id="notes" placeholder="Additional instructions..."></textarea>
                        </div>
                    </div>
                    <button type="submit" id="submitBtn" class="btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-save"></i> Save Global Schedule
                    </button>
                    <button type="button" id="cancelBtn" onclick="resetForm()" class="btn-secondary" style="margin-top: 20px; display:none; background: #a0aec0;">
                        Cancel
                    </button>
                </form>
            </section>

            <section class="table-container" style="margin-top: 30px;">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Frequency</th>
                            <th>Schedule Detail</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['settings'])): ?>
                            <tr><td colspan="5" style="text-align:center;">No global schedules defined yet.</td></tr>
                        <?php else: ?>
                            <?php foreach($data['settings'] as $s): ?>
                                <tr>
                                    <td><strong><?php echo $s->vendor_name; ?></strong></td>
                                    <td><span class="badge info"><?php echo str_replace('_', ' ', $s->frequency); ?></span></td>
                                    <td><?php echo "<strong>" . str_replace(',', ', ', $s->purchase_day) . "</strong>"; ?></td>
                                    <td><span class="badge success">Active (Global)</span></td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn-edit" 
                                                onclick='editSchedule(<?php echo json_encode($s); ?>)'
                                                style="background: #ebf8ff; border: 1px solid #4299e1; color: #2b6cb0; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</div>

<script>
function updateHybridSelector(callback) {
    const freq = document.getElementById('freqSelector').value;
    const container = document.getElementById('hybridContainer');
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    let html = '';

    if (freq === 'per_week' || freq === 'bi_weekly') {
        html = `<div class="form-group">
            <label>Select Days</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; margin-top: 10px;">
                ${days.map(day => `
                    <label style="display: flex; align-items: center; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; cursor: pointer;">
                        <input type="checkbox" name="purchase_days[]" value="${day}" class="day-checkbox"> ${day}
                    </label>
                `).join('')}
            </div>
        </div>`;
    } else if (freq === 'per_month') {
        html = `<div class="form-group" style="max-width: 200px;">
            <label>Day of Month (1-31)</label>
            <input type="number" name="purchase_day" id="dayOfMonth" min="1" max="31" class="form-control" required>
        </div>`;
    }
    container.innerHTML = html;

    // Ejecutar callback si existe (útil para cuando editamos)
    if(callback) callback();
}

function editSchedule(data) {
    // 1. Asignar Vendor y Frecuencia
    document.getElementById('vendor_id').value = data.vendor_id;
    document.getElementById('freqSelector').value = data.frequency;
    document.getElementById('notes').value = data.notes || '';

    // 2. Actualizar el selector híbrido y después marcar los días
    updateHybridSelector(() => {
        if (data.frequency === 'per_month') {
            document.getElementById('dayOfMonth').value = data.purchase_day;
        } else {
            const selectedDays = data.purchase_day.split(',');
            selectedDays.forEach(day => {
                const trimmedDay = day.trim();
                const cb = document.querySelector(`.day-checkbox[value="${trimmedDay}"]`);
                if (cb) cb.checked = true;
            });
        }
    });

    // 3. Estética: Cambiar botón y hacer scroll
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-sync"></i> Update Global Schedule';
    document.getElementById('submitBtn').style.background = '#2c5282';
    document.getElementById('cancelBtn').style.display = 'inline-block';
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('scheduleForm').reset();
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Save Global Schedule';
    document.getElementById('submitBtn').style.background = '';
    document.getElementById('cancelBtn').style.display = 'none';
    updateHybridSelector();
}

document.addEventListener('DOMContentLoaded', () => updateHybridSelector());
</script>