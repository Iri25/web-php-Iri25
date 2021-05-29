<?php

echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Comments</title>

    <style>
    
        h1{
        text-align: center;
        font-family: Brush Script MT;
        }

        h2 {
        text-align: center;
        font-family: Lucida Handwriting;
        }      
        
        #p1 {
        text-align: center;
        font-family: Lucida Handwriting;
        }
        
        form {
        text-align: center;
        }        
      
        body {
            background-color: #FFFF99;
        }
    </style>
</head>";

echo "<body>";
echo "<h1>Admin Page</h1>";
echo "<h2>Manage Post Comments</h2><br><br>";
echo '<form method="post">';

$servername = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection error");
}
$sql = "USE php";
if ($conn->query($sql) === TRUE) {
 //   echo 'connected to php';
} 
else {
    die("Cannot execute query");
}

    $stmt =$conn->prepare( 'SELECT id_comment, content FROM comments where approved = 0');
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo '<form method="post">';
        echo '<select name="idComment">';
        echo "<option> <p id='p1'>Choose one comment: </p></option>";
        while($row = $result->fetch_assoc()){
            echo '<option>' . $row['id_comment'] . ',' . $row['content']  . ' </option>';
        }
        echo '</select>';
        echo '<br><br>';
        echo '<input type="submit" value="Approve comment" name="approveBtn">';
        echo '</form>';
    }
    else{
        echo "<p id='p1'>There are no comments to be approved!</p>";
    }

    if(isset($_POST['approveBtn'])){
        $array = explode(',', $_POST['idComment']);
        $stmt = $conn->prepare('UPDATE Comments SET approved = 1 WHERE id_comment = ?');
        $stmt->bind_param('i', $array[0]);
        $stmt->execute();
        header('refresh: 2');
    }

echo "</body>";
echo "</html>";
?>