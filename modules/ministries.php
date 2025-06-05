<h2>Ministry Management</h2>
<p>Here you can manage all ministries.</p>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMinistryModal">
    <i class="fa-solid fa-plus"></i> Add Ministry
</button>

<table id="ministryTable" class="table table-striped" style="width:100%"></table>

<!-- Add Ministry Modal -->
<div class="modal fade" id="addMinistryModal" tabindex="-1" aria-labelledby="addMinistryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMinistryModalLabel">Add Ministry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
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

    bindEvents: function() {
        // $('#ministryTable').on('click', '.edit-btn', function() {
        //     const id = $(this).data('id');
        //     // Implement editMinistry(id)
        // });

        // $('#ministryTable').on('click', '.delete-btn', function() {
        //     const id = $(this).data('id');
        //     // Implement deleteMinistry(id)
        // });

        const form = document.getElementById('addMinistryForm');
        if (form) this.addMinistry(form);
    },
};

$(document).ready(function() {
    ministryTable.init();
});
</script>