<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Track Your Activity</h1>
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
                                    <th>Method</th>
                                    <th>Message</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr id="loader-row">
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm"></div>
                                        <strong class="ms-2">Activity Log(s) Loading...</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    // Get All
    let dataTable;

    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-auth-activity') ?>",
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
                    $('#loader-row td').html('No activity log(s) found.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
</script>