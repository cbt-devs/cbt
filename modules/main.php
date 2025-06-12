<?php
    require_once 'class/database.php';
    require_once 'class/member.php';
    require_once 'class/ministries.php';
    require_once 'class/events.php';

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