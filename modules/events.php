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

<style>
#calendar {
    max-width: 100%;
    margin: 40px auto;
}
</style>

<div id="calendar"></div>

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
                            <select class="form-control" id="ministry" name="ministry[]" multiple>
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
                            <label for="eventDate" class="form-label">Start Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventTime" class="form-label">Start Time <span
                                    class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="eventTime" name="eventTime" step="1800"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventEndDate" class="form-label">End Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eventEndDate" name="eventEndDate" required>
                        </div>

                        <div class="col-md-6">
                            <label for="eventEndTime" class="form-label">End Time <span
                                    class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="eventEndTime" name="eventEndTime" step="1800"
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

<script>
var eventsCalendar = {
    calendar: null,

    init: function() {
        this.initCalendar();
        this.bindEvents();

        // Load events from server and render on calendar
        this.show();
    },

    initCalendar: function() {
        var calendarEl = document.getElementById('calendar');
        this.calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            events: [],

            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;

                // Example: Show details using SweetAlert
                Swal.fire({
                    title: event.title,
                    html: `
                    <p><strong>Location:</strong> ${props.location}</p>
                    <p><strong>Ministries:</strong> ${props.ministries}</p>
                    <p><strong>Start:</strong> ${eventsCalendar.formatDateTime(event.start.toLocaleString())}</p>
                    <p><strong>End:</strong> ${event.end ? eventsCalendar.formatDateTime(event.end.toLocaleString()) : 'N/A'}</p>
                `,
                    icon: 'info'
                });

                // Optionally prevent default browser behavior
                info.jsEvent.preventDefault();
            }
        });

        this.calendar.render();
    },

    show: function() {
        var self = this;
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: "show",
                type: "events"
            },
            dataType: "json",
            success: function(response) {
                if (response.status === "success" && Array.isArray(response.data)) {
                    console.log(response.data);
                    const fcEvents = response.data.map(ev => ({
                        id: ev.id,
                        title: ev.event_name,
                        start: ev.start_date, // full datetime string
                        end: ev.end_date, // full datetime string
                        extendedProps: {
                            location: ev.event_location,
                            ministries: ev.ministries
                        }
                    }));

                    self.calendar.removeAllEvents();
                    self.calendar.addEventSource(fcEvents);
                } else {
                    console.error("Failed to load events:", response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error loading events:", error);
            }
        });
    },


    add: function(formElement) {
        formElement.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(formElement);
            formData.append('action', 'add');
            formData.append('type', 'events');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                console.log(result)

                if (result.status === 'success') {
                    Swal.fire("Event added", "", "success");
                    formElement.reset();
                    eventsCalendar.show(); // reload calendar events
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'addEventModal'));
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

    update: function(formElement) {
        formElement.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(formElement);
            formData.append('action', 'update');
            formData.append('type', 'events');

            try {
                const response = await fetch('controller/main.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire("Event updated", "", "success");
                    events.show();
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'editEventModal'));
                    if (modal) modal.hide();
                } else {
                    toastr.error('Failed to update event');
                    console.error('Update event error:', result.message);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert('There was a problem updating the event.');
            }
        });
    },

    delete: function(id, name) {
        Swal.fire({
            title: `Do you want to delete event "${name}"?`,
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                events.action('delete', id);
            }
        });
    },

    bindEvents: function() {
        // bind add form
        const addForm = document.getElementById('addEventForm');
        if (addForm) this.add(addForm);

        // bind update form
        const editForm = document.getElementById('editEventForm');
        if (editForm) this.update(editForm);

        // You can also add calendar event click handling here if needed
    },

    action: function(actionType, id) {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: actionType,
                type: "events",
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire(`Event ${actionType}d`, "", "success");
                    events.show();
                } else {
                    toastr.error(`Failed to ${actionType} event`);
                    console.error(`${actionType} failed:`, response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(`AJAX Error (${actionType}):`, status, error);
            }
        });
    },

    formatDateTime: function(dateTimeStr) {
        const date = new Date(dateTimeStr);
        const options = {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        };
        // Remove the comma between date and time
        return date.toLocaleString('en-US', options).replace(',', '');
    },
};

$(document).ready(function() {
    eventsCalendar.init();
});
</script>