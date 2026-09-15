<?php
require '../include/config.php';
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1){
    header("Location: ../login");
    exit;
}

// Ensure session_code is set (it should be if they are logged in and have generated a QR, but just in case)
if(!isset($_SESSION['user_id'])){
    // If no session code, they haven't generated any patients yet.
    // We can either generate one or just show empty list.
    // Let's assume empty list for now, but we need a variable for the query.
    $session_code = ''; 
} else {
    $session_code = $_SESSION['user_id'];
}

// --- CSV Export Logic ---
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    $filename = "my_patients_" . date('Y-m-d') . ".csv";
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Patient Name', 'Created At'));

    // Build Query for Export (No Pagination)
    $query = "SELECT patient_name, created_at FROM patient_qrcodes WHERE user_id = ?";
    $params = array($session_code);
    $types = "s";

    // Apply Filters (Same as display logic)
    if (!empty($_GET['search'])) {
        $search = "%" . $_GET['search'] . "%";
        $query .= " AND patient_name LIKE ?";
        $params[] = $search;
        $types .= "s";
    }

    if (!empty($_GET['from_date'])) {
        $query .= " AND DATE(created_at) >= ?";
        $params[] = $_GET['from_date'];
        $types .= "s";
    }

    if (!empty($_GET['to_date'])) {
        $query .= " AND DATE(created_at) <= ?";
        $params[] = $_GET['to_date'];
        $types .= "s";
    }

    // Sort
    $sort_order = isset($_GET['sort']) && strtolower($_GET['sort']) == 'asc' ? 'ASC' : 'DESC';
    $query .= " ORDER BY created_at " . $sort_order;

    $stmt = $con->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

// --- Display Logic ---

// Pagination Setup
$limit = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build Query
$query = "SELECT patient_name, created_at FROM patient_qrcodes WHERE user_id = ?";
$params = array($session_code);
$types = "s";

// Search Filter
$search_term = "";
if (!empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $search_param = "%" . $search_term . "%";
    $query .= " AND patient_name LIKE ?";
    $params[] = $search_param;
    $types .= "s";
}

// Date Filters
$from_date = "";
if (!empty($_GET['from_date'])) {
    $from_date = $_GET['from_date'];
    $query .= " AND DATE(created_at) >= ?";
    $params[] = $from_date;
    $types .= "s";
}

$to_date = "";
if (!empty($_GET['to_date'])) {
    $to_date = $_GET['to_date'];
    $query .= " AND DATE(created_at) <= ?";
    $params[] = $to_date;
    $types .= "s";
}

// Sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$sort_order = strtolower($sort) == 'asc' ? 'ASC' : 'DESC';
$query .= " ORDER BY created_at " . $sort_order;

// Count Total Records (for pagination)
// We need a separate query for count without LIMIT/OFFSET
$count_query = str_replace("SELECT patient_name, created_at", "SELECT COUNT(*) as total", $query);
$stmt_count = $con->prepare($count_query);
$stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$total_records = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Add Limit/Offset to Main Query
$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $con->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Patients | MedQR+</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="user-style.css">
<style>
    .filters-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #666;
    }
    
    .filter-group input, .filter-group select {
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.9rem;
    }

    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .btn-filter {
        background: #2196F3;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .btn-filter:hover {
        background: #1976D2;
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #2196F3;
        color: #2196F3;
    }

    .btn-outline:hover {
        background: #f0f9ff;
    }

    .patients-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }

    .patients-table th, .patients-table td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
    }

    .patients-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
    }

    .patients-table tr:last-child td {
        border-bottom: none;
        color: #475569;
    }

    .patients-table tr:hover {
        background: #f8fafc;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link.active {
        background: #2196F3;
        color: white;
        border-color: #2196F3;
    }

    .page-link:hover:not(.active) {
        background: #f1f5f9;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-group {
            flex-direction: column;
        }
    }
</style>
</head>
<body>

<div class="dashboard-shell">
  <header class="console-hero">
    <div>
      <p class="hero-kicker">MedQR+ Console</p>
      <h1>My Patients</h1>
      <p class="hero-copy">View and manage your patient history.</p><span style="font-size: 8px;color: #980707ff;">We only keep last 3 months of data.Your data is secured under our privacy policy</span>
    </div>
    <div class="hero-links">
      <a href="./index.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
      <!-- <a href="./logout.php" class="danger"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a> -->
    </div>
  </header>

  <main class="dashboard-grid" style="grid-template-columns: 1fr;"> <!-- Full width for table -->
    
    <form class="filters-bar" method="GET">
        <div class="filter-group">
            <label>Search Name</label>
            <input type="text" name="search" placeholder="Patient Name..." value="<?php echo htmlspecialchars($search_term); ?>">
        </div>
        
        <div class="filter-group">
            <label>From Date</label>
            <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
        </div>

        <div class="filter-group">
            <label>To Date</label>
            <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">
        </div>

        <div class="filter-group">
            <label>Sort By Date</label>
            <select name="sort">
                <option value="desc" <?php echo $sort == 'desc' ? 'selected' : ''; ?>>Newest First</option>
                <option value="asc" <?php echo $sort == 'asc' ? 'selected' : ''; ?>>Oldest First</option>
            </select>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <!-- Export Button (Builds URL with current filters + export=csv) -->
            <?php
                $export_params = $_GET;
                $export_params['export'] = 'csv';
                unset($export_params['page']); // Export all pages
                $export_url = '?' . http_build_query($export_params);
            ?>
            <a href="<?php echo $export_url; ?>" class="btn-filter btn-outline">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>
    </form>

    <div class="card">
        <?php if ($result->num_rows > 0): ?>
            <div style="overflow-x: auto;">
                <table class="patients-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php
                            $page_params = $_GET;
                            $page_params['page'] = $i;
                            $page_url = '?' . http_build_query($page_params);
                        ?>
                        <a href="<?php echo $page_url; ?>" class="page-link <?php echo $page == $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                <h3>No patients found</h3>
                <p>Try adjusting your filters or generate new QR codes.</p>
            </div>
        <?php endif; ?>
    </div>

  </main>
</div>

</body>
</html>
