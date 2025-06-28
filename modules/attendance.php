<?php
require_once __DIR__ . '/../init.php';
$members_r = $member->show();
$commitments_type = $commitments->type_show();
$ministry_r = $ministry->show();

$today = new DateTime();
$formatted_today = $today->format('l d, Y');
$type_r = Attendance::TYPE;
?>
<style>
    /* Hide only the label text before the search input */
    .dt-search input,
    .dt-search label {
        display: none;
    }

    #addMemberForm .nice-select {
        margin-bottom: 15px !important;
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

<div class="col-md-4">
    <div class="input-group mt-3 mb-2">
        <span class="input-group-text">
            <i class="fa-solid fa-magnifying-glass"></i>
        </span>
        <input type="text" id="attendanceSearch" class="form-control" placeholder="Search member...">
    </div>
</div>

<table id="attendanceTable" class="table table-striped" style="width:100%"></table>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
            </div>

            <form id="addMemberForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col">
                            <select name="attendance_type" class="form-control nice-select2">
                                <option value="0" selected>Please Select Type</option>
                                <?php foreach ($type_r as $key => $label) { ?>
                                    <option value="<?= htmlspecialchars($key) ?>">
                                        <?= htmlspecialchars(ucfirst($label)) ?>
                                    </option>
                                <?php } ?>
                            </select>
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
                            <input type="text" id="customSearch" class="form-control mb-2 mt-2" placeholder="Search members...">
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
    var attendanceTable = {
        init: function() {
            this.show();
            this.showMember();
            this.bindEvents();
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

                    // Destroy existing table if it exists
                    if ($.fn.dataTable.isDataTable('#attendanceTable')) {
                        $('#attendanceTable').DataTable().clear().destroy();
                    }

                    // Initialize DataTable
                    $('#attendanceTable').DataTable({
                        data: data,
                        lengthChange: false,
                        columns: [{
                                data: 'name',
                                title: 'Name'
                            },
                            {
                                data: 'date_attendance',
                                title: 'Date'
                            },
                            {
                                data: 'type',
                                title: 'Type',
                                render: function(data, type, row) {
                                    let badgeClass = 'secondary';
                                    if (data === 'present') badgeClass = 'success';
                                    else if (data === 'absent') badgeClass = 'danger';
                                    else if (data === 'excused') badgeClass = 'warning';

                                    return `<span class="badge bg-${badgeClass} text-capitalize">${data}</span>`;
                                }
                            }
                        ],
                        responsive: true,
                        autoWidth: false,
                        order: [
                            [1, 'desc']
                        ],
                        language: {
                            emptyTable: "No attendance records found."
                        },
                        initComplete: function() {
                            // Bind custom search
                            const table = this.api();
                            $('#attendanceSearch').off('keyup').on('keyup', function() {
                                table.search(this.value).draw();
                            });
                            JsLoadingOverlay.hide();
                        }
                    });

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
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error (show):", status, error);
                }
            });
        },

        validate: function() {
            document.getElementById('addMemberForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const attendanceType = document.querySelector('select[name="attendance_type"]');
                const ministry = document.querySelector('select[name="ministry"]');
                const checkedCheckboxes = document.querySelectorAll('#memberList .row-checkbox:checked');

                const isValid = validateRequiredFields([{
                        element: attendanceType,
                        message: 'Please select an attendance type.'
                    },
                    {
                        element: ministry,
                        message: 'Please select a ministry.'
                    }
                ]);

                if (!isValid) return;

                if (checkedCheckboxes.length === 0) {
                    const searchInput = document.getElementById('customSearch');
                    showValidationTooltip(searchInput, 'Please select at least one member.');
                    return;
                }

                // ✅ Prepare FormData
                const form = document.getElementById('addMemberForm');
                const formData = new FormData(form);
                formData.append('action', 'add');
                formData.append('type', 'attendance');

                // Append selected member IDs
                checkedCheckboxes.forEach(cb => {
                    formData.append('members[]', cb.dataset.id);
                });

                try {
                    JsLoadingOverlay.show();

                    const response = await fetch('controller/main.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        Swal.fire("Member added", "", "success");
                        $('#addModal').modal('hide');
                        form.reset();
                        $('#memberList').DataTable().search('').draw();
                        attendanceTable.show();
                    } else {
                        toastr.error('Failed to add attendance');
                        console.error('Failed:', result.message);
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('Something went wrong while submitting.');
                }
            });
        },

        bindEvents: function() {
            document.querySelectorAll(".nice-select2").forEach(el => {
                NiceSelect.bind(el);
            });

            $(document).on('click', '.nice-select', function() {
                const $select = $(this);
                const $dropdown = $select.find('.list');

                // Match the width of the visible .nice-select element
                const selectWidth = $select.outerWidth();

                $dropdown.css({
                    'width': selectWidth + 'px',
                    'min-width': selectWidth + 'px',
                    'left': 0
                });
            });


            const searchInput = document.getElementById('customSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    $('#memberList').DataTable().search(this.value).draw();
                });
            }

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

            // Row click toggles checkbox
            $(document).on('click', '#memberList tbody tr', function(e) {
                if (e.target.tagName.toLowerCase() === 'input') return;
                const $checkbox = $(this).find('input.row-checkbox');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
            });

            this.validate();
        },

    }

    $(document).ready(function() {
        attendanceTable.init();
    });
</script>