<?php
require_once("admin/inc/config.php");

$fetchingElections = mysqli_query($db, "SELECT * FROM elections") OR die(mysqli_error($db));
$curr_date = date("Y-m-d");

while ($data = mysqli_fetch_assoc($fetchingElections)) {
    $starting_date = $data['starting_date'];
    $ending_date = $data['ending_date'];
    $election_id = $data['id'];
    $status = $data['status'];

    $date1 = date_create($curr_date);

    if ($status == "Active") {
        $date2 = date_create($ending_date);
        $diff = date_diff($date1, $date2);

        if ((int)$diff->format("%R%a") < 0) {
            // Update status to 'Expired'
            mysqli_query($db, "UPDATE elections SET status='Expired' WHERE id='$election_id'") OR die(mysqli_error($db));
        }

    } else if ($status == "InActive") {
        $date2 = date_create($starting_date);
        $diff = date_diff($date1, $date2);

        if ((int)$diff->format("%R%a") >= 0) {
            // Update status to 'Active'
            mysqli_query($db, "UPDATE elections SET status='Active' WHERE id='$election_id'") OR die(mysqli_error($db));
        }
    }
}
?>

<?php
require_once("admin/inc/config.php");

if (isset($_POST['sign_up_btn'])) {
    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_contact_no = mysqli_real_escape_string($db, $_POST['su_contact_no']);
    $su_password = mysqli_real_escape_string($db, $_POST['su_password']);
    $su_retype_password = mysqli_real_escape_string($db, $_POST['su_retype_password']);
    $user_role = "voter";

    if ($su_password == $su_retype_password) {
        // Check if the username already exists
        $check_query = "SELECT * FROM users WHERE username='$su_username'";
        $result = mysqli_query($db, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>location.assign('index.php?sign-up=1&user_exists=1');</script>";
        } else {
            // Insert query
            $query = "INSERT INTO users (username, contact_no, password, user_role) VALUES ('$su_username', '$su_contact_no', '$su_password', '$user_role')";

            if (mysqli_query($db, $query)) {
                echo "<script>location.assign('index.php?sign-up=1&registered=1');</script>";
            } else {
                die("Database query failed: " . mysqli_error($db));
            }
        }
    } else {
        echo "<script>location.assign('index.php?sign-up=1&invalid=1');</script>";
    }
}
else if (isset($_POST['loginBtn'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Query fetch/select
    $fetchingData = mysqli_query($db, "SELECT * FROM users WHERE username='$username'") or die(mysqli_error($db));
    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);
        if ($username == $data['username'] && $password == $data['password']) {
          session_start();
          $_SESSION['user_role']=$data['user_role'];
          $_SESSION['username']=$data['username'];
          $_SESSION['user_id']=$data['id'];


          if($data['user_role']=="Admin"){
            

               $_SESSION['key']='AdminKey';
               ?>
               <script>location.assign("admin/index.php?homepage=1");</script>
               <?php
          }
          else{
               $_SESSION['key']='VotersKey';

               ?>
               <script>location.assign("voters/index.php");</script>
               <?php
          }
        } else {
            echo "<script>location.assign('index.php?invalid_access=1');</script>";
        }
    } else {
        echo "<script>location.assign('index.php?not_registered=1');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Voting System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="assets/images/logo.jpeg" class="brand_logo" alt="Logo">
                    </div>
                </div>
                <?php if (isset($_GET['sign-up'])) { ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="Username" required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" name="su_contact_no" class="form-control input_pass" placeholder="Contact #" required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_password" class="form-control input_pass" placeholder="Password" required />
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_retype_password" class="form-control input_pass" placeholder="Retype Password" required />
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_btn" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links text-white">
                            Already have an account? <a href="index.php" class="ml-2 text-white">Sign In</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="username" class="form-control input_user" placeholder="Username" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control input_pass" placeholder="Password" required/>
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="loginBtn" class="btn login_btn">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links text-white">
                            Don't have an account? <a href="?sign-up=1" class="ml-2 text-white">Sign Up</a>
                        </div>
                        <div class="d-flex justify-content-center links">
                            <a href="#" class="text-white">Forgot your password?</a>
                        </div>
                    </div>
                <?php } ?>
                <?php
                if (isset($_GET['registered'])) {
                    echo "<span class='bg-white text-success text-center my-3'>Your account has been created successfully</span>";
                } else if (isset($_GET['invalid'])) {
                    echo "<span class='bg-white text-danger text-center my-3'>Password mismatched, please try again</span>";
                } else if (isset($_GET['user_exists'])) {
                    echo "<span class='bg-white text-danger text-center my-3'>User already registered</span>";
                } else if (isset($_GET['not_registered'])) {
                    echo "<span class='bg-white text-warning text-center my-3'>Sorry, you are not registered!</span>";
                } else if (isset($_GET['invalid_access'])) {
                    echo "<span class='bg-white text-danger text-center my-3'>Invalid Username or Password</span>";
                }
                ?>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
