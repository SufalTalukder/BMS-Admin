<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Manage Users</h1>
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
                  <th>Ph. No.</th>
                  <th>Email</th>
                  <th>DOB</th>
                  <th>Address</th>
                  <th>Referral Code</th>
                  <th>Action By</th>
                  <th>Created At</th>
                  <th>Updated At</th>
                  <th>Active</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tcategory">
                <tr>
                  <td colspan="9">
                    <center id="usersResponse">
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      User(s) Loading...
                    </center>
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
        <div class="modal-header">
          <h5 class="modal-title">Add User</h5>
          <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addName" id="addName" maxlength="100" autocomplete="new-name" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Email Address *</label>
                <div class="col-sm-12">
                  <input type="email" class="form-control" name="addEmail" id="addEmail" maxlength="50" autocomplete="new-email" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Phone Number *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addPhoneNumber" id="addPhoneNumber" minlength="10" maxlength="10" autocomplete="new-phone-number" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Date of Birth *</label>
                <div class="col-sm-12">
                  <input type="date" class="form-control" name="addDOB" id="addDOB" autocomplete="new-dob" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Address *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addAddress" id="addAddress" maxlength="100" autocomplete="new-address" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addActive" id="addActive" required>
                    <option value="">-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="btnClick">
                <span id="addUserSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="saveUser" id="saveUserText">Save</span>
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
        <div class="modal-header">
          <h5 class="modal-title">Update User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body" id="editUserBody">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <!-- dynamic content will be loaded -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="editBtnClick">
                <span id="updateUserSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="updateUser" id="updateUserText">Update</span>
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
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this User?</p>
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
  $(document).on('click', '.saveUser', function() {
    const addName = $('#addName').val().trim();
    const addEmail = $('#addEmail').val().trim();
    const addPhoneNumber = $('#addPhoneNumber').val().trim();
    const addDOB = $('#addDOB').val();
    const addAddress = $('#addAddress').val().trim();
    const addActive = $('#addActive').val();

    $('#saveUserText').text('');
    $('#addUserSpinner').show();
    $('#btnClick').addClass('disabled');

    function stopLoading() {
      $('#addUserSpinner').hide();
      $('#saveUserText').text('Save');
      $('#btnClick').removeClass('disabled');
    }

    if (!addName) {
      showToast("User name is required.", "warning");
      stopLoading();
      return;
    }
    if (!addEmail) {
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
    if (!addDOB) {
      showToast("DOB is required.", "warning");
      stopLoading();
      return;
    }
    if (!addAddress) {
      showToast("Address is required.", "warning");
      stopLoading();
      return;
    }
    if (!addActive) {
      showToast("Active status is required.", "warning");
      stopLoading();
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    const formData = new FormData();
    formData.append('addName', addName);
    formData.append('addEmail', addEmail);
    formData.append('addPhoneNumber', addPhoneNumber);
    formData.append('addDOB', addDOB);
    formData.append('addAddress', addAddress);
    formData.append('addActive', addActive);
    formData.append(csrfName, csrfHash);

    $.ajax({
      url: "<?= base_url('add-user') ?>",
      type: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.status) {
          showToast("User added successfully!", "success");
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
      url: "<?= base_url('fetch-users') ?>",
      type: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('.datatable tbody').html(response.html);
          $('.datatable').DataTable({
            order: [
              [9, 'asc']
            ]
          });
        } else {
          $('#usersResponse').html(response.message || "No user(s) found!");
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
      }
    });
  });

  // Get
  function getUser(userId) {
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $.ajax({
      url: "<?= base_url('get-user-details') ?>",
      method: "POST",
      data: {
        userId: userId,
        [csrfName]: csrfHash
      },
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('#editUserBody').html(response.html);
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
  $(document).on('click', '.updateUser', function() {
    const updateUserId = $('#updateUserId').val();
    const updateName = $('#updateName').val().trim();
    const updateEmail = $('#updateEmail').val().trim();
    const updatePhoneNumber = $('#updatePhoneNumber').val().trim();
    const updateDOB = $('#updateDOB').val().trim();
    const updateAddress = $('#updateAddress').val().trim();
    const updateActive = $('#updateActive').val();
    const updateImageFile = $('#updateImageFile')[0].files[0];

    $('#editBtnClick').addClass('disabled');
    $('#updateUserText').text('');
    $('#updateUserSpinner').show();

    function stopLoading() {
      $('#updateUserSpinner').hide();
      $('#updateUserText').text('Update');
      $('#editBtnClick').removeClass('disabled');
    }

    if (!updateName) {
      showToast("User name is required.", "warning");
      stopLoading();
      return;
    }
    if (!updateEmail) {
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
    if (!updateDOB) {
      showToast("DOB is required.", "warning");
      stopLoading();
      return;
    }
    if (!updateAddress) {
      showToast("Address is required.", "warning");
      stopLoading();
      return;
    }
    if (!updateActive) {
      showToast("Active status is required.", "warning");
      stopLoading();
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    const formData = new FormData();
    formData.append('userId', updateUserId);
    formData.append('updateName', updateName);
    formData.append('updateEmail', updateEmail);
    formData.append('updatePhoneNumber', updatePhoneNumber);
    formData.append('updateDOB', updateDOB);
    formData.append('updateAddress', updateAddress);
    formData.append('updateActive', updateActive);
    formData.append(csrfName, csrfHash);

    if (updateImageFile) {
      formData.append('updateImageFile', updateImageFile);
    }

    $.ajax({
      url: "<?= base_url('update-user') ?>",
      type: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.status) {
          showToast("User updated successfully!", "success");
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
  function deleteUser(userId) {
    $('#deleteModal').modal('show');
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $('#confirmDelete').off('click').on('click', function() {
      $.ajax({
        url: "<?= base_url('delete-user') ?>",
        method: "POST",
        data: {
          userId: userId,
          [csrfName]: csrfHash
        },
        dataType: "json",
        success: function(response) {
          if (response.status) {
            showToast("User deleted successfully!", "success");
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