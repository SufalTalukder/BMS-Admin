<main id="main" class="main">
  <div class="pagetitle d-flex justify-content-between align-items-center">
    <h1 class="mb-0">Manage Carts</h1>
    <div class="d-flex align-items-center gap-2">
      <select class="form-select" name="filterByUserId" id="filterByUserId" style="min-width: 100px;">
        <option value="">-- Filter User --</option>
        <?php
        foreach ($user_list as $user) {
          echo '<option value="' . $user->userId . '">' . $user->fullName . '</option>';
        }
        ?>
      </select>
      <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal" style="min-width: 150px;">
        + Add Record
      </button>
    </div>
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
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>User</th>
                  <th>Action By</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tcategory">
                <tr id="loader-row">
                  <td colspan="8" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm"></div>
                    <strong>Cart(s) Loading...</strong>
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
  <!-- add cart modal starts -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Cart</h5>
          <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="card">
            <div class="card-body">
              <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">User *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addUser" id="addUser" required>
                    <option value="">-- Select --</option>
                    <?php
                    foreach ($user_list as $user) {
                      echo '<option value="' . $user->userId . '">' . $user->fullName . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Product *</label>
                <div class="col-sm-12">
                  <select class="form-select" name="addProduct" id="addProduct" required>
                    <option value="">-- Select --</option>
                    <?php
                    foreach ($product_list as $product) {
                      echo '<option value="' . $product->productId . '">' . $product->productName . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Quantity *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addQuantity" id="addQuantity" autocomplete="none" maxlength="8" required>
                </div>
              </div>
              <div class="row mb-3">
                <label for="inputText" class="col-sm-12 col-form-label">Price *</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" name="addPrice" id="addPrice" autocomplete="new-price" maxlength="10" readonly required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="btnClick">
                <span id="addCartSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                <span class="saveCart" id="saveCartText">Save</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- add cart modal end -->

  <!-- delete cart modal starts -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure! you want to delete this Cart?</p>
          <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- delete cart modal end -->
  <!-- modals end -->
</main>

<script type="text/javascript">
  // Add
  $(document).on('click', '.saveCart', function() {
    const addProduct = $('#addProduct').val();
    const addUser = $('#addUser').val();
    const addQuantity = $('#addQuantity').val().trim();
    const addPrice = $('#addPrice').val().trim();

    $('#btnClick').addClass('disabled');
    $('#saveCartText').text('');
    $('#addCartSpinner').show();

    function stopLoading() {
      $('#addCartSpinner').hide();
      $('#saveCartText').text('Save');
      $('#btnClick').removeClass('disabled');
    }

    if (!addUser) {
      showToast("User is required.", "warning");
      stopLoading();
      return;
    }
    if (!addProduct) {
      showToast("Product is required.", "warning");
      stopLoading();
      return;
    }
    if (!addQuantity) {
      showToast("Quantity is required.", "warning");
      stopLoading();
      return;
    }
    if (!addPrice) {
      showToast("Price is required.", "warning");
      stopLoading();
      return;
    }

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    const formData = new FormData();
    formData.append('userId', addUser);
    formData.append('productId', addProduct);
    formData.append('quantity', addQuantity);
    formData.append('eachProductTotalPrice', addPrice);
    formData.append(csrfName, csrfHash);

    $.ajax({
      url: "<?= base_url('add-cart') ?>",
      type: "POST",
      dataType: "json",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.status) {
          showToast("Cart added successfully!", "success");
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

  // Get Product Price
  $(document).on('change', '#addProduct', function() {
    const productId = $(this).val();
    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $.ajax({
      url: "<?= base_url('get-product-price') ?>",
      method: "POST",
      data: {
        productId: productId,
        [csrfName]: csrfHash
      },
      dataType: "json",
      success: function(response) {
        if (response.status) {
          $('#addPrice').val(response.price);
        } else {
          showToast(response.message, "error");
        }
      },
      error: function(xhr, status, error) {
        showToast("Server error!", "error");
      }
    });
  });

  // Get All
  let dataTable = null;

  $(document).ready(function() {

    function fetchCarts(userId = 0) {
      $.ajax({
        url: "<?= base_url('fetch-carts') ?>",
        type: "GET",
        data: {
          userId
        },
        dataType: "json",
        success: function(response) {

          if (dataTable) {
            dataTable.destroy();
            dataTable = null;
          }

          if (!response.status) {
            $('#tcategory').html(`
              <tr>
                <td colspan="8" class="text-center text-muted">
                  ${response.message}
                </td>
              </tr>
            `);
            return;
          }

          $('#tcategory').html(response.html);

          dataTable = new simpleDatatables.DataTable("#demo-table", {
            searchable: true,
            sortable: true
          });
        },
        error: function(xhr) {
          console.error(xhr.responseText);
        }
      });
    }
    fetchCarts(0);

    // Filter by user
    $('#filterByUserId').on('change', function() {
      const userId = $(this).val() || 0;
      fetchCarts(userId);
    });
  });

  // Delete
  function deleteCart(cartId, userId) {
    $('#deleteModal').modal('show');

    const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
    const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

    $('#confirmDelete').off('click').on('click', function() {
      $.ajax({
        url: "<?= base_url('delete-cart') ?>",
        type: "POST",
        data: {
          cartId,
          userId,
          [csrfName]: csrfHash
        },
        dataType: "json",
        success: function(response) {
          if (response.status) {
            showToast("Cart removed successfully!", "success");
            fetchCarts($('#filterByUserId').val() || 0);
            $('#deleteModal').modal('hide');
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