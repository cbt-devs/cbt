<h2>Members Management</h2>
<p>Here you can manage all your members.</p>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal"><i
        class="fa-solid fa-plus"></i></button>
<button type="button" class="btn btn-warning"><i class="fa-solid fa-pen"></i></button>
<button type="button" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Baptism Date</th>
        </tr>
    </thead>
</table>

<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- made wider for better layout -->
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Member</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addMemberForm">
                <!-- Moved form tag to wrap whole modal body+footer -->
                <div class="modal-body">
                    <div class="row mb-3">
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
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" selected disabled>Select gender</option>
                                <option value="female">Female</option>
                                <option selected value="male">Male</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate"
                                value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="addressLine" class="form-label">Address Line</label>
                        <input type="text" class="form-control" id="addressLine" name="addressLine" value="q" required>
                    </div>

                    <div class="row mb-3">
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
                            <input type="number" class="form-control" id="postalCode" name="postalCode" value="q"
                                required>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="primary" name="primary" value="1">
                        <label class="form-check-label" for="primary">Primary Address</label>
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
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "ajax/member.php",
        data: {
            action: "show"
        },
        success: function(response) {
            const data = response.data;

            console.log(data);

            if ($.fn.dataTable.isDataTable('#example')) {
                $('#example').DataTable().clear().destroy();
            }

            $('#example').DataTable({
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
                    }
                ]
            });
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + ": " + error);
        }
    });
});


document.getElementById('addMemberForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    formData.append('action', 'add');

    for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    try {
        const response = await fetch('ajax/member.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Close modal, reset form, show success message, etc.
            toastr.success('Member added successfully!')
            form.reset();
        } else {
            toastr.error('Failed to add the member')
            console.error('Failed:', result.message);
        }

        // Remove focus to avoid ARIA warning
        if (document.activeElement instanceof HTMLElement) {
            document.activeElement.blur();
        }

        // Hide the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addMemberModal'));
        modal.hide();

    } catch (error) {
        console.error('Error:', error);
        alert('There was a problem submitting the form.');
    }
});
</script>