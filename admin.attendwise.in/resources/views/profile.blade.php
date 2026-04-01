<x-structure />
<x-header />
<div class="profile-container">
    <div class="row g-4">
        <!-- Left Side: Profile Image -->
        <div class="col-md-4 text-center">
            <div class="profile-pic-wrapper">
                <img src="{{env("PLACEHOLDER_IMAGE")}}" alt="Profile Picture" id="profileImage" class="profile-pic">
                <input type="file" id="profileImageUpload" accept="image/*" style="display: none;">
                <div class="profile-edit-icon" id="profileEditIcon">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="col-md-8">
            <form id="profileForm">
                <!-- Information Section -->
                <div class="profile-section">
                    <div class="profile-section-title">Information</div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="profileName" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="tel" class="form-control" id="profileMobile" placeholder="Enter your mobile number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="profileEmail" placeholder="Enter your email" required>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="profile-section">
                    <div class="profile-section-title">Change Password</div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="profilePassword" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="profileConfirmPassword" placeholder="Confirm new password">
                        <small id="profilePasswordHelp" class="text-danger d-none">Passwords do not match!</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Open file dialog when edit icon is clicked
    document.getElementById('profileEditIcon').addEventListener('click', function() {
        document.getElementById('profileImageUpload').click();
    });

    // Update profile picture preview
    document.getElementById('profileImageUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if(file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Form submit handler
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        const password = document.getElementById('profilePassword').value;
        const confirmPassword = document.getElementById('profileConfirmPassword').value;
        const passwordHelp = document.getElementById('profilePasswordHelp');

        if (password !== confirmPassword) {
            passwordHelp.classList.remove('d-none');
            event.preventDefault(); // Stop form submission
        } else {
            passwordHelp.classList.add('d-none');
        }
    });
</script>

<x-footer />