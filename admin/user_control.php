<?php
require '../include/config.php';
include './header.php';
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Manage Companies</h3>
        <a href="register.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Company
        </a>
    </div>

    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Company Name</th>
                    <th>Short Form</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM company_info ORDER BY created_at DESC";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $company_id = $row['user_id'];
                    $company_name = htmlspecialchars($row['company_name']);
                    $short_form = htmlspecialchars($row['short_form']);
                    $phone_number = htmlspecialchars($row['phone_number']);
                    $email_address = htmlspecialchars($row['email_address']);
                    $logo_path = !empty($row['company_logo']) ? '../'.$row['company_logo'] : "https://ui-avatars.com/api/?name=".urlencode($company_name)."&background=random";

                    echo "
                    <tr>
                        <td><img src='{$logo_path}' alt='Logo' class='company-logo-sm'></td>
                        <td><span style='font-weight: 500;'>{$company_name}</span></td>
                        <td><span style='background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;'>{$short_form}</span></td>
                        <td>{$phone_number}</td>
                        <td>{$email_address}</td>
                        
                        <td>
                            <div style='display: flex; gap: 0.5rem;'>
                                <a href='edit_company.php?user_id={$company_id}' class='btn btn-primary btn-sm' style='background-color: var(--secondary-color);'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>
                                <button class='btn btn-sm btn-outline-danger' onclick='deleteCompany({$company_id})'>
                                    <i class='fas fa-trash'></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center; padding: 2rem; color: var(--text-muted);'>No companies found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteCompany(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete the company and its associated user.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("delete_company.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + id
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: data.status,
                    title: data.status === 'success' ? 'Deleted!' : 'Error!',
                    text: data.message,
                    confirmButtonColor: "#2563eb"
                }).then(() => {
                    if (data.status === 'success') {
                        location.reload();
                    }
                });
            });
        }
    });
}
</script>

<?php include './footer.php'; ?>
