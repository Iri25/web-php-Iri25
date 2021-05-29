<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function initStudents($id_teacher){
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);
    if($conn->connect_error){
        die("Connection error");
    }

    $sql = "USE php";
    if($conn->query($sql) === TRUE){

    }
    else{
        die("Cannot execute query");
    }

    $stmt = $conn->prepare('SELECT firstname, lastname FROM students');
    $stmt->execute();
    $result = $stmt->get_result();
    $select = "<select name='studentName'>";

    $text = "<label>Students: </label>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row['firstname'] . ' ' . $row['lastname'];
            $select .= "<option> $name </option>";
        }
        $select .= "</select> ";

    }
    $text .= $select;
    echo $text;
    echo '<br>';
    $stmt = $conn->prepare('SELECT id_subject FROM teacher_subjects WHERE id_teacher = ?');
    $stmt->bind_param('i', $id_teacher);
    $stmt->execute();
    $result1 = $stmt->get_result();
    echo "Subjects:";
    echo "<select name='subject'>";
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $id_subject = $row1['id_subject'];
            $stmt1 = $conn->prepare('SELECT subject FROM subjects WHERE id_subject = ?');
            $stmt1->bind_param('i', $id_subject);
            $stmt1->execute();
            $result2 = $stmt1->get_result();
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    $sub = $row2['subject'];
                    echo "<option>" . $sub  . "</option>";
                }
            }
        }
    }

    echo "</select>";
    $conn->close();

}



function getTeacherInfo($u, $p){
    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);
    if($conn->connect_error){
        die("Connection error");
    }

    $sql = "USE php";
    if($conn->query($sql) === TRUE){

    }
    else{
        die("Cannot execute query");
    }

    $stmt = $conn->prepare('SELECT id_teacher, full_name FROM teachers WHERE username = ? AND password = ?');
    $stmt->bind_param('ss', $u, $p);
    $stmt->execute();
    $result = $stmt->get_result();
    $array = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_teacher = $row['id_teacher'];
            $full_name = $row['full_name'];
            array_push($array, $id_teacher);
            array_push($array, $full_name);
        }
    }
   return $array;
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Page</title>

    <style>
       
        body {
            background-color: lightgreen;
        }
        form {
        margin-top: 80px;
        text-align: center;
        }
        h2{
        text-align: center;
        }
        
    </style>
</head>
<body>
<?php


    echo "<h2> Student Management System</h2>";

    $username = test_input($_GET["username"]);
    $password = test_input($_GET["password"]);

    $info = getTeacherInfo($username, $password);
    $id_teacher = $info['0'];
    $name = $info['1'];
    
?>

    <form method='post' action='saveGrade.php' target='_blank'>
    <label>Teacher Name: </label>
    <input type='text' name='teacher' value='<?php echo $name ?>' readonly>
    <br>
    <label>Teacher Id: </label>
    <input type='text' name='id_teacher' value='<?php echo $id_teacher?>' readonly>
    <br>
    <?php
    initStudents($id_teacher);
    ?>
     <br>
    <br>
    <label>Grade: </label>
    <input type='text' name='grade' >
    <input type='submit' name='submitB'>  </form>
    </body></html>
<?php

}

?>