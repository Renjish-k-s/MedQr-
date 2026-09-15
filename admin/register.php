<?php include './header.php'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Register New Company</h3>
    </div>

    <form id="companyForm" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Company Name *</label>
                <input type="text" name="company_name" class="form-control" required placeholder="e.g. BioStar Health">
            </div>

            <div class="form-group">
                <label class="form-label">Preferred Short Form *</label>
                <input type="text" name="short_form" class="form-control" required placeholder="e.g. BSH">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Website URL</label>
            <input type="url" name="website_url" class="form-control" placeholder="https://example.com">
        </div>

        <div class="form-group">
            <label class="form-label">Full Address *</label>
            <textarea name="full_address" class="form-control" rows="3" required placeholder="Enter complete address"></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="phone_number" class="form-control" pattern="[0-9+\-\s()]+" required placeholder="+1 234 567 890">
            </div>

            <div class="form-group">
                <label class="form-label">Company Email (for booking) *</label>
                <input type="email" name="email_address" class="form-control" required placeholder="contact@company.com">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Upload Company Logo (optional)</label>
            <input type="file" name="company_logo" class="form-control" id="logoInput" accept="image/jpeg, image/png">
            <div style="margin-top: 1rem;">
                <img id="previewImg" style="display: none; max-width: 150px; border-radius: 8px; border: 1px solid var(--border-color);" alt="Logo Preview">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-check-circle"></i> Submit Company Information
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById("logoInput").addEventListener("change", function(e){
    const file = e.target.files[0];
    const preview = document.getElementById("previewImg");
    if(file){
        const reader = new FileReader();
        reader.onload = function(evt){
            preview.src = evt.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = "none";
    }
});

document.getElementById("companyForm").addEventListener("submit", function(e){
    e.preventDefault();
    let formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    fetch("insert_company.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success") {
            // Use SweetAlert if available, otherwise alert
            if(typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: "#2563eb"
                });
            } else {
                alert("✅ " + data.message);
            }
            document.getElementById("companyForm").reset();
            document.getElementById("previewImg").style.display = "none";
        } else {
            if(typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: "#ef4444"
                });
            } else {
                alert("❌ " + data.message);
            }
        }
    })
    .catch(err => {
        alert("Error: " + err);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
</script>
<!-- SweetAlert2 for nicer alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include './footer.php'; ?>
