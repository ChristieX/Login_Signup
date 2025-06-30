<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="d-flex flex-column justify-content-between">
        <div>
            <a class="btn btn-primary" href="?login=true" role="button" >login</a>
            <a class="btn btn-primary" href="?signup=true" role="button">Signup</a>
        </div>
        <?php if (isset($_GET['login']) && $_GET['login'] == 'true'): ?>
            <div class="login-form">
                <?php include('./client/login.php'); ?>
            </div>
        <?php else: ?>
            <div class="signup-form">
                <?php include('./client/signup.php'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>