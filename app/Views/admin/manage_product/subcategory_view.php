<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage Subcategories</h1>
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
                                        <center id="subCategoryResponse">Subcategorie(s) List Loading...</center>
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
    <!-- add subcategory modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Add Subcategory</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addSubCategoryName" id="addSubcategoryName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addSubcategoryActive" id="addSubcategoryActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="addSubcategorySpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveSubcategory" id="saveSubcategoryText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add subcategory modal end -->

    <!-- edit subcategory modal starts -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Update Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" id="editSubcategoryBody">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <!-- dynamic content will be loaded -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="updateSubcategorySpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="updateSubcategory" id="updateSubcategoryText">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit subcategory modal end -->

    <!-- delete subcategory modal starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Subcategory?</p>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete subcategory modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add
    $(document).on('click', '.saveSubcategory', function() {
        const addSubcategoryName = $('#addSubcategoryName').val().trim();
        const addSubcategoryActive = $('#addSubcategoryActive').val();

        $('#saveSubcategoryText').text('Saving...');
        $('#addSubcategorySpinner').show();

        function stopLoading() {
            $('#addSubcategorySpinner').hide();
            $('#saveSubcategoryText').text('Save');
        }

        if (!addSubcategoryName) {
            showToast("Subcategory name is required.", "warning");
            stopLoading();
            return;
        }
        if (!addSubcategoryActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('subCategoryName', addSubcategoryName);
        formData.append('subCategoryActive', addSubcategoryActive);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('add-sub-category') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Subcategory added successfully!", "success");
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
            url: "<?= base_url('fetch-sub-categories') ?>",
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
                    $('#subCategoryResponse').html(response.message || "No Subcategorie(s) found!");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Get
    function getSubCategory(getSubCategoryId) {
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $.ajax({
            url: "<?= base_url('get-sub-category-details') ?>",
            method: "POST",
            data: {
                getSubCategoryId: getSubCategoryId,
                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('#editSubcategoryBody').html(response.html);
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
    $(document).on('click', '.updateSubcategory', function() {
        const updateSubcategoryId = $('#updateSubcategoryId').val();
        const updateSubcategoryName = $('#updateSubcategoryName').val().trim();
        const updateSubcategoryActive = $('#updateSubcategoryActive').val();

        $('#updateSubcategoryText').text('Updating...');
        $('#updateSubcategorySpinner').show();

        function stopLoading() {
            $('#updateSubcategorySpinner').hide();
            $('#updateSubcategoryText').text('Update');
        }

        if (!updateSubcategoryName) {
            showToast("Subcategory name is required.", "warning");
            stopLoading();
            return;
        }
        if (!updateSubcategoryActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('updateSubcategoryId', updateSubcategoryId);
        formData.append('updateSubcategoryName', updateSubcategoryName);
        formData.append('updateSubcategoryActive', updateSubcategoryActive);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('update-sub-category') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Subcategory updated successfully!", "success");
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
    function deleteSubCategory(deleteSubCategoryId) {
        $('#deleteModal').modal('show');
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-sub-category') ?>",
                method: "POST",
                data: {
                    deleteSubCategoryId: deleteSubCategoryId,
                    [csrfName]: csrfHash
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        showToast("Subcategory deleted successfully!", "success");
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