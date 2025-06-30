<?php 
    include('../common/db_connect.php');
    if(isset($_POST['signup'])){
       $username = $_POST['username'];
       $password = $_POST['password'];
       $email= $_POST['email'];
       $fname= $_POST['fname'];
       $lname= $_POST['lname'];
       
       $check_query = "SELECT * FROM users WHERE username = '$username'";
        $existing_user = $conn->query($check_query);
        if ($existing_user && $existing_user->num_rows > 0) {
            header("Location: /Login_Signup/index.php?signup=1&error=username_exists");
            exit();
        } else{
       $sql = "Insert into `users`
      (`username`,`email`,`password`,`fname`,`lname`)
      values ('$username','$email','$password','$fname','$lname')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
    };
}if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $query = "SELECT username, password FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if($result && $result->num_rows>0){ ?>
    <div class="alert alert-success alert-dismissible fade show">
        <strong>Welcome</strong> <?php echo $username; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php }else{
        header("Location: /Login_Signup/index.php?login=true&error=no_users");
            exit();
    }
}

?>