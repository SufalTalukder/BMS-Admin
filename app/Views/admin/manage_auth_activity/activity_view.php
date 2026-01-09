<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Track Your Activity</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12 px-0">
                <div class="card">
                    <div class="card-body">
                        <div class="datatable-top d-flex justify-content-between align-items-center mb-2 d-none">
                            <div class="datatable-search d-flex gap-2">
                                <button class="datatable-button" id="export-csv">Export CSV</button>
                                <button class="datatable-button" id="export-excel">Export Excel</button>
                                <button class="datatable-button" id="export-pdf">Export PDF</button>
                                <button class="datatable-button" id="export-doc">Export DOC</button>
                                <button class="datatable-button" id="export-txt">Export TXT</button>
                                <button class="datatable-button" id="export-sql">Export SQL</button>
                            </div>
                        </div>
                        <div class="text-center" id="loader-row">
                            <div class="spinner-border spinner-border-sm"></div>
                            <strong class="ms-2">Activity Log(s) Loading...</strong>
                        </div>
                        <div id="activityCalendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    $(document).ready(function() {
        var calendarEl = document.getElementById('activityCalendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            height: 650,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridYear,dayGridMonth,timeGridWeek,timeGridDay'
            },

            views: {
                dayGridYear: {
                    type: 'dayGrid',
                    duration: {
                        years: 1
                    },
                    buttonText: 'Year'
                }
            },

            events: function(fetchInfo, successCallback, failureCallback) {
                $('#loader-row').remove();
                $.ajax({
                    url: "<?= base_url('fetch-auth-activity') ?>",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        successCallback(response);
                    },
                    error: function() {
                        alert('Failed to fetch activities!');
                        failureCallback();
                    }
                });
            },

            eventDidMount: function(info) {
                // Tooltip on hover
                new bootstrap.Tooltip(info.el, {
                    title: info.event.extendedProps.description,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();
    });
</script>