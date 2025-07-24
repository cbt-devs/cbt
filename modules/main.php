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

<style>
    .timeline .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: absolute;
        left: -1.25rem;
        top: 0.4rem;
    }
</style>

<div class="container-fluid px-3">
    <!-- Stats Cards -->
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-place-of-worship fa-2x text-primary mb-2"></i><br>
                    <small>6 missions</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-people-group fa-2x text-primary mb-2"></i><br>
                    <small><?= $ministry_ctr ?> Ministry</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-user-group fa-2x text-primary mb-2"></i><br>
                    <small><?= $member_ctr ?> total members</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-user fa-2x text-primary mb-2"></i><br>
                    <small>2 new members</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-water-ladder fa-2x text-primary mb-2"></i><br>
                    <small><?= $newly_baptist_ctr ?> newly baptist</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fa-solid fa-calendar-check fa-2x text-primary mb-2"></i><br>
                    <small><?= $event_ctr ?> events</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Logs -->
    <div class="row mt-4 g-3">
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-title d-flex justify-content-between mx-3 mb-0">
                    <span>Events</span>
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="barChart" class="w-100 h-100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-title d-flex justify-content-between align-items-center mx-3">
                    <span class="fw-semibold">Recent Activity</span>
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <ul class="timeline list-unstyled position-relative ps-4">
                        <?php foreach ($logs_r as $row): ?>
                            <li class="mb-4 position-relative">
                                <span class="dot bg-<?= ['primary', 'success', 'danger', 'info', 'warning', 'secondary'][$row['id'] % 6] ?>"></span>
                                <small class="text-muted"><?= $logs->time_elapsed_string($row['date']) ?></small>
                                <p class="mb-0"><?= htmlspecialchars($row['text']) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-4 g-3">
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body" style="height: 300px;">
                    <canvas id="doughnutChart" class="w-100 h-100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-body" style="height: 300px;">
                    <canvas id="lineChart" class="w-100 h-100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var labels = ['Red', 'Blue', 'Yellow'];
    var data = [12, 19, 3];

    const chartjs = {
        init: () => {
            chartjs.bar();
            chartjs.doughnut();
            chartjs.line();
        },
        bar: () => {
            const ctx = document.getElementById('barChart');
            if (!ctx) return;
            new Chart(ctx, {
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
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        },
        doughnut: () => {
            const ctx = document.getElementById('doughnutChart');
            if (!ctx) return;
            new Chart(ctx, {
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
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        },
        line: () => {
            const ctx = document.getElementById('lineChart');
            if (!ctx) return;
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar'],
                    datasets: [{
                        label: 'Sample Data',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }
    };

    document.addEventListener('DOMContentLoaded', chartjs.init);
</script>