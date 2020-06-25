<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Document</title>
</head>
<body>
<?php
    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        head(); 
        if (isset($_POST["id"])){
            $conn = connect();
            $id = $_POST["id"];
            $sql = "SELECT loans.id_students, loans.id_loan, loans.id_isbn, loans.date_out, loans.date_sin, students.first_name, students.last_name, students.phone_number from loans join students ON loans.id_students = students.id_students AND loans.active = 1  AND loans.id_students = $id ORDER BY loans.date_sin DESC;";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row		
                $tabla = "<table>";
                $tabla .= "<tr><th>id</th><th>Name</th><th>phone</th><th>ISBN</th><th>Date borrowed</th>";
                $tabla .= "<th>Date to return</th></tr>";//header row
                while($row = $result->fetch_assoc()) {
                    $tabla .= "<tr>";
                    $tabla .= "<td>" . $row["id_students"] . "</td>";
                    $tabla .= "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                    $tabla .= "<td>" . $row["phone_number"] . "</td>";
                    $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                    $tabla .= "<td>" . $row["date_out"] . "</td>";
                    $tabla .= "<td>" . $row["date_sin"] . "</td>";
                    $tabla .= "<td><a href='http://localhost/admin/loan-fee/return_loan_submit.php?acc=" . $row["id_loan"]  . "'>Return</a></td>";
                    $tabla .= "</tr>";
                }
                $tabla .= "</table>";
                echo $tabla;
            } else {
                echo "<div class = 'W'>There are no active loans with this ID at the moment<div> ";
            }
            $conn->close();
        }else{
            echo "What are you doing here?";
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>