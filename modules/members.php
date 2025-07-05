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
            <th>Email</th>
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
                                        <input type="text" class="form-control" id="firstName" name="firstName" value="" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middleName" name="middleName" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" value="" required>
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
                                        <input type="text" class="form-control" id="contact" name="contact" value="" placeholder="ex.09123456789">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div id="step-2" class="tab-pane" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="addressLine" class="form-label">Address Line</label>
                                        <input type="text" class="form-control" id="addressLine" name="addressLine" value="" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="Olongapo City" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label">Province</label>
                                        <input type="text" class="form-control" id="state" name="state" value="Zambales" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="postalCode" class="form-label">Postal Code</label>
                                        <input type="number" class="form-control" id="postalCode" name="postalCode" value="2200" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="primary" name="primary" value="1">
                                            <label class="form-check-label" for="primary">Primary Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="step-3" class="tab-pane" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">Select Ministry</label>
                                    <div class="row">
                                        <?php if (!empty($ministry_r)) : ?>
                                            <?php foreach ($ministry_r as $ministry) : ?>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="ministry_<?= htmlspecialchars($ministry['id']) ?>"
                                                            name="ministry[]"
                                                            value="<?= htmlspecialchars($ministry['id']) ?>" <?= $ministry['auto'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="ministry_<?= htmlspecialchars($ministry['id']) ?>">
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
                        <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">Previous</button>
                        <button type="button" id="nextBtn" class="btn btn-primary" style="display: none;">Next</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary" style="display: none;">Submit</button>
                    </div>
                </div>
            </form>
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
                                data: 'email'
                            },
                            {
                                data: 'bday'
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

                // Determine current step
                const stepIndex = parseInt($('#smartwizard .nav .nav-link.active').attr('href').match(/\d+/)[0], 10);

                // Only validate on steps 1–3
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

                        const modal = bootstrap.Modal.getInstance(document.getElementById('addMemberModal'));
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

        bindEvents: function() {
            $('#memberTable').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                memberTable.editMember(id);
            });

            $('#memberTable').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                memberTable.deleteMember(id);
            });

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

                    // Hide all buttons by default
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

                // Manual wizard navigation
                $('#nextBtn').on('click', function() {
                    const step = memberTable.validate();

                    if (!step) return;

                    $('#smartwizard').smartWizard("next");
                });

                $('#prevBtn').on('click', function() {
                    $('#smartwizard').smartWizard("prev");
                });

                $('#addMemberModal').on('hidden.bs.modal', function() {
                    $('#smartwizard').smartWizard("reset"); // Go back to step 0
                });
            });
        },

        populateReview: function() {
            const ministries = [];
            document.querySelectorAll('input[name="ministry[]"]:checked').forEach(input => {
                const label = document.querySelector(`label[for="${input.id}"]`);
                if (label) ministries.push(label.innerText.trim());
            });

            const firstName = $('#firstName').val() || '';
            const middleName = $('#middleName').val() || '';
            const lastName = $('#lastName').val() || '';
            const gender = $('#gender').val() || '';
            const birthdate = $('#birthdate').val() || '';
            const contact = $('#contact').val() || '';
            const addressLine = $('#addressLine').val() || '';
            const city = $('#city').val() || '';
            const state = $('#state').val() || '';
            const postalCode = $('#postalCode').val() || '';
            const isPrimary = $('#primary').is(':checked') ? 'Yes' : 'No';

            const summaryHtml = `
        <ul class="list-group">
            <li class="list-group-item"><strong>Name:</strong> ${firstName} ${middleName} ${lastName}</li>
            <li class="list-group-item"><strong>Gender:</strong> ${gender}</li>
            <li class="list-group-item"><strong>Birthdate:</strong> ${birthdate}</li>
            <li class="list-group-item"><strong>Contact:</strong> ${contact}</li>
            <li class="list-group-item"><strong>Address:</strong> ${addressLine}, ${city}, ${state}, ${postalCode}</li>
            <li class="list-group-item"><strong>Primary Address:</strong> ${isPrimary}</li>
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
                case 1:
                    isValid = validate.requiredfields([{
                            element: document.querySelector('input[name="firstName"]'),
                            message: 'First name missing.'
                        },
                        {
                            element: document.querySelector('input[name="middleName"]'),
                            message: 'Middle name missing.'
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
                            element: document.querySelector('input[name="contact"]'),
                            message: 'Contact missing.'
                        },
                    ]);

                    console.log(isValid);

                    break;
                case 2:
                    isValid = validate.requiredfields([{
                            element: document.querySelector('input[name="addressLine"]'),
                            message: 'House #, Street name missing.'
                        },
                        {
                            element: document.querySelector('input[name="city"]'),
                            message: 'City missing.'
                        },
                        {
                            element: document.querySelector('input[name="state"]'),
                            message: 'State missing.'
                        },
                        {
                            element: document.querySelector('input[name="postalCode"]'),
                            message: 'Postal code missing.'
                        },
                    ]);
                    break;
                case 3:
                    const ministryChecked = document.querySelectorAll('input[name="ministry[]"]:checked').length > 0;
                    if (!ministryChecked) {
                        toastr.error("Please select at least one ministry.");
                        isValid = false;
                    } else {
                        isValid = true;
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