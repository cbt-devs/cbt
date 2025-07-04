<?php require_once("./modules/header.php"); ?>

<body class="d-flex align-items-center justify-content-center bg-light min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Login</h2>
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
                <p class="text-center mt-3 text-muted small">© <?= date("Y") ?> YourApp</p>
            </div>
        </div>
    </div>

    <?php require_once("./modules/footer.php"); ?>
</body>

</html>