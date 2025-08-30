<?php
require_once __DIR__ . '/../init.php';
$ministry_r = $ministry->show();
?>

<style>
.sw-theme-circles .nav .nav-link.active {
    background-color: var(--bs-primary);
    color: #fff;
    border-color: var(--bs-primary);
}

.sw-theme-circles .nav .nav-link.active .num {
    background-color: #fff;
    color: var(--bs-primary);
    border: 2px solid #fff;
}

.sw-theme-arrows {
    border: none;
}

#memberTable td {
    white-space: nowrap;
}
</style>

<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Members Management</h2>
        <p>Here you can manage all your members.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
        <i class="fa-solid fa-plus"></i> Add Member
    </button>
</div>

<table id="memberTable" class="table" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Baptism</th>
            <th>action</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="addMemberModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="mt-3 d-flex justify-content-center">
                <h5>New Member Form</h5>
            </div>

            <form id="addMemberForm">
                <div class="modal-body">

                    <!-- SmartWizard Steps -->
                    <div id="smartwizard">
                        <ul class="nav">
                            <li><a class="nav-link" href="#step-1">Step 1<br /><small>Personal Info</small></a></li>
                            <li><a class="nav-link" href="#step-2">Step 2<br /><small>Address</small></a></li>
                            <li><a class="nav-link" href="#step-3">Step 3<br /><small>Ministry</small></a></li>
                            <li><a class="nav-link" href="#step-4">Step 4<br /><small>Review</small></a></li>
                        </ul>

                        <div class="tab-content mt-3">
                            <!-- Step 1 -->
                            <div id="step-1" class="tab-pane" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" value=""
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middleName" name="middleName"
                                            value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" value=""
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled>Select gender</option>
                                            <option value="female">Female</option>
                                            <option value="male" selected>Male</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birthdate" class="form-label">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate"
                                            value="<?= date('Y-m-d') ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contact" class="form-label">Contact #</label>
                                        <input type="text" class="form-control" id="contact" name="contact" value=""
                                            placeholder="ex.09123456789">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birthplace" class="form-label">Birth Place</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace"
                                            value="Olongapo City" placeholder="ex.Olongapo City">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="occupation" class="form-label">Occupation</label>
                                        <select class="form-control" id="occupation" name="occupation" required>
                                            <option value="student" selected>Student</option>
                                            <option value="working">Working</option>
                                            <option value="unemployed">Unemployed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="occupation_place" class="form-label">School/Company</label>
                                        <input type="text" class="form-control" id="occupation_place"
                                            name="occupation_place"
                                            placeholder="ex. Gordon Heights National High School">
                                        </input>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="occupation_position" class="form-label">Grade/Position</label>
                                        <input type="text" class="form-control" id="occupation_position"
                                            name="occupation_position" placeholder="ex. Grade 11, Manager">
                                        </input>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Marital Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="" disabled>Select status</option>
                                            <option value="single" selected>Single</option>
                                            <option value="married">Married</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 married_inputs" hidden>
                                        <label for="anniversarydate" class="form-label">Anniversary Date</label>
                                        <input type="date" class="form-control" id="anniversarydate"
                                            name="anniversarydate" required>
                                    </div>

                                    <div class="col-md-8 married_inputs" hidden>
                                        <label for="partner_name" class="form-label">Spouse/Husband Name</label>
                                        <input type="text" class="form-control" id="partner_name" name="partner_name">
                                        </input>
                                    </div>

                                    <div class="col-md-4 married_inputs" hidden>
                                        <label for="partner_occupation" class="form-label">Occupation</label>
                                        <input type="text" class="form-control" id="partner_occupation"
                                            name="partner_occupation">
                                        </input>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div id="step-2" class="tab-pane" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="addressLine" class="form-label">Address Line</label>
                                        <input type="text" class="form-control" id="addressLine" name="addressLine"
                                            value="" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="Olongapo City" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label">Province</label>
                                        <input type="text" class="form-control" id="state" name="state" value="Zambales"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="postalCode" class="form-label">Postal Code</label>
                                        <input type="number" class="form-control" id="postalCode" name="postalCode"
                                            value="2200" required>
                                    </div>
                                    <div class="col-12" hidden>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="primary" name="primary"
                                                value="1">
                                            <label class="form-check-label" for="primary">Primary Address</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="father_name" class="form-label">Father Name</label>
                                        <input type="text" class="form-control" id="father_name" name="father_name"
                                            value="" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mother_name" class="form-label">Mother Name</label>
                                        <input type="text" class="form-control" id="mother_name" name="mother_name"
                                            value="" required>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="father_occupation" class="form-label">Father Occupation</label>
                                        <input type="text" class="form-control" id="father_occupation"
                                            name="father_occupation" value="" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mother_occupation" class="form-label">Mother Occupation</label>
                                        <input type="text" class="form-control" id="mother_occupation"
                                            name="mother_occupation" value="" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="step-3" class="tab-pane" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="date_saved" class="form-label">Date Saved</label>
                                        <input type="date" class="form-control" id="date_saved" name="date_saved"
                                            required>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="witness_by" class="form-label">Witnessed By</label>
                                        <input type="text" class="form-control" id="witness_by" name="witness_by"
                                            value="" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="date_baptized" class="form-label">Date Baptized</label>
                                        <input type="date" class="form-control" id="date_baptized" name="date_baptized"
                                            required>

                                    </div>

                                    <div class="col-md-4">
                                        <label for="baptized_by" class="form-label">Baptized By</label>
                                        <input type="text" class="form-control" id="baptized_by" name="baptized_by"
                                            value="" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="place_of_baptism" class="form-label">Place of Baptism</label>
                                        <input type="text" class="form-control" id="place_of_baptism"
                                            name="place_of_baptism" value="" required>
                                    </div>
                                </div>

                                <div class="col-mb-12 mb-3 mt-3">
                                    <label class="form-label">Select Ministry</label>
                                    <div class="row">
                                        <?php if (!empty($ministry_r)) : ?>
                                        <?php foreach ($ministry_r as $ministry) : ?>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="ministry_<?= htmlspecialchars($ministry['id']) ?>"
                                                    name="ministry[]" value="<?= htmlspecialchars($ministry['id']) ?>"
                                                    <?= $ministry['auto'] ? 'checked' : '' ?>>
                                                <label class="form-check-label"
                                                    for="ministry_<?= htmlspecialchars($ministry['id']) ?>">
                                                    <?= htmlspecialchars($ministry['name']) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                        <p class="text-muted">No ministries found</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div id="step-4" class="tab-pane" role="tabpanel">
                                <h6>Review Information</h6>
                                <div id="reviewSummary">
                                    <!-- Content will be injected by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer with Navigation Buttons -->
                <div class="modal-footer justify-content-between" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <div class="d-flex gap-2">
                        <button type="button" id="prevBtn" class="btn btn-secondary"
                            style="display: none;">Previous</button>
                        <button type="button" id="nextBtn" class="btn btn-primary" style="display: none;">Next</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary"
                            style="display: none;">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="informationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">

            <!-- Header -->
            <div class="modal-header bg-light">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                        style="width:50px; height:50px;">
                        <i class="bi bi-person fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0" id="infoName"></h5>
                        <small class="text-muted" id="infoAddress"></small>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Personal Info Row -->
                <div class="row text-muted mb-3">
                    <div class="col-md-3"><strong>Birthday:</strong><br><span id="infoBday"></span></div>
                    <div class="col-md-3"><strong>Contact #:</strong><br><span id="infoContact"></span><i
                            class="bi bi-check-circle-fill text-success"></i></div>
                    <div class="col-md-3"><strong>Baptized Date:</strong><br><span id="infoBaptized"></span>
                    </div>
                    <div class="col-md-3 text-capitalize"><strong>Marital Status:</strong><br><span
                            id="infoStatus"></span></div>
                </div>

                <!-- <div class="alert alert-danger py-2" role="alert">
                    This Donor is <strong>Temporarily Deferred</strong> for 15 Days
                </div> -->

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3 mt-4" id="infoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="attendance-tab" data-bs-toggle="tab"
                            data-bs-target="#attendance" type="button" role="tab">Attendance</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="card-tab" data-bs-toggle="tab" data-bs-target="#events"
                            type="button" role="tab">Events</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#ministries"
                            type="button" role="tab">Ministries</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#commitments"
                            type="button" role="tab">Commitments</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button"
                            role="tab">Logs</button>
                    </li>
                </ul>

                <!-- Tab Contents -->
                <div class="tab-content" id="infoTabsContent">
                    <div class="tab-pane fade show active" id="attendance" role="tabpanel">
                        <div class="table-responsive">
                            <table id="attendanceTable" class="table table-sm table-bordered align-middle">
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="events" role="tabpanel">
                        <div class="table-responsive">
                            <table id="eventsTable" class="table table-sm table-bordered align-middle">
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ministries" role="tabpanel">
                        <div class="table-responsive">
                            <table id="ministriesTable" class="table table-sm table-bordered align-middle">
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="commitments" role="tabpanel">
                        <div class="table-responsive">
                            <table id="commitmentsTable" class="table table-sm table-bordered align-middle">
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<script>
var memberTable = {
    init: function() {
        this.showMember();
        this.bindEvents();
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

                if ($.fn.dataTable.isDataTable('#memberTable')) {
                    $('#memberTable').DataTable().clear().destroy();
                }

                $('#memberTable').DataTable({
                    data: data,
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'birthdate'
                        },
                        {
                            data: 'address'
                        },
                        {
                            data: 'baptism_date'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}" hidden>
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                `;
                            },
                            orderable: false,
                            searchable: false
                        }
                    ],
                    initComplete: function() {
                        JsLoadingOverlay.hide();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error (show):", status, error);
            }
        });
    },

    addMember: function(formElement) {
        formElement.addEventListener('submit', async function(event) {
            event.preventDefault();

            const stepIndex = parseInt($('#smartwizard .nav .nav-link.active').attr('href').match(
                /\d+/)[0], 10);

            if (stepIndex >= 1 && stepIndex <= 3) {
                const isValid = memberTable.validate();
                if (!isValid) return;
            }

            const formData = new FormData(formElement);
            formData.append('action', 'add');
            formData.append('type', 'members');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire("Member added", "", "success");
                    formElement.reset();
                    memberTable.showMember();

                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'addMemberModal'));
                    modal.hide();
                } else {
                    toastr.error('Failed to add the member');
                    console.error('Failed:', result.message);
                }

                if (document.activeElement instanceof HTMLElement) {
                    document.activeElement.blur();
                }

            } catch (error) {
                console.error('Error (add):', error);
                alert('There was a problem submitting the form.');
            }
        });
    },

    editMember: function(id) {
        Swal.fire({
            title: `Do you want to update member ID ${id}?`,
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                memberTable.action('update', id);
            }
        });
    },

    deleteMember: function(id) {
        Swal.fire({
            title: `Do you want to delete member ID ${id}?`,
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                memberTable.action('delete', id);
            }
        });
    },

    personalInfoTables: function(accounts_id = 0) {
        const type_r = ['attendance', 'events', //'ministries', 'commitments'

        ];

        type_r.forEach(type => {
            $.ajax({
                type: "POST",
                url: "controller/main.php",
                data: {
                    action: "show",
                    type: type,
                    accounts_id: accounts_id
                },
                dataType: 'json',
                success: function(response) {
                    const data = response.data;
                    const table = `#${type}Table`;

                    if ($.fn.dataTable.isDataTable(table)) {
                        $(table).DataTable().clear().destroy();
                    }

                    // Define columns dynamically based on type
                    let columns = [];

                    if (type === "attendance") {
                        columns = [{
                                data: 'date',
                                title: 'Date'
                            },
                            {
                                data: 'type',
                                title: 'Status',
                                render: function(data) {
                                    let badgeClass = 'secondary';
                                    if (data === 'present') badgeClass = 'success';
                                    else if (data === 'absent') badgeClass =
                                        'danger';
                                    else if (data === 'excused') badgeClass =
                                        'warning';
                                    return `<span class="badge bg-${badgeClass} text-capitalize">${data}</span>`;
                                }
                            }
                        ];
                    } else if (type === "events") {
                        columns = [{
                                data: 'event_name',
                                title: 'Event'
                            },
                            {
                                data: 'event_location',
                                title: 'Location'
                            },
                            {
                                data: 'start_date',
                                title: 'Start Date'
                            },
                            {
                                data: 'end_date',
                                title: 'End Date'
                            },
                            {
                                data: 'ministries',
                                title: 'Ministries'
                            }
                        ];
                    } else if (type === "ministries") {
                        columns = [{
                                data: 'name',
                                title: 'Ministry Name'
                            },
                            {
                                data: 'age_start',
                                title: 'Age Start'
                            },
                            {
                                data: 'age_end',
                                title: 'Age End'
                            }
                        ];
                    }

                    // Initialize DataTable
                    $(table).DataTable({
                        data: data,
                        columns: columns,
                        paging: false, // hide pagination
                        searching: false, // hide search box
                        info: false, // hide "Showing 1 of N"
                        ordering: true,
                        order: [
                            [0, 'asc']
                        ] // default sort (adjust per type if needed)
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error loading data:", error);
                }
            });
        });
    },

    bindEvents: function() {
        $('#memberTable').on('click', 'tr', function() {
            const table = $('#memberTable').DataTable();
            const data = table.row(this).data();

            if (data) {
                $('#infoName').text(data.name);
                $('#infoContact').text(data.contact);
                $('#infoBday').text(data.birthdate);
                $('#infoAddress').text(data.address);
                $('#infoBaptized').text(data.baptism_date);
                // $('#infoEmail').text(data.email);
                // $('#infoGender').text(data.gender);
                $('#infoStatus').text(data.marital_status);

                // Optionally store ID for later update/delete
                $('#informationModal').attr('data-id', data.id);

                // Show the modal
                $('#informationModal').modal('show');

                // populate tables
                // attendance data
                memberTable.personalInfoTables(data.id);
            }
        });

        $('#memberTable').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            memberTable.editMember(id);
        });

        $('#memberTable').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            memberTable.deleteMember(id);
        });

        // marital status toggle
        $('#status').on('change', function() {
            if ($(this).val() === 'married') {
                $('.married_inputs').prop('hidden', false);
                $('#anniversarydate, #partner_name, #partner_occupation').attr('required', true);
            } else {
                $('.married_inputs').prop('hidden', true);
                $('#anniversarydate, #partner_name, #partner_occupation').removeAttr('required');
            }
        }).trigger('change');

        const form = document.getElementById('addMemberForm');
        if (form) {
            memberTable.addMember(form);
        }

        $(document).ready(function() {
            $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'arrows',
                justified: true,
                autoAdjustHeight: true,
                backButtonSupport: true,
                transition: {
                    animation: 'slide-horizontal'
                },
                toolbar: {
                    showNextButton: false,
                    showPreviousButton: false
                }
            }).on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                const $nextBtn = $('#nextBtn');
                const $prevBtn = $('#prevBtn');
                const $submitBtn = $('#submitBtn');

                $prevBtn.hide();
                $nextBtn.hide();
                $submitBtn.hide();

                if (stepPosition === 'first') {
                    $nextBtn.show();
                } else if (stepPosition === 'middle') {
                    $prevBtn.show();
                    $nextBtn.show();
                } else if (stepPosition === 'last') {
                    $prevBtn.show();
                    $submitBtn.show();
                    memberTable.populateReview();
                }
            });

            $('#nextBtn').on('click', function() {
                const step = memberTable.validate();
                if (!step) return;
                $('#smartwizard').smartWizard("next");
            });

            $('#prevBtn').on('click', function() {
                $('#smartwizard').smartWizard("prev");
            });

            $('#addMemberModal').on('hidden.bs.modal', function() {
                $('#smartwizard').smartWizard("reset");
            });
        });
    },

    populateReview: function() {
        const ministries = [];
        document.querySelectorAll('input[name="ministry[]"]:checked').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (label) ministries.push(label.innerText.trim());
        });

        // Step 1 - Personal Info
        const firstName = $('#firstName').val() || '';
        const middleName = $('#middleName').val() || '';
        const lastName = $('#lastName').val() || '';
        const gender = $('#gender').val() || '';
        const birthdate = $('#birthdate').val() || '';
        const contact = $('#contact').val() || '';
        const birthplace = $('#birthplace').val() || '';
        const occupation = $('#occupation').val() || '';
        const occupation_place = $('#occupation_place').val() || '';
        const occupation_position = $('#occupation_position').val() || '';
        const status = $('#status').val() || '';

        // Married only
        let spouseHtml = '';
        if (status === 'married') {
            spouseHtml = `
            <li class="list-group-item"><strong>Anniversary:</strong> ${$('#anniversarydate').val()}</li>
            <li class="list-group-item"><strong>Spouse:</strong> ${$('#partner_name').val()} (${ $('#partner_occupation').val() })</li>
        `;
        }

        // Step 2 - Address + Parents
        const addressLine = $('#addressLine').val() || '';
        const city = $('#city').val() || '';
        const state = $('#state').val() || '';
        const postalCode = $('#postalCode').val() || '';
        const isPrimary = $('#primary').is(':checked') ? 'Yes' : 'No';

        const father_name = $('#father_name').val() || '';
        const mother_name = $('#mother_name').val() || '';
        const father_occupation = $('#father_occupation').val() || '';
        const mother_occupation = $('#mother_occupation').val() || '';

        // Step 3 - Salvation & Baptism
        const date_saved = $('#date_saved').val() || '';
        const witness_by = $('#witness_by').val() || '';
        const date_baptized = $('#date_baptized').val() || '';
        const baptized_by = $('#baptized_by').val() || '';
        const place_of_baptism = $('#place_of_baptism').val() || '';

        const summaryHtml = `
    <ul class="list-group">
        <li class="list-group-item"><strong>Name:</strong> ${firstName} ${middleName} ${lastName}</li>
        <li class="list-group-item"><strong>Gender:</strong> ${gender}</li>
        <li class="list-group-item"><strong>Birthdate:</strong> ${birthdate}</li>
        <li class="list-group-item"><strong>Birth Place:</strong> ${birthplace}</li>
        <li class="list-group-item"><strong>Contact:</strong> ${contact}</li>
        <li class="list-group-item"><strong>Occupation:</strong> ${occupation} - ${occupation_place} (${occupation_position})</li>
        <li class="list-group-item"><strong>Marital Status:</strong> ${status}</li>
        ${spouseHtml}

        <li class="list-group-item"><strong>Address:</strong> ${addressLine}, ${city}, ${state}, ${postalCode}</li>
        <li class="list-group-item"><strong>Primary Address:</strong> ${isPrimary}</li>

        <li class="list-group-item"><strong>Father:</strong> ${father_name} (${father_occupation})</li>
        <li class="list-group-item"><strong>Mother:</strong> ${mother_name} (${mother_occupation})</li>

        <li class="list-group-item"><strong>Date Saved:</strong> ${date_saved}</li>
        <li class="list-group-item"><strong>Witnessed By:</strong> ${witness_by}</li>
        <li class="list-group-item"><strong>Date Baptized:</strong> ${date_baptized}</li>
        <li class="list-group-item"><strong>Baptized By:</strong> ${baptized_by}</li>
        <li class="list-group-item"><strong>Place of Baptism:</strong> ${place_of_baptism}</li>

        <li class="list-group-item">
            <strong>Ministries:</strong>
            ${ministries.length > 0
                ? `<ul class="mb-0 mt-2">${ministries.map(m => `<li>${m}</li>`).join('')}</ul>`
                : 'None'}
        </li>
    </ul>
    `;
        $('#reviewSummary').html(summaryHtml);
    },

    validate: function() {
        let isValid = false;
        let stepIndex = parseInt($('#smartwizard .nav .nav-link.active').attr('href').match(/\d+/)[0], 10);

        switch (stepIndex) {
            case 1: // Personal Info
                isValid = validate.requiredfields([{
                        element: document.querySelector('input[name="firstName"]'),
                        message: 'First name missing.'
                    },
                    {
                        element: document.querySelector('input[name="lastName"]'),
                        message: 'Last name missing.'
                    },
                    {
                        element: document.querySelector('select[name="gender"]'),
                        message: 'Gender missing.'
                    },
                    {
                        element: document.querySelector('input[name="birthdate"]'),
                        message: 'Birth date missing.'
                    },
                    {
                        element: document.querySelector('input[name="birthplace"]'),
                        message: 'Birth place missing.'
                    },
                    {
                        element: document.querySelector('input[name="contact"]'),
                        message: 'Contact missing.'
                    },
                    {
                        element: document.querySelector('select[name="occupation"]'),
                        message: 'Occupation missing.'
                    },
                    {
                        element: document.querySelector('input[name="occupation_place"]'),
                        message: 'School/Company missing.'
                    },
                    {
                        element: document.querySelector('input[name="occupation_position"]'),
                        message: 'Grade/Position missing.'
                    },
                    {
                        element: document.querySelector('select[name="status"]'),
                        message: 'Marital status missing.'
                    }
                ]);

                if ($('#status').val() === 'married') {
                    isValid = validate.requiredfields([{
                            element: document.querySelector('input[name="anniversarydate"]'),
                            message: 'Anniversary date missing.'
                        },
                        {
                            element: document.querySelector('input[name="partner_name"]'),
                            message: 'Spouse name missing.'
                        },
                        {
                            element: document.querySelector('input[name="partner_occupation"]'),
                            message: 'Spouse occupation missing.'
                        }
                    ]) && isValid;
                }
                break;

            case 2: // Address + Parents
                isValid = validate.requiredfields([{
                        element: document.querySelector('input[name="addressLine"]'),
                        message: 'Address line missing.'
                    },
                    {
                        element: document.querySelector('input[name="city"]'),
                        message: 'City missing.'
                    },
                    {
                        element: document.querySelector('input[name="state"]'),
                        message: 'Province missing.'
                    },
                    {
                        element: document.querySelector('input[name="postalCode"]'),
                        message: 'Postal code missing.'
                    },
                    {
                        element: document.querySelector('input[name="father_name"]'),
                        message: 'Father name missing.'
                    },
                    {
                        element: document.querySelector('input[name="mother_name"]'),
                        message: 'Mother name missing.'
                    },
                    {
                        element: document.querySelector('input[name="father_occupation"]'),
                        message: 'Father occupation missing.'
                    },
                    {
                        element: document.querySelector('input[name="mother_occupation"]'),
                        message: 'Mother occupation missing.'
                    }
                ]);
                break;

            case 3: // Baptism + Ministries
                isValid = validate.requiredfields([{
                        element: document.querySelector('input[name="date_saved"]'),
                        message: 'Date saved missing.'
                    },
                    {
                        element: document.querySelector('input[name="witness_by"]'),
                        message: 'Witnessed by missing.'
                    },
                    {
                        element: document.querySelector('input[name="date_baptized"]'),
                        message: 'Date baptized missing.'
                    },
                    {
                        element: document.querySelector('input[name="baptized_by"]'),
                        message: 'Baptized by missing.'
                    },
                    {
                        element: document.querySelector('input[name="place_of_baptism"]'),
                        message: 'Place of baptism missing.'
                    }
                ]);

                const ministryChecked = document.querySelectorAll('input[name="ministry[]"]:checked').length >
                    0;
                if (!ministryChecked) {
                    toastr.error("Please select at least one ministry.");
                    isValid = false;
                }
                break;
        }

        return isValid;
    },

    action: function(actionType, id) {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: actionType,
                type: "members",
                id: id
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire(`Member ${actionType}d`, "", "success");
                    memberTable.showMember();
                } else {
                    toastr.error(`Failed to ${actionType} member`);
                    console.error(`${actionType} failed:`, response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(`AJAX Error (${actionType}):`, status, error);
            }
        });
    }
};

$(document).ready(function() {
    memberTable.init();
});
</script>