<?php 
require_once __DIR__ . '/../init.php';
$members_r = $member->show();
$commitments_type = $commitments->type_show();
?>

<style>
.nice-select {
    width: 100% !important;
    min-width: 200px;
    /* optional: adjust based on your layout */
    max-width: 100%;
    white-space: nowrap;
}
</style>

<h2>Commitments Management</h2>
<p>Here you can manage all the church commitments.</p>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="fa-solid fa-plus"></i> Add Commitment
</button>

<div class="row">
    <div class="col-md-10">
        <table id="commitmentTable" class="table table-striped" style="width:100%"></table>
    </div>
    <div class="col-md-2">
        <table id="typeTable" class="table table-striped" style="width:100%"></table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="addModalLabel">Add Commitment</h5>
            </div>

            <form id="addForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="commitment" class="form-label">Type</label>
                            <select class="form-control nice-select2" id="commitment" name="commitment[]">
                                <option value="">Select type</option>
                                <?php
                                if (!empty($commitments_type)) {
                                    foreach ($commitments_type as $type) {
                                        echo '<option value="' . htmlspecialchars($type['id']) . '">' . htmlspecialchars($type['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No commitment found</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="member" class="form-label">Member</label>
                            <select class="form-control nice-select2" id="member" name="member">
                                <option value="">Select Member</option>
                                <?php
                                    if (!empty($members_r)) {
                                        foreach ($members_r as $member) {
                                            echo '<option value="' . htmlspecialchars($member['id']) . '">' . htmlspecialchars($member['name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No member found</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="eventDate" class="form-label">Start Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventTime" class="form-label">Start Time <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="eventTime" name="eventTime" required>
                                <?php echo generateTimeOptions(); ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="eventEndDate" class="form-label">End Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventEndDate" name="eventEndDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventEndTime" class="form-label">End Time <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="eventEndTime" name="eventEndTime" required>
                                <?php echo generateTimeOptions(); ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="amount" name="amount" required>
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
var commitmentTable = {
    init: function() {
        this.show();
        this.showType();
        this.bindEvents();
    },

    show: function() {
        JsLoadingOverlay.show({
            overlayBackgroundColor: '#141414',
            overlayOpacity: 0.6,
            spinnerIcon: 'square-loader',
            spinnerColor: '#0D6EFD',
            spinnerSize: '2x',
            overlayIDName: 'overlay',
            spinnerIDName: 'spinner',
            offsetX: 0,
            offsetY: 0,
            containerID: null,
            lockScroll: false,
            overlayZIndex: 9998,
            spinnerZIndex: 9999,
        });

        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: "show",
                type: "commitments",
            },
            dataType: 'json',
            success: function(response) {
                const data = response.data;

                if ($.fn.dataTable.isDataTable('#commitmentTable')) {
                    $('#commitmentTable').DataTable().clear().destroy();
                }

                $('#commitmentTable').DataTable({
                    data: data,
                    columns: [{
                            data: 'member',
                            title: 'Member'
                        },
                        {
                            data: 'type',
                            title: 'Type'
                        },
                        {
                            data: 'start',
                            title: 'Start',
                        },
                        {
                            data: 'end',
                            title: 'End',
                        },
                        {
                            data: 'amount',
                            title: 'Amount',
                            render: function(data) {
                                return `₱${parseFloat(data).toFixed(2)}`;
                            }
                        },
                        {
                            data: null,
                            title: 'Actions',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>`;
                            }
                        }
                    ],
                    initComplete: function() {
                        JsLoadingOverlay.hide();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error (show commitments):", status, error);
            }
        });
    },

    showType: function() {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: "show",
                type: "commitments",
                table_name: "commitments_type"
            },
            dataType: 'json',
            success: function(response) {
                const data = response.data;

                if ($.fn.dataTable.isDataTable('#typeTable')) {
                    $('#typeTable').DataTable().clear().destroy();
                }

                $('#typeTable').DataTable({
                    data: data,
                    searching: false, // Hide search box
                    info: false, // Hide "Showing X of Y entries"
                    lengthChange: false, // Hide "Show N entries" dropdown
                    searching: false,
                    columns: [{
                        data: 'name',
                        title: 'Type Name'
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error("Error loading type data:", error);
            }
        });
    },

    add: function(formElement) {
        formElement.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(formElement);
            formData.append('action', 'add');
            formData.append('type', 'commitments');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                console.log(result)

                if (result.status === 'success') {
                    Swal.fire("Commitment added", "", "success");
                    formElement.reset();
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'addModal'));
                    if (modal) modal.hide();
                } else {
                    toastr.error('Failed to add event');
                    console.error('Add event error:', result.message);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('There was a problem submitting the form.');
            }
        });
    },

    bindEvents: function() {
        const addForm = document.getElementById('addForm');
        if (addForm) this.add(addForm);
    },
};


$(document).ready(function() {
    commitmentTable.init();

    NiceSelect.bind(document.getElementById("commitment"), {
        searchable: true
    });

    NiceSelect.bind(document.getElementById("member"), {
        searchable: true
    });
});
</script>