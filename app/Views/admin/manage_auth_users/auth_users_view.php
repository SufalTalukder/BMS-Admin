<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Manage Auth Users</h1>
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal">
      + Add Record
    </button>
  </div>

  <section class="section" style="overflow-x: scroll;">
    <div class="row">
      <div class="col-lg-12 px-0">
        <div class="card">
          <div class="card-body p-3">
            <!-- Table with stripped rows -->
            <table class="table datatable table-sm table-hover">
              <thead>
                <tr>
                  <th>Sr. No.</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Ph. No.</th>
                  <th>Type</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th>Active</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tcategory">
                <tr>
                  <td colspan="9">
                    <center id="authUsersResponse">Auth User(s) List Loading...</center>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- End Table with stripped rows -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- modals starts -->
  <!-- add auth modal starts -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header custom-modal-header">
          <h5 class="modal-title">Add Auth User</h5>
          <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addAuthName" id="addAuthName" maxlength="100" autocomplete="new-name" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Email Address *</label>
                <div class="col-sm-12">
                  <input type="email" class="form-control" name="addAuthEmail" id="addAuthEmail" maxlength="50" autocomplete="new-email" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Phone Number *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addPhoneNumber" id="addPhoneNumber" minlength="10" maxlength="10" autocomplete="new-phone-number" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Password *</label>
                <div class="col-sm-12">
                  <input type="password" class="form-control" name="addPassword" id="addPassword" maxlength="50" autocomplete="new-password" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Type *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addAuthType" id="addAuthType" required>
                    <option value="">-- Select --</option>
                    <option value="SUPER_ADMIN">Super Admin</option>
                    <option value="ADMIN">Admin</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addAuthActive" id="addAuthActive" required>
                    <option value="">-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputFile" class="col-sm-12 col-form-label">Upload Image</label>
                <div class="col-sm-12">
                  <input type="file" class="form-control" name="image" id="addImageFile" accept="png, jpg, jpeg">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">
                <span id="addAuthSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="saveAuthUser" id="saveAuthText">Save</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- add auth modal end -->

  <!-- edit auth modal starts -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header custom-modal-header">
          <h5 class="modal-title">Update Auth User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body" id="editAuthBody">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <!-- dynamic content will be loaded -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">
                <span id="updateAuthSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="updateAuthUser" id="updateAuthText">Update</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- edit auth modal end -->

  <!-- delete auth modal starts -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this auth?</p>
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- delete auth modal end -->
  <!-- modals end -->
</main>

<script type="text/javascript">
  // Add
  $(document).on('click', '.saveAuthUser', function() {
    const addAuthName = $('#addAuthName').val().trim();
    const addAuthEmail = $('#addAuthEmail').val().trim();
    const addPhoneNumber = $('#addPhoneNumber').val().trim();
    const addPassword = $('#addPassword').val().trim();
    const addAuthType = $('#addAuthType').val();
    const addAuthActive = $('#addAuthActive').val();
    const addImageFile = $('#addImageFile')[0].files[0];
    const passwordRegex = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

    $('#saveAuthText').text('Saving...');
    $('#addAuthSpinner').show();

    function stopLoading() {
      $('#addAuthSpinner').hide();
      $('#saveAuthText').text('Save');
    }

    if (!addAuthName) {
      showToast("Auth name is required.", "warning");
      stopLoading();
      return;
    }
    if (!addAuthEmail) {
      showToast("Email is required.", "warning");
      stopLoading();
      return;
    }
    if (!addPhoneNumber) {
      showToast("Phone number is required.", "warning");
      stopLoading();
      return;
    }
    if (!/^\d{10}$/.test(addPhoneNumber)) {
      showToast("Phone number must be exactly 10 digits.", "warning");
      stopLoading();
      return;
    }
    if (!addPassword) {
      showToast("Password is required.", "warning");
      stopLoading();
      return;
    }
    if (!passwordRegex.test(addPassword)) {
      showToast(
        "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.",
        "warning"
      );
      stopLoading();
      return;
    }
    if (!addAuthType) {
      showToast("Auth type is required.", "warning");
      stopLoading();
      return;
    }
    if (!addAuthActive) {
      showToast("Active status is required.", "warning");
      stopLoading();
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    const formData = new FormData();
    formData.append('authName', addAuthName);
    formData.append('authEmail', addAuthEmail);
    formData.append('phoneNumber', addPhoneNumber);
    formData.append('password', addPassword);
    formData.append('authType', addAuthType);
    formData.append('authActive', addAuthActive);

    if (addImageFile) {
      formData.append('imageFile', addImageFile);
    }

    $.ajax({
      url: "<?= base_url('add-auth-user') ?>",
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
          showToast("Auth user added successfully!", "success");
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

  // Get All
  $(document).ready(function() {
    $.ajax({
      url: "<?= base_url('fetch-auth-users') ?>",
      type: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('.datatable tbody').html(response.html);
          $('.datatable').DataTable({
            order: [
              [6, 'desc']
            ]
          });
        } else {
          $('#authUsersResponse').html(response.message || "No auth user(s) found!");
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
      }
    });
  });

  // Get
  function getAuth(authId) {
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $.ajax({
      url: "<?= base_url('get-auth-user-details') ?>",
      method: "POST",
      data: {
        authId: authId,
        [csrfName]: csrfHash
      },
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('#editAuthBody').html(response.html);
          $('#editModal').modal('show');
        } else {
          showToast(response.message, "error");
        }
      },
      error: function(xhr, status, error) {
        showToast("Server error!", "error");
      }
    });
  }

  // Update
  $(document).on('click', '.updateAuthUser', function() {
    const authUserId = $('#updateAuthId').val();
    const updateAuthName = $('#updateAuthName').val().trim();
    const updateAuthEmail = $('#updateAuthEmail').val().trim();
    const updatePhoneNumber = $('#updatePhoneNumber').val().trim();
    const updatePassword = $('#updatePassword').val().trim();
    const updateAuthType = $('#updateAuthType').val();
    const updateAuthActive = $('#updateAuthActive').val();
    const updateImageFile = $('#updateImageFile')[0].files[0];
    const passwordRegex = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

    $('#updateAuthText').text('Updating...');
    $('#updateAuthSpinner').show();

    function stopLoading() {
      $('#updateAuthSpinner').hide();
      $('#updateAuthText').text('Update');
    }

    if (!updateAuthName) {
      showToast("Auth name is required.", "warning");
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
    if (updatePassword != "" && !passwordRegex.test(updatePassword)) {
      showToast(
        "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.",
        "warning"
      );
      stopLoading();
      return;
    }
    if (!updateAuthType) {
      showToast("Auth type is required.", "warning");
      stopLoading();
      return;
    }
    if (!updateAuthActive) {
      showToast("Active status is required.", "warning");
      stopLoading();
      return;
    }

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

    if (updateImageFile) {
      formData.append('updateImageFile', updateImageFile);
    }

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
          showToast("Auth user updated successfully!", "success");
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

  // Delete
  function deleteAuth(authId) {
    $('#deleteModal').modal('show');
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $('#confirmDelete').off('click').on('click', function() {
      $.ajax({
        url: "<?= base_url('delete-auth-user') ?>",
        method: "POST",
        data: {
          authId: authId,
          [csrfName]: csrfHash
        },
        dataType: "json",
        success: function(response) {
          if (response.status) {
            showToast("Auth user deleted successfully!", "success");
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            showToast(response.message, "error");
          }
        },
        error: function() {
          showToast("Server error!", "error");
        }
      });
    });
  }
</script>