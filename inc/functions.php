<?php
$role = getRole();

function getStudents($conn)
{
    global $role;
    $studentQuery = "SELECT * FROM student";
    $report = mysqli_query($conn, $studentQuery);
    if ($report->num_rows > 0) {
        echo '<table class="table mx-auto">';
        echo '<thead><tr><th scope="col">ID</th><th scope="col">Name</th><th scope="col">Roll</th><th scope="col">Gander</th>';
?>
<?php
        if ('editor' == $role || 'admin' == $role) {
            echo '<th scope="col">Action</th>';
        }
        if ('admin' == $role) {
            echo '<th scope="col">Update</th>';
        }
        echo '</tr></thead><tbody>';
        $index = 1;
        while ($row = $report->fetch_assoc()) {
            echo '<tr>';
            echo '<th>' . $index . '</th>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['roll'] . '</td>';
            echo '<td>' . $row['gander'] . '</td>';
            if ('admin' == $role) {
                echo "<td><a class='delete btn btn-danger rounded' href='index.php?task=delete&id={$row['id']}'><i
                class='fa-solid fa-trash'></i></a></td>";
            }
            if ('editor' == $role || 'admin' == $role) {
                echo "<td><a class='btn btn-secondary rounded' href='index.php?task=edit&id={$row['id']}'><i
                class='fa-regular fa-pen-to-square'></i></a></td>";
            }
            echo '</tr>';
            $index++;
        }
        echo '</tbody>
</table>';
    }
}

function saveStudentData($conn, $name, $roll, $gender)
{
    $query = "INSERT INTO student(name,roll,gander) VALUES('$name','$roll','$gender')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function isExistsInDB($desired_roll, $conn, $filed, $table)
{

    $query = "SELECT * FROM $table WHERE $filed = '$desired_roll'";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    }
    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        // Roll exists in the database
        return true;
    } else {
        // Roll does not exist in the database
        return false;
    }
}

function getStudentDetails($id, $conn)
{
    $query = "SELECT * FROM student WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {
        // Fetch the student details as an associative array
        $studentDetails = mysqli_fetch_assoc($result);
        // echo var_dump($studentDetails);
        return $studentDetails;
    } else {
        // Return null if the student with the given roll doesn't exist
        return null;
    }
}

function updateStudent($conn, $id, $name, $roll, $gender)
{
    $query = "UPDATE student SET name = '$name', roll = '$roll', gander='$gender' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    } else {
        return true;
    }
}
function deleteStudent($conn, $id)
{
    $query = "DELETE FROM student WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error in SQL query: " . mysqli_error($conn));
    } else {
        return true;
    }
}

function createUser($conn, $name, $password, $role = 'student')
{
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO auth(username,password,role) VALUES('$name','$hash_pass','$role')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function userLogin($conn, $username, $password)
{
    $query = "SELECT * FROM auth WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            print_r($row);
            $hashedPassword = $row['password'];
            echo $hashedPassword . "</br>";
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['role'] = $row['role'];
                return true;
            } else {
                return false;
            }
        }
    } else {
        echo "user not found";
        return 'user not found';
    }
}
function getRole()
{
    if (count($_SESSION) > 0) {
        if ($_SESSION['role'] == 'admin') {
            return 'admin';
        }
        if ($_SESSION['role'] == 'editor') {
            return 'editor';
        } else {
            return 'student';
        }
    }
}
