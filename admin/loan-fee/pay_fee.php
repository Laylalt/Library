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
            if (isset($_GET["acc"])){
                $conn = connect();
                $id_fee = $_GET["acc"];
                $id_admin = $_SESSION["id"];
                $today = date("Y-m-d");
                $sql = "UPDATE fees SET id_admin = $id_admin, date_paid = '$today', active = 0 WHERE id_fee = $id_fee;";
                if($conn->query($sql) === TRUE){
                    echo "<div class = 'W'>Payment registred</div>";
                    $sql = "SELECT ammount, id_students, date_paid FROM fees WHERE active = 0 AND id_fee = $id_fee;";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $tablaf = "<table>";
                        $tablaf .= "<tr><th>id Student</th><th>Ammount</th><th>date_paid</th>";
                        $tablaf .= "</tr>";//header row
                        $row = $result->fetch_assoc();
                        $tablaf .= "<tr>";
                        $tablaf .= "<td>" . $row["id_students"] . "</td>";
                        $tablaf .= "<td>$" . $row["ammount"] . "</td>";
                        $tablaf .= "<td>" . $row["date_paid"] . "</td>";
                        $tablaf .= "</tr>";
                        $tablaf .= "</table>";
                        echo $tablaf;
                    }
                }else{
                    echo "error updating the payment";
                }
            }else{
                echo "error getting the data";
            }
        }else{
            echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
        }
    ?>
</body>
</html>