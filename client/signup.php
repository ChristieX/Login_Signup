<form method="post" action="./server/requests.php">
    <div class="row g-3">
        <label for="name" class="form-label">Name</label>
        <div class="col mt-0">
            <input type="text" name='fname' class="form-control" placeholder="First name" aria-label="First name" required>
        </div>
        <div class="col mt-0">
            <input type="text" name='lname' class="form-control" placeholder="Last name" aria-label="Last name" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">username</label>
        <input type="text" name='username' class="form-control" id="username" required>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'username_exists'): ?>
                        <div class="alert alert-danger text-center align-self-center" role="alert">
                            Username already exists. Please choose a different one.
                        </div>
                    <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="Password" class="form-label">Password</label>
        <input type="password" name='password' class="form-control" id="Password" required>
    </div>
    <div class="mb-3">
        <label for="Email" class="form-label">Email address</label>
        <input type="email" name='email' class="form-control" id="Email" aria-describedby="emailHelp" required>
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <button type="submit" name='signup' class="btn btn-primary">signup</button>
</form>