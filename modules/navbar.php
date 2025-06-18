<?php
$nav = new Navigation();
?>

<nav class="col-md-2 p-3 d-flex flex-column shadow-sm">
    <a href="#" class="load-content p-0 m-0 text-decoration-none" data-page="<?= $nav->getLink('main') ?>">
        <img src="assets/img/CBT Logo 1.svg" alt="CBT Logo" width="100" class="mx-auto d-block">
    </a>

    <ul class="nav flex-column flex-grow-1 mt-3">
        <li class="nav-item bg-info-subtle rounded">
            <a href="#" class="nav-link load-content hover-bg-light" data-page="<?= $nav->getLink('main') ?>">
                <i class="fa-solid fa-chart-simple me-2"></i>Dashboard
            </a>
        </li>

        <!-- Management Dropdown (Always Open) -->
        <li class="nav-item">
            <div class="nav-link d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-table me-2"></i>Management</span>
            </div>
            <div>
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('missions') ?>">Missions</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('members') ?>">Members</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('events') ?>">Events</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('ministries') ?>">Ministries</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('commitments') ?>">Commitments</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Tools Dropdown (Always Open) -->
        <li class="nav-item">
            <div class="nav-link d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-screwdriver-wrench me-2"></i>Tools</span>
            </div>
            <div>
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('pp_generator') ?>">Create PowerPoint</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('schedule') ?>">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link load-content"
                            data-page="<?= $nav->getLink('documentation') ?>">Documentation</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>