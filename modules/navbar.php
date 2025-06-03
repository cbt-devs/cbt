<?php
$nav = new Navigation();
?>

<nav class="col-md-2 p-3 d-flex flex-column shadow-sm">
    <h3 class="text-center">CBT</h5>

        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item bg-info-subtle rounded">
                <a href="#" class="nav-link load-content hover-bg-light" data-page="<?= $nav->getLink('main') ?>">
                    <i class="fa-solid fa-chart-simple me-2"></i>Dashboard
                </a>
            </li>

            <!-- Management Dropdown -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    data-bs-target="#managementDropdown" aria-expanded="false" aria-controls="managementDropdown">
                    <span><i class="fa-solid fa-table me-2"></i>Management</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
                <div class="collapse" id="managementDropdown">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link load-content"
                                data-page="<?= $nav->getLink('members') ?>">Members</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link load-content"
                                data-page="<?= $nav->getLink('events') ?>">Events</a>
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

            <!-- Tools Dropdown -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    data-bs-target="#toolsDropdown" aria-expanded="false" aria-controls="toolsDropdown">
                    <span><i class="fa-solid fa-screwdriver-wrench me-2"></i>Tools</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
                <div class="collapse" id="toolsDropdown">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link load-content"
                                data-page="<?= $nav->getLink('create_powerpoint') ?>">Create PowerPoint</a>
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