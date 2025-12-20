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
        <h1 class="mb-0">Manage Banners</h1>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Record
        </button>
    </div>

    <section class="section">
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
                                    <th>Action By</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr>
                                    <td colspan="9">
                                        <center id="bannerResponse">Banner(s) List Loading...</center>
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
    <!-- add Banner modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title">Add Banner</h5>
                    <button type="button" class="btn-close red-bold " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
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
                            <button type="button" class="btn btn-primary">
                                <span id="addBannerSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveBannerUser" id="saveBannerText">Save</span>
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
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure! you want to delete this Banner?</p>
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
    // Add banner
    $(document).on('click', '.saveBannerUser', function() {

        const files = $('#addImageFile')[0].files;
        let formData = new FormData();

        $('#saveBannerText').text('Saving...');
        $('#addBannerSpinner').show();

        function stopLoading() {
            $('#addBannerSpinner').hide();
            $('#saveBannerText').text('Save');
        }

        if (!files || files.length === 0) {
            showToast("Image file is required.", "warning");
            stopLoading();
            return;
        }

        for (let i = 0; i < files.length; i++) {
            formData.append('appBannerImage', files[i]);
        }

        $.ajax({
            url: "<?= base_url('add-banner') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
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

    // Fetch and display banner
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-banners') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.datatable tbody').html(response.html);
                    $('.datatable').DataTable();
                } else {
                    $('#bannerResponse').html(response.message || "No banner(s) found!");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Delete banner
    function deleteBanner(BannerId) {
        $('#deleteModal').modal('show');
        $('#confirmDelete').off('click').on('click', function() {
            $.ajax({
                url: "<?= base_url('delete-banner') ?>",
                method: "POST",
                data: {
                    BannerId: BannerId
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        showToast("Banner user deleted successfully!", "success");
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