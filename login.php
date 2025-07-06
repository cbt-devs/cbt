<?php require_once("./modules/header.php"); ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <a href="index.php" class="p-0 m-0 text-decoration-none">
                            <img src="assets/img/CBT Logo 1.svg" alt="CBT Logo" width="100" class="mx-auto d-block mb-3">
                        </a>

                        <h3 class="text-center mb-4">Login</h3>

                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="pass" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>

                        <div class="text-center">
                            <small><a href="#">Forgot password?</a></small>
                        </div>

                    </div>
                </div>
                <p class="text-center mt-3 text-muted small">© <?= date("Y") ?> CBT Admin</p>
            </div>
        </div>
    </div>

    <?php require_once("./modules/footer.php"); ?>
</body>

</html>