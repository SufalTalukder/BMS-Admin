<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage Categories</h1>
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
                                    <th>Name</th>
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
                                        <center id="categoryResponse">Categorie(s) List Loading...</center>
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
    <!-- add Category modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addCategoryName" id="addCategoryName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addCategoryActive" id="addCategoryActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="addCategorySpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveCategory" id="saveCategoryText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add Category modal end -->

    <!-- edit Category modal starts -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Update Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" id="editCategoryBody">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <!-- dynamic content will be loaded -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="updateCategorySpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="updateCategory" id="updateCategoryText">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit Category modal end -->

    <!-- delete Category modal starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Category?</p>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete Category modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add
    $(document).on('click', '.saveCategory', function() {
        const addCategoryName = $('#addCategoryName').val().trim();
        const addCategoryActive = $('#addCategoryActive').val();

        $('#saveCategoryText').text('Saving...');
        $('#addCategorySpinner').show();

        function stopLoading() {
            $('#addCategorySpinner').hide();
            $('#saveCategoryText').text('Save');
        }

        if (!addCategoryName) {
            showToast("Category name is required.", "warning");
            stopLoading();
            return;
        }
        if (!addCategoryActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('categoryName', addCategoryName);
        formData.append('categoryActive', addCategoryActive);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('add-category') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Category added successfully!", "success");
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
            url: "<?= base_url('fetch-categories') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.datatable tbody').html(response.html);
                    $('.datatable').DataTable({
                        order: [
                            [3, 'asc']
                        ]
                    });
                } else {
                    $('#categoryResponse').html(response.message || "No Categorie(s) found!");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Get
    function getCategory(getCategoryId) {
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $.ajax({
            url: "<?= base_url('get-category-details') ?>",
            method: "POST",
            data: {
                getCategoryId: getCategoryId,
                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('#editCategoryBody').html(response.html);
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
    $(document).on('click', '.updateCategory', function() {
        const updateCategoryId = $('#updateCategoryId').val();
        const updateCategoryName = $('#updateCategoryName').val().trim();
        const updateCategoryActive = $('#updateCategoryActive').val();

        $('#updateCategoryText').text('Updating...');
        $('#updateCategorySpinner').show();

        function stopLoading() {
            $('#updateCategorySpinner').hide();
            $('#updateCategoryText').text('Update');
        }

        if (!updateCategoryName) {
            showToast("Category name is required.", "warning");
            stopLoading();
            return;
        }
        if (!updateCategoryActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('updateCategoryId', updateCategoryId);
        formData.append('updateCategoryName', updateCategoryName);
        formData.append('updateCategoryActive', updateCategoryActive);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('update-category') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Category updated successfully!", "success");
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
    function deleteCategory(deleteCategoryId) {
        $('#deleteModal').modal('show');
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-category') ?>",
                method: "POST",
                data: {
                    deleteCategoryId: deleteCategoryId,
                    [csrfName]: csrfHash
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        showToast("Category deleted successfully!", "success");
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