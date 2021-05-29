<?php


echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Student Page</title>

    <style>
        h2{
        text-align: center;
        font-family: Calibri;
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

$student = $_POST['studentName'];
$subject = $_POST['subject'];
$grade = $_POST['grade'];
$id_teacher = $_POST['id_teacher'];

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
$name = explode(' ', $student);
$firtsN = $name[0];
$lastN = $name[1];

$stmt = $conn->prepare('SELECT id_student FROM students WHERE firstname = ? AND lastname = ?');
$stmt ->bind_param('ss',$firtsN, $lastN);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $id_student = $row['id_student'];
        
        $stmt = $conn->prepare('SELECT id_subject FROM subjects WHERE subject = ? AND id_student = ?');
        $stmt ->bind_param('ss', $subject, $id_student);
        $stmt->execute();
        $result1 = $stmt->get_result();

        if($result1->num_rows > 0){
            while($row1 = $result1->fetch_assoc()){
                $id_subject = $row1['id_subject'];
                $stmt = $conn->prepare('INSERT INTO grades(id_teacher, id_subject, id_student, grade) VALUES (?, ?, ?, ?)');
                $stmt ->bind_param('iiii', $id_teacher, $id_subject, $id_student, $grade);
                $stmt->execute();
            }
        }
    }
}

$conn -> close();
echo "Grade successfully added.";

echo "</body></html>";

?>
