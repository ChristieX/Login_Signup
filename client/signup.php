<?php
include('../common/db_connect.php');
session_start();

$username = $fname = $lname = $email = $password = $ConfirmPassword = "";
$usernameErr = $nameErr = $emailErr = $passwordErr = $ConfirmPasswordErr = "";

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isValid = true;

    // Username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $isValid = false;
    } else {
        $username = test_input($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $username)) {
            $usernameErr = "Only letters, numbers and white space allowed";
            $isValid = false;
        }
    }

    // Password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $isValid = false;
    } else {
        $rawPassword = test_input($_POST["password"]);
        if (strlen($rawPassword) < 6) {
            $passwordErr = "Password must be at least 6 characters long";
            $isValid = false;
        }
    }

    // Confirm Password
    if (empty($_POST["ConfirmPassword"])) {
        $ConfirmPasswordErr = "Enter password again";
        $isValid = false;
    } else {
        $rawConfirmPassword = test_input($_POST["ConfirmPassword"]);
        if ($rawPassword !== $rawConfirmPassword) {
            $ConfirmPasswordErr = "Passwords do not match";
            $isValid = false;
        } else {
            $password = password_hash($rawPassword, PASSWORD_DEFAULT);
        }
    }

    // First and Last Name
    if (empty($_POST["fname"]) || empty($_POST["lname"])) {
        $nameErr = "Name is required";
        $isValid = false;
    } else {
        $fname = test_input($_POST["fname"]);
        $lname = test_input($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname) || !preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $nameErr = "Only letters and white space allowed";
            $isValid = false;
        }
    }

    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $isValid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $isValid = false;
        }
    }

    // Check and insert
    if ($isValid) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $existing_user = $stmt->get_result();

        if ($existing_user && $existing_user->num_rows > 0) {
            header("Location: /Login_Signup/index.php?signup=1&error=username_exists");
            exit();
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, fname, lname) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $password, $fname, $lname);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                header("Location: ./dashboard.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <?php include('../common/common_files.php'); ?>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4 w-100" style="max-width: 500px;">
        <h2 class="text-center mb-4">Sign Up</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <div class="col">
                        <input type="text" name="fname" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>" required>
                    </div>
                    <div class="col">
                        <input type="text" name="lname" class="form-control <?php echo $nameErr ? 'is-invalid' : ''; ?>" placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>" required>
                    </div>
                </div>
                <?php if ($nameErr): ?>
                    <div class="invalid-feedback d-block"><?php echo $nameErr; ?></div>
                <?php endif; ?>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control <?php echo $usernameErr ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>" required>
                <?php if ($usernameErr): ?>
                    <div class="invalid-feedback d-block"><?php echo $usernameErr; ?></div>
                <?php elseif (isset($_GET['error']) && $_GET['error'] === 'username_exists'): ?>
                    <div class="alert alert-danger mt-2">Username already exists. Please choose a different one.</div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : ''; ?>" required>
                <?php if ($passwordErr): ?>
                    <div class="invalid-feedback d-block"><?php echo $passwordErr; ?></div>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" name="ConfirmPassword" class="form-control <?php echo $ConfirmPasswordErr ? 'is-invalid' : ''; ?>" required>
                <?php if ($ConfirmPasswordErr): ?>
                    <div class="invalid-feedback d-block"><?php echo $ConfirmPasswordErr; ?></div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control <?php echo $emailErr ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="form-text">We'll never share your email with anyone else.</div>
                <?php if ($emailErr): ?>
                    <div class="invalid-feedback d-block"><?php echo $emailErr; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" name="signup" class="btn btn-primary w-100">Sign Up</button>

            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-link">Already have an account? Log in</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
