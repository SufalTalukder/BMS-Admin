<style>
    #basicModal .modal-dialog {
        max-width: 600px;
        width: 100%;
    }

    #basicModal .modal-content {
        padding: 20px;
    }

    .modal-body {
        max-height: 400px;
        overflow-y: auto;

    }

    .custom-modal-header {
        background-color: #0bcbe2;
        /* Dark Red Background */
        color: white;
        /* White Text Color */
    }

    .custom-modal-header .modal-title {
        font-weight: bold;
        /* Bold Text */
    }

    .red-bold {
        color: red;
        font-weight: bold;
    }
</style>

<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage languages</h1>
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
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Active</th>
                                    <th>Action By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr>
                                    <td colspan="9">
                                        <center id="languageResponse">Languages(s) List Loading...</center>
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
    <!-- add Language modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Add Language</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="addLanguageName" id="addLanguageName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addLanguageActive" id="addLanguageActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="addLanguageSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveLanguage" id="saveLanguageText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add Language modal end -->

    <!-- edit Language modal starts -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Update Language</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="updateLanguageId" name="updateLanguageId" value="">
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="updateLanguageName" id="updateLanguageName" maxlength="100" autocomplete="new-name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="updateLanguageActive" id="updateLanguageActive" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary">
                                <span id="updateLanguageSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="updateLanguage" id="updateLanguageText">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- edit Language modal end -->

    <!-- delete Language modal starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Language?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete Language modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add Language user
    $(document).on('click', '.saveLanguage', function() {

        const addLanguageName = $('#addLanguageName').val().trim();
        const addLanguageActive = $('#addLanguageActive').val();

        $('#saveLanguageText').text('Saving...');
        $('#addLanguageSpinner').show();

        function stopLoading() {
            $('#addLanguageSpinner').hide();
            $('#saveLanguageText').text('Save');
        }

        if (!addLanguageName) {
            showToast("Language name is required.", "warning");
            stopLoading();
            return;
        }
        if (!addLanguageActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const formData = new FormData();
        formData.append('languageName', addLanguageName);
        formData.append('languageActive', addLanguageActive);

        $.ajax({
            url: "<?= base_url('add-language') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Language added successfully!", "success");
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

    // Fetch and display Language users
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-languages') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.datatable tbody').html(response.html);
                    $('.datatable').DataTable();
                } else {
                    $('#languageResponse').html(response.message || "No Language(s) found!");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Open edit modal and populate data
    function getLanguage(getLanguageId) {
        $.ajax({
            url: "<?= base_url('get-language-details') ?>",
            method: "POST",
            data: {
                getLanguageId: getLanguageId
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('#updateLanguageId').val(getLanguageId);
                    $('#updateLanguageName').val(response.content.languageName);
                    $('#updateLanguageActive').val(response.content.languageActive);
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

    // Update Language user
    $(document).on('click', '.updateLanguage', function() {

        const updateLanguageId = $('#updateLanguageId').val();
        const updateLanguageName = $('#updateLanguageName').val().trim();
        const updateLanguageActive = $('#updateLanguageActive').val();

        $('#updateLanguageText').text('Updating...');
        $('#updateLanguageSpinner').show();

        function stopLoading() {
            $('#updateLanguageSpinner').hide();
            $('#updateLanguageText').text('Update');
        }

        if (!updateLanguageName) {
            showToast("Language name is required.", "warning");
            stopLoading();
            return;
        }
        if (!updateLanguageActive) {
            showToast("Active status is required.", "warning");
            stopLoading();
            return;
        }

        const formData = new FormData();
        formData.append('updateLanguageId', updateLanguageId);
        formData.append('updateLanguageName', updateLanguageName);
        formData.append('updateLanguageActive', updateLanguageActive);

        $.ajax({
            url: "<?= base_url('update-language') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Language updated successfully!", "success");
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

    // Delete Language user
    function deleteLanguage(deleteLanguageId) {
        $('#deleteModal').modal('show');
        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-language') ?>",
                method: "POST",
                data: {
                    deleteLanguageId: deleteLanguageId
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        showToast("Language deleted successfully!", "success");
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