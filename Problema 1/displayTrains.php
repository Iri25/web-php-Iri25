<?php


echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Trains</title>

    <style>
        table,tr,td{
            border: 1px solid red;
        }


    </style>
</head>
<body>";

$start = $stop = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start = test_input($_POST["start"]);
    $stop = test_input($_POST["end"]);
    }

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

getTrains($start, $stop);

echo "</body>
</html>";

function getTrains($from, $to){

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);
    if($conn->connect_error){
        die("Connection error");
    }

    $sql = "USE php";
    if($conn->query($sql) === TRUE){

    }else{
        die("Cannot execute query");
    }

    if(isset($_POST['direct'])) {
        $stmt = $conn->prepare('SELECT * FROM trains WHERE localitate_plecare = ? AND localitate_sosire = ?');
        $stmt ->bind_param('ss', $from, $to);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "Direct trains from $from to $to : <br>";
            $table = "<table style='border: 1px solid blue'> <tr><th>Train Number</th> <th>Train Type</th> <th> Departure Location</th> 
                <th>Arriving Location</th> <th>Departure Hour</th> <th>Arriving Hour</th> </tr>";

            while ($row = $result->fetch_assoc()) {
                $table .= "<tr> <td>" . $row["numar_tren"] . "</td>" . "<td>" . $row["tip_tren"] . " </td>"
                    . "<td>" . $row["localitate_plecare"] . " </td>" . "<td>" . $row["localitate_sosire"] . " </td>"
                    . "<td>" . $row["ora_plecare"] . " </td>" . "<td>" . $row["ora_sosire"] . " </td></tr>";
            }
            $table .= "</table>";
            echo $table;
        } else {
            echo "There is no direct train for this line.";
        }
    }

    echo "<br>";

    if(isset($_POST['inter'])) {

        $stmt = $conn->prepare
        ('SELECT T1.numar_tren, T1.localitate_plecare, T1.localitate_sosire, T1.ora_plecare, T1.ora_sosire, T2.localitate_sosire as departure_location, T2.ora_plecare as departure_hour, T2.ora_sosire as arriving_hour 
        FROM trains T1 
        INNER JOIN trains T2 ON T1.localitate_sosire = t2.localitate_plecare 
        WHERE t1.localitate_plecare = ? AND t2.localitate_sosire = ?');
        $stmt ->bind_param('ss',$from,$to);
        $stmt->execute();

        $result1 = $stmt->get_result();
        if( $result1->num_rows > 0 ){
        echo "1-change trains from $from to $to : <br>";
        $i = 1;
        while( $row1 = $result1->fetch_assoc()){
            echo 'Line: ' . $i . '<br>';
            $i +=1;
            echo 'Train1 No: ' . $row1['numar_tren']  .'<br>';
            echo 'From: ' . $row1['localitate_plecare']  .'<br>';
            echo 'To: ' . $row1['localitate_sosire']  .'<br>';
            echo 'Departure Hour1: ' . $row1['ora_plecare']  .'<br>';
            echo 'Arriving Hour1: ' . $row1['ora_sosire']  .'<br>';
            echo 'Departure Location: ' . $row1['departure_location']  .'<br>';
            echo 'Departure Hour2: ' . $row1['departure_hour']  .'<br>';
            echo 'Arriving Hour2: ' . $row1['arriving_hour']  .'<br>';

            }
          }
    else{
        echo "There are no 1-change trains for this line.";
    }
    }
    $conn->close();

}

?>