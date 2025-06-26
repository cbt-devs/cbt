<?php
require_once __DIR__ . '/../init.php';
$members_r = $member->show();
$commitments_type = $commitments->type_show();
$ministry_r = $ministry->show();

$today = new DateTime();
$formatted_today = $today->format('l d, Y');
?>
<style>
    /* Hide only the label text before the search input */
    .dt-search label {
        font-size: 0;
    }
</style>

<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Attendance</h2>
    </div>
    <div>
        <button class="btn border" id="prevDate">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <span id="dateDisplay" style="display: inline-block; width: 180px; text-align: center; white-space: nowrap; font-weight: bold;">
            <?= $formatted_today ?>
        </span>
        <button class="btn border" id="nextDate">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i> Add Attendance
    </button>
</div>

<div class="row g-3 mt-2">
    <!-- Present Summary -->
    <div class="col-12">
        <div class="card shadow-sm p-3">
            <div class="d-flex justify-content-center flex-row flex-nowrap gap-5">
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Present (AM)</span>
                    <h5 class="fw-bold">12</h5>
                    <span class="text-secondary"><span class="text-primary">+5</span> last week</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Absent (AM)</span>
                    <h5 class="fw-bold">4</h5>
                    <span class="text-secondary"><span class="text-danger">-2</span> last week</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Excused (AM)</span>
                    <h5 class="fw-bold">2</h5>
                    <span class="text-secondary"><span class="text-danger">-1</span> last week</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Present (PM)</span>
                    <h5 class="fw-bold">12</h5>
                    <span class="text-secondary"><span class="text-primary">+5</span> last week</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Absent (PM)</span>
                    <h5 class="fw-bold">4</h5>
                    <span class="text-secondary"><span class="text-danger">-2</span> last week</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-secondary">Excused (PM)</span>
                    <h5 class="fw-bold">2</h5>
                    <span class="text-secondary"><span class="text-danger">-1</span> last week</span>
                </div>
            </div>
        </div>
    </div>
</div>

<table id="attendanceTable" class="table table-striped" style="width:100%"></table>

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
            </div>

            <form id="addMemberForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="ministry" class="form-label">Ministry</label>
                            <select class="form-control nice-select2" id="ministry" name="ministry">
                                <option value="all" selected>All</option>
                                <?php
                                if (!empty($ministry_r)) {
                                    foreach ($ministry_r as $ministry) {
                                        echo '<option value="' . htmlspecialchars($ministry['id']) . '">' . htmlspecialchars($ministry['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No ministries found</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <table id="memberList" class="table table-striped"></table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const dateDisplay = document.getElementById('dateDisplay');
    let currentDate = new Date('<?= $today->format('Y-m-d') ?>'); // from PHP

    function formatDate(date) {
        const options = {
            weekday: 'long',
            day: 'numeric',
            year: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    }

    document.getElementById('prevDate').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 1);
        dateDisplay.textContent = formatDate(currentDate);
    });

    document.getElementById('nextDate').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 1);
        dateDisplay.textContent = formatDate(currentDate);
    });

    var attendanceTable = {
        init: function() {
            this.show();
            this.showMember();
        },
        show: function() {
            $.ajax({
                type: "POST",
                url: "controller/main.php",
                data: {
                    action: "show",
                    type: "attendance"
                },
                success: function(response) {
                    const data = response.data;

                    console.log(data);

                    if ($.fn.dataTable.isDataTable('#attendanceTable')) {
                        $('#attendanceTable').DataTable().clear().destroy();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error (show):", status, error);
                }
            });
        },
        showMember: function() {
            $.ajax({
                type: "POST",
                url: "controller/main.php",
                data: {
                    action: "show",
                    type: "members"
                },
                success: function(response) {
                    const data = response.data;

                    if ($.fn.dataTable.isDataTable('#memberList')) {
                        $('#memberList').DataTable().clear().destroy();
                    }

                    $('#memberList').DataTable({
                        data: data,
                        searching: true,
                        info: false,
                        lengthChange: false,
                        columns: [{
                                title: '<input type="checkbox" id="select-all">', // header checkbox
                                orderable: false,
                                searchable: false,
                                className: 'text-center',
                                render: function(data, type, row, meta) {
                                    return `<input type="checkbox" class="row-checkbox" data-id="${row.id}">`;
                                }
                            },
                            {
                                data: 'name',
                                title: 'name'
                            },
                        ],
                        initComplete: function() {
                            $('#memberList thead').hide();
                            $('#dt-search-0').attr('placeholder', 'Search...');
                            JsLoadingOverlay.hide();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error (show):", status, error);
                }
            });
        }
    }

    $(document).ready(function() {
        attendanceTable.init();

        document.querySelectorAll(".nice-select2").forEach(el => {
            NiceSelect.bind(el);
        });
    });
</script>