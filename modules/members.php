<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Members Management</h2>
        <p>Here you can manage all your members.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
        <i class="fa-solid fa-plus"></i> Add Member
    </button>
</div>

<table id="memberTable" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Baptism Date</th>
            <th>action</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="addMemberModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="exampleModalLabel">Add Member</h5>
            </div>

            <form id="addMemberForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="q" required>
                        </div>
                        <div class="col-md-4">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middleName" value="q">
                        </div>
                        <div class="col-md-4">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="q" required>
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

                        <div class="col-12">
                            <label for="addressLine" class="form-label">Address Line</label>
                            <input type="text" class="form-control" id="addressLine" name="addressLine" value="q"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="q" required>
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="q" required>
                        </div>
                        <div class="col-md-4">
                            <label for="postalCode" class="form-label">Postal Code</label>
                            <input type="number" class="form-control" id="postalCode" name="postalCode" value="1100"
                                required>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="primary" name="primary" value="1">
                                <label class="form-check-label" for="primary">Primary Address</label>
                            </div>
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
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
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
                    } else {
                        toastr.error('Failed to add the member');
                        console.error('Failed:', result.message);
                    }

                    if (document.activeElement instanceof HTMLElement) {
                        document.activeElement.blur();
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'addMemberModal'));
                    modal.hide();

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