<?php
require_once __DIR__ . '/../class/database.php';
require_once __DIR__ . '/../class/member.php';
require_once __DIR__ . '/../class/ministries.php';
require_once __DIR__ . '/../class/events.php';
require_once __DIR__ . '/../class/logs.php';

$pdo = new Database();
$conn = $pdo->getConnection();

$member = new Member($conn);
$ministry = new Ministries($conn);
$event = new Events($conn);
$logs = new Logs($conn);

$event_ctr = count($event->show());
$ministry_ctr = count($ministry->show());
$member_ctr = count($member_r = $member->show(_origdate: true));
$logs_r = $logs->show(_limit: 20);

$newly_baptist_ctr = 0;
foreach ($member_r as $member) {
    if ($member['baptism_date'] ?? 0) {
        $baptismDate = new DateTime($member['baptism_date']);
        $currentDate = new DateTime();

        if (
            $baptismDate->format('m') === $currentDate->format('m') &&
            $baptismDate->format('Y') === $currentDate->format('Y')
        ) {
            $newly_baptist_ctr += 1;
        }
    }
}
?>

<div class="row">
    <div class="card col m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-place-of-worship fa-2x text-primary"></i>
            6 missions
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-people-group fa-2x text-primary"></i>
            <?= $ministry_ctr ?> Ministry
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-user-group fa-2x text-primary"></i>
            <?= $member_ctr ?> total members
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-user fa-2x text-primary"></i>
            2 new members
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-water-ladder fa-2x text-primary"></i>
            <?= $newly_baptist_ctr ?> newly baptist
        </div>
    </div>
    <div class="card col     m-2">
        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fa-solid fa-calendar-check fa-2x text-primary"></i>
            <?= $event_ctr ?> events
        </div>
    </div>
</div>
<div class="row">
    <div class="card col-8 m-2">
        <div class="card-title d-flex justify-content-between mx-2 mb-0">
            <span>Events</span>
            <i class="bi bi-three-dots"></i>
        </div>

        <div class="card-body d-flex justify-content-center align-items-center" style="height: 400px;">
            <canvas id="barChart" class="w-100 h-100"></canvas>
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-title d-flex justify-content-between align-items-center mx-3 mt-3">
            <span class="fw-semibold">Recent Activity</span>
            <i class="bi bi-three-dots"></i>
        </div>

        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            <ul class="timeline list-unstyled position-relative ps-4">
                <?php foreach ($logs_r as $row): ?>
                    <li class="mb-4 position-relative">
                        <span class="dot bg-<?php
                                            $colors = ['primary', 'success', 'danger', 'info', 'warning', 'secondary'];
                                            echo $colors[$row['id'] % count($colors)];
                                            ?>"></span>
                        <small class="text-muted">
                            <?= $logs->time_elapsed_string($row['date']) ?>
                        </small>
                        <p class="mb-0"><?= htmlspecialchars($row['text']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col m-2">
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="doughnutChart" width="400" height="400"></canvas>
        </div>
    </div>
    <div class="card col m-2">
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="lineChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>

<script>
    var labels = ['Red', 'Blue', 'Yellow'];
    var data = [12, 19, 3];

    var chartjs = {
        init: () => {
            chartjs.bar();
            chartjs.doughnut();
            chartjs.line();
        },
        bar: () => {
            const barChart = document.getElementById('barChart');
            if (!barChart) return; // Check if element exists
            new Chart(barChart.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sample Data',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                        ],
                        borderWidth: 2,
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        },
        doughnut: () => {
            const doughnutChart = document.getElementById('doughnutChart');
            if (!doughnutChart) return; // Check if element exists
            new Chart(doughnutChart.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sample Data',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                        ],
                        borderWidth: 2,
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        },
        line: () => {
            const lineChart = document.getElementById('lineChart');
            if (!lineChart) return; // Check if element exists
            new Chart(lineChart.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        label: 'Sample Data',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: true,
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                },
            });
        },
    };

    document.addEventListener('DOMContentLoaded', function() {
        chartjs.init();
    });
</script>