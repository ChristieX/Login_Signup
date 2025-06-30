<form method="post" action="./server/requests.php">
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" name="username" class="form-control" id="username" required>
  </div>
  <div class="mb-3">
    <label for="Password" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="Password" required>
  </div>
  <?php if (isset($_GET['error']) && $_GET['error'] === 'no_users'): ?>
                        <div class="alert alert-danger text-center align-self-center" role="alert">
                            No such user exists. sign in instead.
                        </div>
                    <?php endif; ?>
  <button type="submit" name="login" class="btn btn-primary">Log in</button>
</form>