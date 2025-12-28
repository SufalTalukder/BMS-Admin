<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/activity-service'); ?>">Activity</a></li>
                <li class="breadcrumb-item active">My Profile</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="<?= !empty($extracted_auth_user_details->authUserImage) ? $extracted_auth_user_details->authUserImage : base_url('assets/img/admin.jfif') ?>" alt="Profile" class="rounded-circle">
                        <h2><?= !empty($extracted_auth_user_details->authUserName) ? $extracted_auth_user_details->authUserName : "NA"; ?></h2>
                        <h3><?= !empty($extracted_auth_user_details->authUserType) && ($extracted_auth_user_details->authUserType === "SUPER_ADMIN") ? "SUPER ADMIN" : "ADMIN"; ?></h3>
                        <div class="social-links mt-2">
                            <a href="https://github.com/SufalTalukder" class="github" target="_blank"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">About</h5>
                                <p class="small fst-italic"><?= PROJECT_ABOUT; ?></p>
                                <h5 class="card-title">Profile Details</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"><?= !empty($extracted_auth_user_details->authUserName) ? $extracted_auth_user_details->authUserName : "NA"; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Company</div>
                                    <div class="col-lg-9 col-md-8"><?= PROJECT_NAME; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Job</div>
                                    <div class="col-lg-9 col-md-8"><?= !empty($extracted_auth_user_details->authUserType) && ($extracted_auth_user_details->authUserType === "SUPER_ADMIN") ? "SUPER ADMIN" : "ADMIN"; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8"><?= !empty($extracted_auth_user_details->authUserPhoneNumber) ? $extracted_auth_user_details->authUserPhoneNumber : "NA"; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?= !empty($extracted_auth_user_details->authUserEmailAddress) ? $extracted_auth_user_details->authUserEmailAddress : "NA"; ?></div>
                                </div>
                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <input type="hidden" id="updateAuthId" value="<?= !empty($extracted_auth_user_details->authUserId) ? $extracted_auth_user_details->authUserId : ""; ?>">
                                <input type="hidden" id="updateAuthType" value="<?= !empty($extracted_auth_user_details->authUserType) ? $extracted_auth_user_details->authUserType : ""; ?>">
                                <input type="hidden" id="updateAuthActive" value="<?= !empty($extracted_auth_user_details->authUserActive) ? $extracted_auth_user_details->authUserActive : ""; ?>">
                                <input type="hidden" id="updatePassword" value="<?= !empty($extracted_auth_user_details->authUserPassword) ? $extracted_auth_user_details->authUserPassword : ""; ?>">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                    <div class="col-md-8 col-lg-9">
                                        <img src="<?= !empty($extracted_auth_user_details->authUserImage) ? $extracted_auth_user_details->authUserImage : base_url('assets/img/admin.jfif') ?>" alt="Profile">
                                        <div class="pt-2">
                                            <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="fullName" type="text" class="form-control" id="updateAuthName" value="<?= !empty($extracted_auth_user_details->authUserName) ? $extracted_auth_user_details->authUserName : "NA"; ?>" autocomplete="new-name" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9">
                                        <textarea name="about" class="form-control" id="about" style="height: 100px" disabled><?= PROJECT_ABOUT; ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="company" type="text" class="form-control" id="company" value="<?= PROJECT_NAME; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="phone" type="text" class="form-control" id="updatePhoneNumber" value="<?= !empty($extracted_auth_user_details->authUserPhoneNumber) ? $extracted_auth_user_details->authUserPhoneNumber : "NA"; ?>" autocomplete="new-phone" maxlength="10" minlength="10" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="updateAuthEmail" value="<?= !empty($extracted_auth_user_details->authUserEmailAddress) ? $extracted_auth_user_details->authUserEmailAddress : "NA"; ?>" autocomplete="new-email" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">GitHub Profile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="twitter" type="text" class="form-control" id="Twitter" value="https://github.com/SufalTalukder" disabled>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary">
                                        <span id="updateAuthSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                        <span class="updateAuthUser" id="updateAuthText">Save Changes</span>
                                    </button>
                                </div>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-settings">
                                <!-- Settings Form -->
                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                            <label class="form-check-label" for="changesMade">
                                                Changes made to your account
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                            <label class="form-check-label" for="newProducts">
                                                Information on new products and services
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proOffers">
                                            <label class="form-check-label" for="proOffers">
                                                Marketing and promo offers
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                                            <label class="form-check-label" for="securityNotify">
                                                Security alerts
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <input type="hidden" id="authUserId" value="<?= !empty($extracted_auth_user_details->authUserId) ? $extracted_auth_user_details->authUserId : ""; ?>">
                                <input type="hidden" id="authUserEmail" value="<?= !empty($extracted_auth_user_details->authUserEmailAddress) ? $extracted_auth_user_details->authUserEmailAddress : ""; ?>">
                                <input type="hidden" id="authUserName" value="<?= !empty($extracted_auth_user_details->authUserName) ? $extracted_auth_user_details->authUserName : ""; ?>">
                                <input type="hidden" id="authUserPhone" value="<?= !empty($extracted_auth_user_details->authUserPhoneNumber) ? $extracted_auth_user_details->authUserPhoneNumber : ""; ?>">
                                <input type="hidden" id="authUserType" value="<?= !empty($extracted_auth_user_details->authUserType) ? $extracted_auth_user_details->authUserType : ""; ?>">
                                <input type="hidden" id="authUserActive" value="<?= !empty($extracted_auth_user_details->authUserActive) ? $extracted_auth_user_details->authUserActive : ""; ?>">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="password" type="password" class="form-control" id="currentPassword" value="<?= !empty($extracted_auth_user_details->authUserPassword) ? $extracted_auth_user_details->authUserPassword : ""; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="newpassword" type="text" class="form-control" id="updatePassword" autocomplete="new-password" value="<?= !empty($extracted_auth_user_details->authUserPassword) ? base64_decode($extracted_auth_user_details->authUserPassword) : ""; ?>" maxlength="20" minlength="8" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary">
                                        <span id="updateAuthSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                        <span class="updateAuthUser" id="updateAuthText">Change Password</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    // Update
    $(document).on('click', '.updateAuthUser', function() {
        const authUserId = $('#updateAuthId').val();
        const updateAuthName = $('#updateAuthName').val().trim();
        const updateAuthEmail = $('#updateAuthEmail').val().trim();
        const updatePhoneNumber = $('#updatePhoneNumber').val().trim();
        const updatePassword = $('#updatePassword').val().trim();
        const updateAuthType = $('#updateAuthType').val();
        const updateAuthActive = $('#updateAuthActive').val();
        const passwordRegex = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

        $('#updateAuthText').text('Updating...');
        $('#updateAuthSpinner').show();

        function stopLoading() {
            $('#updateAuthSpinner').hide();
            $('#updateAuthText').text('Save Changes');
        }

        if (!updateAuthName) {
            showToast("Name is required.", "warning");
            stopLoading();
            return;
        }
        if (!updateAuthEmail) {
            showToast("Email is required.", "warning");
            stopLoading();
            return;
        }
        if (!updatePhoneNumber) {
            showToast("Phone number is required.", "warning");
            stopLoading();
            return;
        }
        if (!/^\d{10}$/.test(updatePhoneNumber)) {
            showToast("Phone number must be exactly 10 digits.", "warning");
            stopLoading();
            return;
        }
        // if (updatePassword != "" && !passwordRegex.test(updatePassword)) {
        //     showToast(
        //         "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.",
        //         "warning"
        //     );
        //     stopLoading();
        //     return;
        // }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('authId', authUserId);
        formData.append('authName', updateAuthName);
        formData.append('authEmail', updateAuthEmail);
        formData.append('phoneNumber', updatePhoneNumber);
        formData.append('password', updatePassword);
        formData.append('authType', updateAuthType);
        formData.append('authActive', updateAuthActive);

        $.ajax({
            url: "<?= base_url('update-auth-user') ?>",
            type: "POST",
            dataType: "json",
            data: {
                formData,
                [csrfName]: csrfHash
            },
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Auth user details updated successfully!", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || "Something went wrong.", "error");
                }
            },
            error: function() {
                showToast("Server error!", "error");
            },
            complete: function() {
                stopLoading();
            }
        });
    });
</script>