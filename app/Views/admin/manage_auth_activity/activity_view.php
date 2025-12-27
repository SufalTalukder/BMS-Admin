<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Track Your Activity</h1>
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
                                    <th>Method</th>
                                    <th>Message</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody id="tcategory">
                                <tr>
                                    <td colspan="9">
                                        <center id="activityResponse">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Activity Log(s) Loading...
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
</main>

<script type="text/javascript">
    // Get All
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('fetch-auth-activity') ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.datatable tbody').html(response.html);
                    $('.datatable').DataTable({
                        order: [
                            [3, 'desc']
                        ]
                    });
                } else {
                    $('#activityResponse').html(response.message || 'No activity log(s) found.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
</script>