<?php include './header.php'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Edit User & Company Information</h3>
    </div>

    <div id="loader" style="text-align: center; padding: 3rem; display: none;">
        <i class="fas fa-spinner fa-spin fa-3x" style="color: var(--primary-color);"></i>
        <p style="margin-top: 1rem; color: var(--text-muted);">Loading data...</p>
    </div>

    <form id="editForm" style="display: none;">
        <input type="hidden" id="user_id" name="user_id">
        <input type="hidden" id="company_id" name="company_id">

        <!-- User Information Section -->
        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 1rem; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">User Information</h4>
            <div class="form-group">
                <label class="form-label" for="email">Email Address <span style="color: var(--danger-color);">*</span></label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="user@example.com">
            </div>
        </div>

        <!-- Company Information Section -->
        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 1rem; color: var(--primary-color); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">Company Information</h4>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter company name">
                </div>
                <div class="form-group">
                    <label class="form-label" for="short_form">Short Form</label>
                    <input type="text" id="short_form" name="short_form" class="form-control" placeholder="Abbreviation">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="website_url">Website URL</label>
                <input type="url" id="website_url" name="website_url" class="form-control" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="full_address">Full Address</label>
                <textarea id="full_address" name="full_address" class="form-control" rows="3" placeholder="Enter complete address"></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="1234567890">
                </div>
                <div class="form-group" style="display: none;"> 
                    <label class="form-label" for="company_logo">Company Logo Path</label>
                    <input type="text" id="company_logo" name="company_logo" class="form-control" placeholder="uploads/logo.jpg">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" id="submitBtn" style="flex: 1;">
                <i class="fas fa-save"></i> Update Information
            </button>
            <a href="user_control.php" class="btn" style="background: #f1f5f9; color: var(--text-muted);">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Get user_id from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user_id');

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (!userId) {
            Swal.fire('Error', 'User ID is required in URL parameter', 'error');
            return;
        }
        loadUserData(userId);
    });

    // Load user and company data
    async function loadUserData(userId) {
        const loader = document.getElementById('loader');
        const form = document.getElementById('editForm');
        
        loader.style.display = 'block';
        form.style.display = 'none';
        
        try {
            const response = await fetch(`fetch_user_data.php?user_id=${userId}`);
            const text = await response.text();
            
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error('Response is not JSON:', text);
                Swal.fire('Error', 'Server error: Invalid response format.', 'error');
                loader.style.display = 'none';
                return;
            }
            
            if (result.success) {
                populateForm(result.data);
                form.style.display = 'block';
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to load data: ' + error.message, 'error');
        } finally {
            loader.style.display = 'none';
        }
    }

    // Populate form with data
    function populateForm(data) {
        document.getElementById('user_id').value = data.user_id;
        document.getElementById('company_id').value = data.company_id || '';
        document.getElementById('email').value = data.email;
        document.getElementById('company_name').value = data.company_name;
        document.getElementById('short_form').value = data.short_form;
        document.getElementById('website_url').value = data.website_url;
        document.getElementById('full_address').value = data.full_address;
        document.getElementById('phone_number').value = data.phone_number;
        document.getElementById('company_logo').value = data.company_logo;
    }

    // Handle form submission
    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

        // Collect form data
        const formData = {
            user_id: document.getElementById('user_id').value,
            email: document.getElementById('email').value,
            company_name: document.getElementById('company_name').value,
            short_form: document.getElementById('short_form').value,
            website_url: document.getElementById('website_url').value,
            full_address: document.getElementById('full_address').value,
            phone_number: document.getElementById('phone_number').value,
            company_logo: document.getElementById('company_logo').value
        };

        try {
            const response = await fetch('update_user_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const text = await response.text();
            let result;

            try {
                result = JSON.parse(text);
            } catch (err) {
                console.error('Response is not valid JSON:', text);
                Swal.fire('Error', 'Server returned an invalid response.', 'error');
                return;
            }

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    loadUserData(formData.user_id);
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }

        } catch (error) {
            console.error('Fetch error:', error);
            Swal.fire('Error', 'Failed to update data: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
</script>

<?php include './footer.php'; ?>