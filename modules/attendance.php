<?php
require_once __DIR__ . '/../init.php';
$members_r = $member->show();
$commitments_type = $commitments->type_show();

$today = new DateTime();
$formatted_today = $today->format('l d, Y');
?>

<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Attendance</h2>
    </div>
    <div>
        <button class="btn border" id="prevDate">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <span id="dateDisplay" style="display: inline-block; width: 180px; text-align: center; white-space: nowrap; font-weight: bold;">
            <?= $formatted_today ?>
        </span>
        <button class="btn border" id="nextDate">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i> Add Commitment
    </button>
</div>

<script>
    const dateDisplay = document.getElementById('dateDisplay');
    let currentDate = new Date('<?= $today->format('Y-m-d') ?>'); // from PHP

    function formatDate(date) {
        const options = {
            weekday: 'long',
            day: 'numeric',
            year: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    }

    document.getElementById('prevDate').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 1);
        dateDisplay.textContent = formatDate(currentDate);
    });

    document.getElementById('nextDate').addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 1);
        dateDisplay.textContent = formatDate(currentDate);
    });
</script>