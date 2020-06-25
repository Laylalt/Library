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
    function book_update($conn, $id_isbn){
        $sql = "SELECT availability FROM books WHERE id_isbn = $id_isbn;";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $update = $row["availability"] + 1;
        $sql = "UPDATE books SET availability = $update WHERE id_isbn = $id_isbn;";
        if($conn->query($sql) === TRUE){
            return 1;
        }
        else{
            return 0;
        }
    }
    function checkfee($conn, $id_loan, $id_admin_in){
        $sql = "SELECT * FROM loans WHERE id_loan = $id_loan;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $today = date("Y-m-d");
            $due_date = $row["date_sin"];
            $id_students = $row["id_students"];
            if($today > $due_date){
                $today = strtotime($today);
                $due_date = strtotime($due_date);
                $days = intval(($today - $due_date)/60/60/24);//gets int from float
                if($days == 0){ //checking number of days to get out ammount
                    $ammount = 20;
                }else{
                    $ammount = 20 * $days;
                }
                $sql = "INSERT INTO fees(id_loan, id_admin, ammount, id_students) VALUES($id_loan, $id_admin_in, $ammount, $id_students);";
                if($conn->query($sql) === TRUE){
                    return 1;
                }
            }else{
               return 0; 
            }
        }
    }
?>
<?php
    $x = check();
    if(isset($x) && $x == 0){
        head();
        echo "<p><a href='http://localhost/admin/library_admin.php?acc=2'><--Go back</a></p>";
        if (isset($_GET["acc"])){
            $conn = connect();
            $id_loan = $_GET["acc"];
            $id_admin_in = $_SESSION["id"];
            $date_rin = date("Y-m-d");
            $sql = "UPDATE loans SET id_admin_in = $id_admin_in, date_rin = '$date_rin', active = 0 WHERE id_loan = $id_loan;";
            if($conn->query($sql) === TRUE){
                $sql = "SELECT  id_admin_in, date_out, date_sin, date_rin, id_isbn FROM loans WHERE id_loan = $id_loan AND active = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $tabla ="<div class = 'W'>New return added</div> ";
                    $tabla .= "<table>";
                    $tabla .= "<tr><th>Borrow date</th><th>Date to return</th><th>True returned date</th>";
                    $tabla .= "<th>Id admn</td></th>";//header row
                    $tabla .= "<tr>";
                    $tabla .= "<td>" . $row["date_out"] . "</td>";
                    $tabla .= "<td>" . $row["date_sin"] . "</td>";
                    $tabla .= "<td>" . $row["date_rin"] . "</td>";
                    $tabla .= "<td>" . $row["id_admin_in"] . "</td>";
                    $tabla .= "</tr>";
                    $tabla .= "</table>";
                    $u = book_update($conn, $row["id_isbn"]);//change book availability 
                    $f = checkfee($conn, $id_loan, $id_admin_in);
                    if($f > 0){
                        $sql = "SELECT id_fee, ammount FROM fees ORDER BY id_fee DESC LIMIT 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0){
                            $row = $result->fetch_assoc();
                            $print = "<div class = 'W'>This book was not returned in time, the fee is: $" . $row["ammount"] ."; <a href='http://localhost/admin/loan-fee/pay_fee.php?acc=" . $row["id_fee"] . "'>Pay now</a><br>";
                            $print .= "The fee can be paid on a later date but it will remain active and the user will not be able to borrow books until they pay the fee</div>";
                            echo $print;
                        }
                    }
                    echo $tabla;
                }else{
                    echo "Error updating return, please try again";    
                }
            }else{
                echo "Error updating return, please try again";
            }
            $conn->close();   
        }else{
            echo "What are you doing here?";
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
    }
?>
</body>
</html>