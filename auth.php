<?php
ob_start();
session_start();
include_once('./inc/connectDb.php');
require_once('./inc/functions.php');
$error = false;
$error_massage = '';
if ($_GET['task'] == 'register') {
    if (count($_SESSION) > 0) {
        if ($_GET['task'] == 'register' && true == $_SESSION['loggedin']) {
            header('location: index.php');
        }
    }
    if (isset($_POST['register_user'])) {
        $r_username = mysqli_real_escape_string($conn, $_POST['r-username']);
        $r_password = mysqli_real_escape_string($conn, $_POST['r-password']);
        $r_confirm_password = mysqli_real_escape_string($conn, $_POST['r-confirm-password']);
        if ($r_password != $r_confirm_password) {
            $error = true;
            $error_massage = "password and confirm password doesn't matched!";
            // header('location:auth.php?task=register');
        } elseif (isExistsInDB($r_username, $conn, 'username', 'auth')) {
            $error = true;
            $error_massage = "username already exist";
        } else {
            $isCreated = createUser($conn, $r_username, $r_password);
            if ($isCreated) {
                header('location:auth.php?task=login');
            } else {
                return;
            }
        }
    }
}

if (isset($_POST['login_student'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $isSuccess = userLogin($conn, $username, $password);
    echo var_dump($isSuccess);
    if ($isSuccess == true) {
        $_SESSION['loggedin'] = true;
        header('location:/crud1/index.php?task=report');
    } else {
        $error = true;
        $error_massage = "username and password doesn't matched!";
        // $_SESSION['loggedin'] = false;
    }
}

if (isset($_POST['logout']) || isset($_GET['logout'])) {
    $_SESSION['loggedin'] = false;
    session_destroy();
    header('location: auth.php?task=login');
}
// echo count($_SESSION);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/08ec2d528c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <?php
        if ($_GET['task'] == 'login' && count($_SESSION) <= 0) {
        ?>
            <div class="w-25 mx-auto">
                <h2 class="mb-5">Login first!</h2>
                <form action="" method="post">
                    <?php
                    if ($error) {
                        echo "<blockquote class='alert alert-danger'>$error_massage</blockquote>";
                    }
                    ?>
                    <div class="mb-3">
                        <label for="username" class="form-label">username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="" required>
                    </div>
                    <div class="mb-3">
                        <label for="roll" class="form-label">password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <p>Don't have any account? <a href="/crud1/auth.php?task=register">register.</a> </p>
                    <button type="submit" class="btn btn-primary" name="login_student">Login</button>
                </form>
            </div>
        <?php } ?>

        <?php
        if (count($_SESSION) > 0) {
            if ($_GET['task'] == 'login' && true == $_SESSION['loggedin']) { ?>
                <div class="w-50 mx-auto">
                    <form action="auth.php?task=login" method="post" class="w-full d-flex justify-content-center flex-column">
                        <h2 class="w-full mx-auto text-center mb-5">You already Loggedin!</h2>
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" class="btn btn-primary w-full mx-auto" name="logout_student">Logout</button>
                    </form>
                </div>
        <?php
            }
        } ?>

        <?php
        if ($_GET['task'] == 'register') {
        ?>
            <div class="w-25 mx-auto mt-5 ">
                <h2 class="mb-5">Register!</h2>
                <form action="" method="post">
                    <?php
                    if ($error) {
                        echo "<blockquote class='alert alert-danger'>$error_massage</blockquote>";
                    }
                    ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">username:</label>
                        <input type="text" class="form-control" id="name" name="r-username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">password</label>
                        <input type="password" class="form-control" id="password" name="r-password" required>
                    </div>
                    <div class="mb-3">
                        <label for="con-password" class="form-label">confirm password</label>
                        <input type="password" class="form-control" id="con-password" name="r-confirm-password" required>
                    </div>
                    <span>Already have an account? <a href="/crud1/auth.php?task=login">login</a> </span>
                    <button type="submit" class="btn btn-primary" name="register_user">Register</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>