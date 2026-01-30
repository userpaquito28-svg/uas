<?php
include "config/db.php";
?>

<?php if (isset($_SESSION['error_login'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error_login']; ?>
    </div>
<?php unset($_SESSION['error_login']); endif; ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/theme.min.css">
</head>

<body>

    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">

                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">

                    <div class="card-body p-sm-5">

                        <div class="text-center mb-4">
                            <h2 class="fs-20 fw-bolder mb-1">Login</h2>
                            <p class="fs-12 fw-medium text-muted mb-0">Silakan masuk untuk melanjutkan</p>
                        </div>

                        <!-- Form -->
                        <form action="login_process.php" method="POST" class="w-100 mt-4 pt-2">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email / Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan email / username" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary w-100">
                                Login
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->

    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/js/common-init.min.js"></script>

</body>

</html>
