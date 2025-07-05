<?php
$nav = new Navigation();
?>

<!-- ✅ Top Navbar (mobile only) -->
<header class="navbar navbar-light bg-light px-3 shadow-sm d-md-none">
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="fa-solid fa-bars"></i>
    </button>
    <span class="ms-3 fw-bold">CBT Dashboard</span>
</header>

<!-- ✅ Static Sidebar for Desktop -->
<nav class="col-md-2 p-3 d-none d-md-flex flex-column shadow-sm min-vh-100 bg-white">
    <a href="#" class="load-content p-0 m-0 text-decoration-none" data-page="<?= $nav->getLink('main') ?>">
        <img src="assets/img/CBT Logo 1.svg" alt="CBT Logo" width="100" class="mx-auto d-block">
    </a>

    <ul class="nav flex-column flex-grow-1 mt-3">
        <!-- Dashboard -->
        <li class="nav-item bg-info-subtle rounded">
            <a href="#" class="nav-link load-content hover-bg-light" data-page="<?= $nav->getLink('main') ?>">
                <i class="fa-solid fa-chart-simple me-2"></i>Dashboard
            </a>
        </li>

        <!-- Management -->
        <li class="nav-item">
            <div class="nav-link d-flex justify-content-between align-items-center dropdown-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#managementMenu"
                role="button"
                aria-expanded="true"
                aria-controls="managementMenu">
                <span><i class="fa-solid fa-table me-2"></i>Management</span>
            </div>
            <div class="collapse show" id="managementMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('attendance') ?>">Attendance</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('missions') ?>">Missions</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('members') ?>">Members</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('events') ?>">Events</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('ministries') ?>">Ministries</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('commitments') ?>">Commitments</a></li>
                </ul>
            </div>
        </li>

        <!-- Tools -->
        <li class="nav-item">
            <div class="nav-link d-flex justify-content-between align-items-center dropdown-toggle"
                data-bs-toggle="collapse"
                data-bs-target="#toolsMenu"
                role="button"
                aria-expanded="true"
                aria-controls="toolsMenu">
                <span><i class="fa-solid fa-screwdriver-wrench me-2"></i>Tools</span>
            </div>
            <div class="collapse show" id="toolsMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('pp_generator') ?>">Create PowerPoint</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('schedule') ?>">Schedule</a></li>
                    <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('documentation') ?>">Documentation</a></li>
                </ul>
            </div>
        </li>

        <!-- Logout -->
        <li class="nav-item rounded">
            <a href="#" id="logoutBtn" class="nav-link hover-bg-light">
                <i class="fa-solid fa-door-open me-2"></i>Logout
            </a>
        </li>
    </ul>
</nav>

<!-- ✅ Offcanvas Sidebar for Mobile -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="p-3 flex-column">
            <a href="#" class="load-content p-0 m-0 text-decoration-none" data-page="<?= $nav->getLink('main') ?>">
                <img src="assets/img/CBT Logo 1.svg" alt="CBT Logo" width="100" class="mx-auto d-block">
            </a>

            <ul class="nav flex-column flex-grow-1 mt-3">
                <!-- Dashboard -->
                <li class="nav-item bg-info-subtle rounded">
                    <a href="#" class="nav-link load-content hover-bg-light" data-page="<?= $nav->getLink('main') ?>">
                        <i class="fa-solid fa-chart-simple me-2"></i>Dashboard
                    </a>
                </li>

                <!-- Management -->
                <li class="nav-item">
                    <div class="nav-link d-flex justify-content-between align-items-center dropdown-toggle"
                        data-bs-toggle="collapse"
                        data-bs-target="#managementMenuMobile"
                        role="button"
                        aria-expanded="true"
                        aria-controls="managementMenuMobile">
                        <span><i class="fa-solid fa-table me-2"></i>Management</span>
                    </div>
                    <div class="collapse show" id="managementMenuMobile">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('attendance') ?>">Attendance</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('missions') ?>">Missions</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('members') ?>">Members</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('events') ?>">Events</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('ministries') ?>">Ministries</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('commitments') ?>">Commitments</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Tools -->
                <li class="nav-item">
                    <div class="nav-link d-flex justify-content-between align-items-center dropdown-toggle"
                        data-bs-toggle="collapse"
                        data-bs-target="#toolsMenuMobile"
                        role="button"
                        aria-expanded="true"
                        aria-controls="toolsMenuMobile">
                        <span><i class="fa-solid fa-screwdriver-wrench me-2"></i>Tools</span>
                    </div>
                    <div class="collapse show" id="toolsMenuMobile">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('pp_generator') ?>">Create PowerPoint</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('schedule') ?>">Schedule</a></li>
                            <li class="nav-item"><a href="#" class="nav-link load-content" data-page="<?= $nav->getLink('documentation') ?>">Documentation</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Logout -->
                <li class="nav-item rounded">
                    <a href="#" id="logoutBtn" class="nav-link hover-bg-light">
                        <i class="fa-solid fa-door-open me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>