<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Manage Wishlists</h1>
    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal">
      + Add Record
    </button>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12 px-0">
        <div class="card">
          <div class="card-body">
            <div class="datatable-top d-flex justify-content-between align-items-center mb-2">
              <div class="datatable-search d-flex gap-2">
                <button class="datatable-button" id="export-csv">Export CSV</button>
                <button class="datatable-button" id="export-excel">Export Excel</button>
                <button class="datatable-button" id="export-pdf">Export PDF</button>
                <button class="datatable-button" id="export-doc">Export DOC</button>
                <button class="datatable-button" id="export-txt">Export TXT</button>
                <button class="datatable-button" id="export-sql">Export SQL</button>
              </div>
            </div>
            <!-- Table with stripped rows -->
            <table class="datatable table table-hover table-sm" id="demo-table">
              <thead>
                <tr>
                  <th>Sr. No.</th>
                  <th>Product</th>
                  <th>User</th>
                  <th>Action By</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tcategory">
                <tr id="loader-row">
                  <td colspan="6" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm"></div>
                    <strong>Wishlist(s) Loading...</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- modals starts -->
  <!-- add wishlist modal starts -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Wishlist</h5>
          <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Product *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addProduct" id="addProduct" required>
                    <option value="">-- Select --</option>
                    <?=
                    $product_list = json_encode($product_list);
                    $product_list = json_decode($product_list);
                    foreach ($product_list as $product) {
                      echo '<option value="' . $product->productId . '">' . $product->productName . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">User *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addUser" id="addUser" required>
                    <option value="">-- Select --</option>
                    <?=
                    $user_list = json_encode($user_list);
                    $user_list = json_decode($user_list);
                    foreach ($user_list as $user) {
                      echo '<option value="' . $user->userId . '">' . $user->fullName . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="btnClick">
                <span id="addWishlistSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="saveWishlist" id="saveWishlistText">Save</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- add wishlist modal end -->

  <!-- delete wishlist modal starts -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure! you want to delete this Wishlist?</p>
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- delete wishlist modal end -->
  <!-- modals end -->
</main>

<script type="text/javascript">
  // Add
  $(document).on('click', '.saveWishlist', function() {
    const addProduct = $('#addProduct').val();
    const addUser = $('#addUser').val();

    $('#btnClick').addClass('disabled');
    $('#saveWishlistText').text('');
    $('#addWishlistSpinner').show();

    function stopLoading() {
      $('#addWishlistSpinner').hide();
      $('#saveWishlistText').text('Save');
      $('#btnClick').removeClass('disabled');
    }

    if (!addProduct) {
      showToast("Product is required.", "warning");
      stopLoading();
      return;
    }
    if (!addUser) {
      showToast("User is required.", "warning");
      stopLoading();
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    const formData = new FormData();
    formData.append('productId', addProduct);
    formData.append('userId', addUser);
    formData.append(csrfName, csrfHash);

    $.ajax({
      url: "<?= base_url('add-wishlist') ?>",
      type: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.status) {
          showToast("Wishlist added successfully!", "success");
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
      url: "<?= base_url('fetch-wishlists') ?>",
      type: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('#loader-row').remove();
          $('#tcategory').append(response.html);

          dataTable = new simpleDatatables.DataTable("#demo-table", {
            searchable: true,
            sortable: true
          });
        } else {
          $('#loader-row td').html('No wishlist(s) found.');
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
      }
    });
  });

  // Delete
  function deleteWishlist(wishlistId, userId) {
    $('#deleteModal').modal('show');
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $('#confirmDelete').off('click').on('click', function() {
      $.ajax({
        url: "<?= base_url('delete-wishlist') ?>",
        method: "POST",
        data: {
          wishlistId: wishlistId,
          userId: userId,
          [csrfName]: csrfHash
        },
        dataType: "json",
        success: function(response) {
          if (response.status) {
            showToast("Wishlist deleted successfully!", "success");
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