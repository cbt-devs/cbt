<?php
require_once __DIR__ . '/../init.php';
$members_r = $member->show();
$commitments_type = $commitments->type_show();
$ministry_r = $ministry->show();

$today = new DateTime();

// Clone and set to start of the week (Sunday)
$startOfWeek = clone $today;
$startOfWeek->modify('Sunday last week');
if ($today->format('w') == 0) {
    $startOfWeek = clone $today; // if today is Sunday, it's the start
}

// End of the week (Saturday)
$endOfWeek = clone $startOfWeek;
$endOfWeek->modify('+6 days');

// Format like: Sun, July 6 2025
$weekRange = $startOfWeek->format('D, F j Y') . ' - ' . $endOfWeek->format('D, F j Y');
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

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h2 class="m-0">Attendance</h2>
    </div>

    <div class="d-flex align-items-center justify-content-center flex-grow-1" style="min-width: 250px;">
        <button class="btn border me-2" id="prevDate">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <span id="dateDisplay" class="text-center fw-bold" style="white-space: nowrap;">
            <?= $weekRange ?>
        </span>
        <button class="btn border ms-2" id="nextDate">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus"></i> Add Attendance
        </button>
    </div>
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
            this.bindEvents();
        },

        show: function(startDate = null, endDate = null) {
            // Fallback to current week if no dates provided
            if (!startDate || !endDate) {
                const today = new Date();
                const sunday = new Date(today);
                sunday.setDate(today.getDate() - today.getDay()); // Sunday

                const saturday = new Date(sunday);
                saturday.setDate(sunday.getDate() + 6); // Saturday

                startDate = sunday.toISOString().split('T')[0];
                endDate = saturday.toISOString().split('T')[0];
            }

            $.ajax({
                type: "POST",
                url: "controller/main.php",
                data: {
                    action: "show",
                    type: "attendance",
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    const data = response.data;

                    if ($.fn.dataTable.isDataTable('#attendanceTable')) {
                        $('#attendanceTable').DataTable().clear().destroy();
                    }

                    $('#attendanceTable').DataTable({
                        data: data,
                        lengthChange: false,
                        columns: [{
                                data: 'name',
                                title: 'Name'
                            },
                            {
                                data: 'raw_date',
                                visible: false,
                                searchable: false
                            },
                            {
                                data: 'date',
                                title: 'Date'
                            },
                            {
                                data: 'type',
                                title: 'Type',
                                render: function(data) {
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

        bindEvents: function() {
            // Nice Select init
            document.querySelectorAll(".nice-select2").forEach(el => {
                NiceSelect.bind(el);
            });

            $(document).on('click', '.nice-select', function() {
                const $select = $(this);
                const $dropdown = $select.find('.list');
                const selectWidth = $select.outerWidth();
                $dropdown.css({
                    'width': selectWidth + 'px',
                    'min-width': selectWidth + 'px',
                    'left': 0
                });
            });

            // Member search
            const searchInput = document.getElementById('customSearch');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    $('#memberList').DataTable().search(this.value).draw();
                });
            }

            // WEEK NAVIGATION
            const dateDisplay = document.getElementById('dateDisplay');
            let startOfWeek = new Date('<?= $startOfWeek->format('Y-m-d') ?>');
            let endOfWeek = new Date('<?= $endOfWeek->format('Y-m-d') ?>');

            function formatWeekRange(startDate, endDate) {
                const options = {
                    weekday: 'short',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                };
                return `${startDate.toLocaleDateString('en-US', options)} - ${endDate.toLocaleDateString('en-US', options)}`;
            }

            const updateWeekDisplay = () => {
                const startStr = startOfWeek.toISOString().split('T')[0];
                const endStr = endOfWeek.toISOString().split('T')[0];
                dateDisplay.textContent = formatWeekRange(startOfWeek, endOfWeek);
                attendanceTable.show(startStr, endStr);
            };

            document.getElementById('prevDate').addEventListener('click', () => {
                startOfWeek.setDate(startOfWeek.getDate() - 7);
                endOfWeek.setDate(endOfWeek.getDate() - 7);
                updateWeekDisplay();
            });

            document.getElementById('nextDate').addEventListener('click', () => {
                startOfWeek.setDate(startOfWeek.getDate() + 7);
                endOfWeek.setDate(endOfWeek.getDate() + 7);
                updateWeekDisplay();
            });

            updateWeekDisplay(); // First load

            // Row checkbox toggle
            $(document).on('click', '#memberList tbody tr', function(e) {
                if (e.target.tagName.toLowerCase() === 'input') return;
                const $checkbox = $(this).find('input.row-checkbox');
                $checkbox.prop('checked', !$checkbox.prop('checked'));
            });

            this.validate(); // keep this if you're using form submission
        },

        validate: function() {
            document.getElementById('addMemberForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const attendanceType = document.querySelector('select[name="attendance_type"]');
                const ministry = document.querySelector('select[name="ministry"]');
                const checkedCheckboxes = document.querySelectorAll('#memberList .row-checkbox:checked');

                const isValid = validate.requiredfields([{
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

                const form = document.getElementById('addMemberForm');
                const formData = new FormData(form);
                formData.append('action', 'add');
                formData.append('type', 'attendance');

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
                        attendanceTable.show(); // reload table
                    } else {
                        toastr.error('Failed to add attendance');
                        console.error('Failed:', result.message);
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    alert('Something went wrong while submitting.');
                }
            });
        }
    };

    $(document).ready(function() {
        attendanceTable.init();
    });
</script>