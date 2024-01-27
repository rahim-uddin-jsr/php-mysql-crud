<?php
require_once('./inc/connectDb.php');
require_once('./inc/functions.php');
session_start();
// $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

// Check if there are any query parameters in the referring page
// if (!empty($referrer)) {
//     $referrerParts = parse_url($referrer);

//     if (isset($referrerParts['query'])) {
//         // Query parameters are present in the referring page
//         $queryParameters = [];
//         parse_str($referrerParts['query'], $queryParameters);
//         if (isset($_GET['message']) && $referrer) {
//             $massage = mysqli_real_escape_string($conn, $_GET['message']);
//             echo "<script>alert('$massage');</script>";
//         }
//     } else {
//         // No query parameters in the referring page
//         echo "No query parameters in the referring page.";
//     }
// } else {
//     // No referring page
//     echo "No referring page.";
// }
$error = '';
$info = '';


$task = $_GET['task'] ?? 'report';
if ('seed' == $task) {
    $info  = 'Seeding in completing';
}
if ('delete' == $task) {
    if ('admin' != $role) {
        header('location: /crud1/index.php?task=report');
    }
}
if ('edit' == $task) {
    if ('editor' != $role && 'admin' != $role) {
        header('location: /crud1/index.php?task=report');
    }
}
if ('add' == $task && $role != 'admin') {
    header('location: /crud1/index.php?task=report');
}
if (isset($_POST['save_student'])) {
    $s_name     = mysqli_real_escape_string($conn, $_POST['name']);
    $s_roll     = mysqli_real_escape_string($conn, $_POST['roll']);
    $s_gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $s_id   = mysqli_real_escape_string($conn, $_POST['id']);
    if ($s_id) {
        if (isExistsInDB($s_roll, $conn, "roll", "student")) {
            $error = "Roll is already exist";
        } else {
            $updated = updateStudent($conn, $s_id, $s_name, $s_roll, $s_gender);
            if ($updated == true) {
                header('Location: index.php?message=Updated successfully');
            }
        }
    } elseif (isExistsInDB($s_roll, $conn, "roll", "student")) {
        $error = "Roll is already exist";
    } else {
        $result = saveStudentData($conn, $s_name, $s_roll, $s_gender);
        if ($result) {
            echo '<script>alert("Success: Data submitted successfully!");</script>';
            header('location: index.php?message=Student added successfully!');
        } else {
            echo '<script>alert("Error: Something went wrong. Please try again.");</script>';
        }
    }
}

if ('delete' == $task) {
    $s_id = mysqli_real_escape_string($conn, $_GET['id']);
    $result = deleteStudent($conn, $s_id);
    if ($result) {
        header('location: index.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/08ec2d528c.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h2 class="text-center my-3 text-secondary">CRUD -2</h2>
        <?php include_once('inc/tamplate/nav.php') ?>
        <hr>
        <?php
        if ($info != '') {
            echo "<p>$info</p>";
        }
        ?>
        <?php
        if ('report' == $task) {
            getStudents($conn);
        }
        if ('add' == $task) { ?>
            <div class="w-50 mx-auto mt-5">
                <?php if ($error) {
                    echo "<h6 class='alert alert-danger'>$error</h6>";
                } ?>
                <form action="" method="post">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $error != '' ?  $_POST['name'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="roll" class="form-label">Roll:</label>
                        <input type="text" class="form-control" id="roll" name="roll" value="<?php echo $error != '' ?  $_POST['roll'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select class="form-select" id="gender" name="gender" value="<?php echo $error != '' ?  $_POST['gender'] : ''; ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="save_student">Submit</button>
                </form>
            </div>
        <?php }
        if ('edit' == $task) {

            $studentData = getStudentDetails($_GET['id'], $conn);
            // print_r($studentData['name']);
        ?>
            <div class="w-50 mx-auto mt-5">
                <?php if ($error) {
                    echo "<h6 class='alert alert-danger'>$error</h6>";
                } ?>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $studentData['id'] ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $studentData['name'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="roll" class="form-label">Roll:</label>
                        <input type="text" class="form-control" id="roll" name="roll" value="<?php echo $studentData['roll'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender:</label>
                        <select class="form-select" id="gender" name="gender" value="<?php echo $studentData['gender'] ?>" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="save_student">Update</button>
                </form>
            </div>
        <?php }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="./assets/js/script.js"></script>
</body>

</html>