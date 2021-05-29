<?php

echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Student Page</title>

    <style>
        h2{
        text-align: center;
        font-family: Times New Roman ;
        }
        body {
            background-color: lightgreen;
        }
        table, td{
        border: 1px solid black;
        }
        
    </style>
</head>
<body>";

echo "<h2> Welcome to your Academic Account!</h2>";

$firstname = $lastname = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = test_input($_POST["firstname"]);
    $lastname = test_input($_POST["lastname"]);
    getGrades($firstname, $lastname);

}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
echo "</body></html>";


function getGrades($firstname, $lastname)
{
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

    echo "Student: " . $firstname . " " . $lastname . "<br>";
    $table = "<table> <tr> <th>Subject</th> <th>Teacher</th> <th>Grade</th> </tr> ";

    $stmt = $conn->prepare('SELECT id_student FROM students WHERE firstname = ? AND lastname = ?');
    $stmt ->bind_param('ss', $firstname, $lastname);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_student = $row['id_student'];
        }
        $stmt = $conn->prepare('SELECT id_student, id_teacher, grade FROM grades WHERE id_student = ?');
        $stmt ->bind_param('i',  $id_student);
        $stmt->execute();
        $result1 = $stmt->get_result();
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $id_student = $row1['id_student'];
                $id_teacher = $row1['id_teacher'];
                $grade = $row1['grade'];
                
                $stmt = $conn->prepare('SELECT subject FROM subjects WHERE id_student = ? AND id_teacher = ?');
                $stmt ->bind_param('ss',  $id_student, $id_teacher);
                $stmt->execute();
                $result2 = $stmt->get_result();
                $subject = '';
                if($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){
                        $subject .= $row2['subject'];
                    }
                }
                $stmt = $conn->prepare('SELECT full_name FROM teachers WHERE id_teacher = ?');
                $stmt ->bind_param('i',  $id_teacher);
                $stmt->execute();
                $result2 = $stmt->get_result();
                $teacher = '';
                if($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){
                        $teacher .= $row2['full_name'];
                    }
                }

                $table .= "<tr><td>$subject </td><td>$teacher</td> <td>$grade</td> </tr>";
            }
        }
    } 
    else {
        echo "There is no student with these credentials.";
    }

    $table .= "</table>";
    echo $table;
}

?>