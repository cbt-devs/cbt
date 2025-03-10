<?php require_once( "./modules/header.php" ); ?>
<body class="d-flex flex-column min-vh-100">
    <div class="container-fluid container-fluid flex-grow-1">
        <div class="row h-100">
            <?php require_once( "./class/navigation.php" ); ?>
            <?php require_once( "./modules/navbar.php" ); ?>

            <main class="col bg-light p-4 contents">
                <?php require_once( "./modules/main.php" ); ?>
            </main>
        </div>

        <?php require_once( "./modules/footer.php" ); ?>
    </div>
    
</body>
</html>