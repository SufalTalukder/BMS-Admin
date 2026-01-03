<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Manage Newsletter</h1>
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
                                    <th>User</th>
                                    <th>Action By</th>
                                    <th>Subscribed</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr id="loader-row">
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm"></div>
                                        <strong>Newsletter(s) Loading...</strong>
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
    <!-- add newsletter modal starts -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Newsletter</h5>
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
                                <label for="inputText" class="col-sm-12 col-form-label">Subscribe *</label>
                                <div class="col-sm-12">
                                    <select class="form-select" name="addSubscribe" id="addSubscribe" required>
                                        <option value="">-- Select --</option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnClick">
                                <span id="addNewsletterSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; pointer-events: none;"></span>
                                <span class="saveNewsletter" id="saveNewsletterText">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- add newsletter modal end -->
    <!-- modals end -->
</main>

<script type="text/javascript">
    // Add
    $(document).on('click', '.saveNewsletter', function() {
        const addUser = $('#addUser').val();
        const addSubscribe = $('#addSubscribe').val();

        $('#btnClick').addClass('disabled');
        $('#saveNewsletterText').text('');
        $('#addNewsletterSpinner').show();

        function stopLoading() {
            $('#addNewsletterSpinner').hide();
            $('#saveNewsletterText').text('Save');
            $('#btnClick').removeClass('disabled');
        }

        if (!addUser) {
            showToast("User is required.", "warning");
            stopLoading();
            return;
        }
        if (!addSubscribe) {
            showToast("Subscription selection is required.", "warning");
            stopLoading();
            return;
        }

        const csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        const csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        const formData = new FormData();
        formData.append('userId', addUser);
        formData.append('addSubscribe', addSubscribe);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: "<?= base_url('add-newsletter') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Newsletter added successfully!", "success");
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
    let dataTable;

    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-newsletters') ?>",
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
                    $('#loader-row td').html('No newsletter(s) found.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Update
    $(document).on('click', '#toggleNewsletter', function() {
        const newsletterId = $(this).attr('data-id');
        const userId = $(this).attr('data-uid');
        const toggleData = $(this).attr('data-toggle');

        const formData = new FormData();
        formData.append('newsletterId', newsletterId);
        formData.append('userId', userId);
        formData.append('toggleData', toggleData);

        $.ajax({
            url: "<?= base_url('update-newsletter') ?>",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    showToast("Newsletter updated successfully!", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || "Something went wrong.", "error");
                }
            },
            error: function() {
                showToast("Server error!", "error");
            }
        });
    });
</script>