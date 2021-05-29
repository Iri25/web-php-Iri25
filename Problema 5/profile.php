<?php

function displayImages(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection error");
    }
    $sql = "USE php";
    if ($conn->query($sql) === TRUE) {
     //   echo 'Connected to php';
    } 
    else {
        die("Cannot execute query");
    }

    echo '<p>Username: '  . $_GET['profile'] . '</p>';
    $stmt =$conn->prepare( "SELECT img, imgName FROM images where username = ?");
    $stmt->bind_param('s', $_GET['profile']);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
          
            echo '<img height="480" width="480" src=images/' .  $row['imgName']   .  ' alt="imaginee"><br>';
        }
    }
}

function isRegistered(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Connection error");
    } 
    else {
        // echo 'Connected to server';
    }
    $sql = "USE php";
    if ($conn->query($sql) === TRUE) {
        //   echo 'connected to php';
    } 
    else {
        die("Cannot execute query");
    }

    $stmt = $conn->prepare( "SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param('ss', $_COOKIE['user'],$_COOKIE['password']);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows>0)
        return TRUE;
    else
        return FALSE;
}

if(isset($_COOKIE['user']) and isset($_COOKIE['password']) and isset($_GET['profile'])){
    //echo '<p>Profile loading.... </p>';
    if(isRegistered()){
    //echo '<p>Registered....... </p>';
        displayImages();
    }
    else
        echo 'Error!';
}
?>


