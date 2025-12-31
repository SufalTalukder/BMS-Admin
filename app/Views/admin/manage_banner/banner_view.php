<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage Banners</h1>
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
                        <!-- Table -->
                        <table class="datatable table table-hover table-sm" id="demo-table">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Image</th>
                                    <th>Action By</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr id="loader-row">
                                    <td colspan="5" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm"></div>
                                        <strong class="ms-2">Banner(s) Loading...</strong>
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
    <!-- add Banner modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Banner</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                            <div class="row mb-3">
                                <label for="addImageFile" class="col-sm-12 col-form-label">
                                    Upload Images
                                </label>
                                <div class="col-sm-12">
                                    <input
                                        type="file"
                                        class="form-control"
                                        name="appBannerImage"
                                        id="addImageFile"
                                        accept=".png,.jpg,.jpeg"
                                        multiple>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnClick">
                                <span id="addBannerSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveBanner" id="saveBannerText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add Banner modal end -->

    <!-- delete Banner modal starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Banner?</p>
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- delete Banner modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add
    $(document).on('click', '.saveBanner', function() {
        const files = $('#addImageFile')[0].files;
        let formData = new FormData();

        $('#btnClick').addClass('disabled');
        $('#saveBannerText').text('');
        $('#addBannerSpinner').show();

        function stopLoading() {
            $('#addBannerSpinner').hide();
            $('#saveBannerText').text('Save');
            $('#btnClick').removeClass('disabled');
        }

        if (!files || files.length === 0) {
            showToast("Banner image file is required.", "warning");
            stopLoading();
            return;
        }

        for (let i = 0; i < files.length; i++) {
            formData.append('appBannerImage', files[i]);
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $.ajax({
            url: "<?= base_url('add-banner') ?>",
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
                    showToast("Banner(s) uploaded successfully!", "success");
                    setTimeout(() => location.reload(), 1000);
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
    let dataTable;

    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-banners') ?>",
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
                    $('#loader-row td').html('No banner(s) found.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Delete
    function deleteBanner(bannerId) {
        $('#deleteModal').modal('show');
        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-banners') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    bannerIds: [bannerId],
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    if (response.status) {
                        showToast("Banner deleted successfully!", "success");
                        setTimeout(() => location.reload(), 1000);
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