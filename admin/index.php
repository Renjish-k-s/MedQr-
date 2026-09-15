<?php 
require '../include/config.php';
include './header.php'; 

// Fetch Stats
$total_companies = 0;
$total_patients = 0; // Placeholder if we want to count patients later

// Count Companies
$res = mysqli_query($con, "SELECT COUNT(*) as count FROM company_info");
if($row = mysqli_fetch_assoc($res)){
    $total_companies = $row['count'];
}

// Count Patients (optional, if we want to show it)
$res_p = mysqli_query($con, "SELECT COUNT(*) as count FROM patient_qrcodes");
if($row_p = mysqli_fetch_assoc($res_p)){
    $total_patients = $row_p['count'];
}

// Recent Companies
$recent_companies = mysqli_query($con, "SELECT * FROM company_info ORDER BY created_at DESC LIMIT 5");
?>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue">
          <i class="fas fa-building"></i>
        </div>
        <div class="stat-info">
          <h3><?php echo $total_companies; ?></h3>
          <p>Registered Companies</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon green">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
          <h3><?php echo $total_patients; ?></h3>
          <p>Total Patients Generated</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon orange">
          <i class="fas fa-qrcode"></i>
        </div>
        <div class="stat-info">
          <h3>Active</h3>
          <p>System Status</p>
        </div>
      </div>
    </div>

    <!-- Recent Activity / Companies -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Recently Added Companies</h3>
        <a href="user_control.php" class="btn btn-primary btn-sm">View All</a>
      </div>
      
      <div class="table-container">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Company</th>
              <th>Short Form</th>
              <th>Email</th>
              <th>Joined Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($recent_companies) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($recent_companies)): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php 
                                    $logo = !empty($row['company_logo']) ? '../'.$row['company_logo'] : 'https://ui-avatars.com/api/?name='.urlencode($row['company_name']).'&background=random';
                                ?>
                                <img src="<?php echo $logo; ?>" alt="" class="company-logo-sm">
                                <span style="font-weight: 500;"><?php echo htmlspecialchars($row['company_name']); ?></span>
                            </div>
                        </td>
                        <td><span style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;"><?php echo htmlspecialchars($row['short_form']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['email_address']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No companies found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

<?php include './footer.php'; ?>