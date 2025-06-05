<?php 
require_once __DIR__ . '/../init.php';
$ministryData = $ministry->show();
?>

<h2>Events Management</h2>
<p>Here you can manage all your events.</p>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
    <i class="fa-solid fa-plus"></i> Add Event
</button>

<table id="eventTable" class="table table-striped" style="width:100%"></table>

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addEventForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="eventName" class="form-label">Event Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eventName" name="eventName" required>
                        </div>

                        <div class="col-md-6">
                            <label for="ministry" class="form-label">Ministry</label>
                            <select class="form-control" id="ministry" name="ministry">
                                <option value="" selected>-- Select Ministry --</option>
                                <?php
                                if (!empty($ministryData)) {
                                    foreach ($ministryData as $ministry) {
                                        echo '<option value="' . htmlspecialchars($ministry['id']) . '">' . htmlspecialchars($ministry['name']) . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No ministries found</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div class="col-md-6">
                            <label for="eventDate" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventTime" class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="eventTime" name="eventTime" step="1800"
                                required>
                        </div>

                        <div class="col-md-8">
                            <label for="place" class="form-label">Place of Event</label>
                            <input type="text" class="form-control" id="place" name="place">
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