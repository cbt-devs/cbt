<h2>Ministry Management</h2>
<p>Here you can manage all ministries.</p>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMinistryModal">
    <i class="fa-solid fa-plus"></i> Add Ministry
</button>

<table id="ministryTable" class="table table-striped" style="width:100%"></table>

<!-- Add Ministry Modal -->
<div class="modal fade" id="addMinistryModal" tabindex="-1" aria-labelledby="addMinistryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="addMinistryModalLabel">Add Ministry</h5>
            </div>

            <form id="addMinistryForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="ministryName" class="form-label">Ministry Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ministryName" name="ministryName" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Age Range</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" class="form-control" id="startAge" name="startAge" min="0"
                                    max="100" step="1" placeholder="Start Age"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)">
                                <span class="mx-2">to</span>
                                <input type="number" class="form-control" id="endAge" name="endAge" min="0" max="100"
                                    step="1" placeholder="End Age"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2)">
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


<!-- Edit Ministry Modal -->
<div class="modal fade" id="updateMinistryModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ministry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editMinistryForm">
                <input type="hidden" id="editMinistryId" name="id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Ministry Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editMinistryName" name="ministryName" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Age Range</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" class="form-control" id="editStartAge" name="startAge" min="0"
                                    max="100" step="1" placeholder="Start Age">
                                <span class="mx-2">to</span>
                                <input type="number" class="form-control" id="editEndAge" name="endAge" min="0"
                                    max="100" step="1" placeholder="End Age">
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="updateActive" name="active"
                                    value="1">
                                <label class="form-check-label" for="updateActive">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
var ministryTable = {
    init: function() {
        this.bindEvents();
        this.showMinistries();
    },

    showMinistries: function() {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: "show",
                type: "ministries"
            },
            success: function(response) {
                const data = response.data;

                if ($.fn.dataTable.isDataTable('#ministryTable')) {
                    $('#ministryTable').DataTable().clear().destroy();
                }

                $('#ministryTable').DataTable({
                    data: data,
                    columns: [{
                            data: 'name',
                            title: 'Ministry Name'
                        },
                        {
                            data: 'age_start',
                            title: 'Start Age'
                        },
                        {
                            data: 'age_end',
                            title: 'End Age'
                        },
                        {
                            data: 'active',
                            title: 'Active',
                            className: 'text-center',
                            render: function(data, type, row) {
                                return `<div class="text-center">
                                            ${data == 1 
                                                ? '<span class="text-success fw-bold">&#10003;</span>' 
                                                : '<span class="text-danger fw-bold">&#10007;</span>'}
                                        </div>`;
                            }
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
                            searchable: false,
                            title: 'Actions'
                        }
                    ]
                });
            }

        });
    },

    addMinistry: function(formElement) {
        formElement.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(formElement);
            formData.append('action', 'add');
            formData.append('type', 'ministries');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire("Ministry added", "", "success");
                    formElement.reset();
                    ministryTable.showMinistries();
                } else {
                    toastr.error('Failed to add the ministry');
                    console.error('Error:', result.message);
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'addMinistryModal'));
                if (modal) modal.hide();

            } catch (error) {
                console.error('Error:', error);
                alert('There was a problem submitting the form.');
            }
        });
    },

    edit: function(formElement) {
        formElement.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(formElement);
            formData.append('action', 'update');
            formData.append('type', 'ministries');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire("Ministry updated", "", "success");
                    ministryTable.showMinistries();
                } else {
                    toastr.error('Failed to update the ministry');
                    console.error('Update error:', result.message);
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'updateMinistryModal'));
                if (modal) modal.hide();
            } catch (error) {
                console.error('Fetch error:', error);
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                alert('There was a problem updating the ministry.');
            }
        });
    },


    delete: function(id, name) {
        Swal.fire({
            title: `Do you want to disable ministry ${name}?`,
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: `No`
        }).then((result) => {
            if (result.isConfirmed) {
                ministryTable.action('delete', id);
            }
        });
    },

    bindEvents: function() {
        $('#ministryTable').on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const rowData = $('#ministryTable').DataTable().row($(this).parents('tr')).data();

            $('#editMinistryId').val(id);
            $('#editMinistryName').val(rowData.name);
            $('#editStartAge').val(rowData.age_start);
            $('#editEndAge').val(rowData.age_end);
            $('#updateActive').prop('checked', rowData.active == 1);

            const modal = new bootstrap.Modal(document.getElementById('updateMinistryModal'));
            modal.show();
        });

        $('#ministryTable').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const name = $(this).closest('tr').find('td:first').text();
            ministryTable.delete(id, name);
        });

        const form = document.getElementById('addMinistryForm');
        if (form) this.addMinistry(form);

        const editForm = document.getElementById('editMinistryForm');
        if (editForm) this.edit(editForm); // <- ADD THIS LINE
    },


    action: function(actionType, id) {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: actionType,
                type: "ministries",
                id: id
            },
            success: function(response) {
                console.log(response);

                if (response.status === 'success') {
                    Swal.fire(`Ministries ${actionType}d`, "", "success");
                    ministryTable.showMinistries();
                } else {
                    toastr.error(`Failed to ${actionType} ministry`);
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
    ministryTable.init();
});
</script>