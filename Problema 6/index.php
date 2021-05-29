<?php

echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Cats</title>

    <style>
        h1{
        text-align: center;
        font-family: Brush Script MT;
       
        }

        div {
        display: inline;
        }
        
        #p1 {
        text-align: center;
        font-family: Lucida Handwriting;
        }
        
        #p2 {
        text-align: left;
        font-family: Lucida Handwriting;
        }
        
        #img1 {
            width: 100%;
            margin: auto;
        }
        
        body {
            background-color: #FFCC99;
        }
    </style>
</head>";

echo "<body>";
echo "<h1> My Adorable Cats</h1>";
echo "<p id='p1'> I love cats. There are so many species of small, playful kittens that cheer up your day. What species do you prefer? What color do you like?</p>";
echo "<img src=pisici.jpg alt = 'Pisicute' id='img1'> ";

echo "<div>";
echo "<br>";
echo "<p id='p2'>Have your say! </p>";
echo "<br>";
echo "<form method='post'> <p id='p2'>Username:</p><input type='text' name='username'>";
echo "<br><p id='p2'>Comment:<p><textarea name='commField'></textarea> <br> <input type='submit' name='submitBtn'>";
echo "</form>";
echo "</div>";

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

$stmt = $conn->prepare( 'SELECT username, content FROM comments where approved = 1');
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    while( $row = $result->fetch_assoc() ){
        echo '<p>' . $row['username'] . ':' . $row['content'] . '</p>';
    }
}
if(isset($_POST['submitBtn']) && isset($_POST['commField']) && isset($_POST['username'])){
    $stmt = $conn->prepare('INSERT INTO comments(username, content, approved) VALUES (?,?,?) ');
    $ap = 0;
    $stmt->bind_param('ssi', $_POST['username'], $_POST['commField'], $ap);
    $stmt->execute();
}
echo "</body> </html>";
?>
