<?php
    require_once __DIR__ . '/../class/database.php';
    require_once __DIR__ . '/../class/member.php';
    require_once __DIR__ . '/../class/ministries.php';
    require_once __DIR__ . '/../class/events.php';

    $pdo = new Database();
    $conn = $pdo->getConnection();

    $member = new Member($conn);
    $ministry = new Ministries($conn);
    $event = new Events($conn);

    $event_ctr = count( $event->show() );
    $member_ctr = count( $member_r = $member->show( 1 ) );

    $newly_baptist_ctr = 0;
    foreach( $member_r as $member ) {
        if( $member[ 'baptism_date' ] ?? 0 ) {
            $baptismDate = new DateTime($member['baptism_date']);
            $currentDate = new DateTime();

            if( $baptismDate->format('m') === $currentDate->format('m') &&
                $baptismDate->format('Y') === $currentDate->format('Y') ) {
                    $newly_baptist_ctr += 1;
            }
        }
    }
?>

<div class="row">
    <div class="card col-2 m-2">
        <div class="card-body d-flex flex-column flex-wrap align-content-center">
            <i class="fa-solid fa-place-of-worship fa-2x text-primary"></i>
            6 missions
        </div>
    </div>
    <div class="card col-2 m-2">
        <div class="card-body d-flex flex-column flex-wrap align-content-center">
            <i class="fa-solid fa-user-group fa-2x text-primary"></i>
            <?= $member_ctr ?> total members
        </div>
    </div>
    <div class="card col-2 m-2">
        <div class="card-body d-flex flex-column flex-wrap align-content-center">
            <i class="fa-solid fa-user fa-2x text-primary"></i>
            2 new members
        </div>
    </div>
    <div class="card col-2 m-2">
        <div class="card-body d-flex flex-column flex-wrap align-content-center">
            <i class="fa-solid fa-water-ladder fa-2x text-primary"></i>
            <?=  $newly_baptist_ctr ?> newly baptist
        </div>
    </div>
    <div class="card col-2 m-2">
        <div class="card-body d-flex flex-column flex-wrap align-content-center">
            <i class="fa-solid fa-calendar-check fa-2x text-primary"></i>
            <?= $event_ctr ?> events
        </div>
    </div>
</div>
<div class="row">
    <div class="card col m-2">
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="barChart" width="400" height="400"></canvas>
        </div>
    </div>

    <div class="card col m-2">
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="doughnutChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>
<div class="row">
    <div class="card col m-2">
        <div class="card-body d-flex justify-content-center align-items-center">
            <canvas id="lineChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>
</div>

<script>
var labels = ['Red', 'Blue', 'Yellow'];
var data = [12, 19, 3];

var chartjs = {
    init: () => {
        console.log("wat wat");
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
                responsive: false
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
                responsive: false
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
                responsive: false
            },
        });
    },
};

document.addEventListener('DOMContentLoaded', function() {
    chartjs.init();
});
</script>