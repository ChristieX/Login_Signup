<?php
include('../common/db_connect.php');
session_start();
$loginUsername = $loginPassword = "";
$loginErr = $loginUsernameErr = $loginPasswordErr = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" ) {
    $isValid = true;

    if (empty($_POST["username"])) {
        $loginUsernameErr = "Username is required";
        $isValid = false;
    } else {
        $loginUsername = trim($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $loginPasswordErr = "Password is required";
        $isValid = false;
    } else {
        $loginPassword = $_POST["password"];
    }

    if ($isValid) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $loginUsername);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            if (password_verify($loginPassword, $hashedPassword)) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $loginUsername;
                header("Location: ./dashboard.php");
                exit();
            } else {
                $loginErr = "Invalid password.";
            }
        } else {
            $loginErr = "No such user found. Please sign up.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <?php include('../common/common_files.php'); ?>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">Login</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control <?php echo !empty($loginUsernameErr) ? 'is-invalid' : ''; ?>" id="username"
                       value="<?php echo htmlspecialchars($loginUsername); ?>">
                <?php if (!empty($loginUsernameErr)): ?>
                    <div class="invalid-feedback"><?php echo $loginUsernameErr; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?php echo !empty($loginPasswordErr) ? 'is-invalid' : ''; ?>" id="Password">
                <?php if (!empty($loginPasswordErr)): ?>
                    <div class="invalid-feedback"><?php echo $loginPasswordErr; ?></div>
                <?php endif; ?>
            </div>

            <?php if (!empty($loginErr)): ?>
                <div class="alert alert-danger"><?php echo $loginErr; ?></div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100">Log in</button>
            <div class="text-center mt-3">
                <a href="signup.php" class="btn btn-link">Don't have an account? Sign up</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
