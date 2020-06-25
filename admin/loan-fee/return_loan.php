<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Return book</title>
</head>
<body>
<?php
    require_once('../../func.php');  
    $x = check();
    if(isset($x) && $x == 0){
        head();
        echo "<p><a href='http://localhost/admin/library_admin.php?acc=2'><--Go back</a></p>";
        $conn = connect();
        $tabla = "<div><p>Search</p>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='id_students'>Id student:</label>";
        $tabla .= "<input type=text name='id_students'><br>";
        $tabla .= "<label for='id_isbn'>ISBN:</label>";
        $tabla .= "<input type=text name='id_isbn'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        $tabla .= "</div>";
        echo $tabla;
        //get requested values
        if(isset($_REQUEST["sbmt"])){
            $id_students = $_POST["id_students"];
            $id_isbn = $_POST["id_isbn"];
            //search 
            $sql = "SELECT id_loan FROM loans WHERE id_students = $id_students AND id_isbn = $id_isbn AND active = 1 ORDER BY date_out DESC LIMIT 1;";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id_loan = $row["id_loan"];
                if($id_students != $_SESSION["id"]){
                    $send = $id_loan;
                    ob_clean();
                    header("Location: http://localhost/admin/loan-fee/return_loan_submit.php?acc=". $send);
                    $conn->close();
                    exit();
                }else{
                    echo "<div class = 'W'>A return cannot be registred by the same person who borrowed it, please try again with a diferent staff</div>";    
                }
            }else{
                echo "<div class = 'W'>Were sorry but we did not find any results with those values, please try again :(</div>";
            }
            $conn->close();
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
    }
    
?>
</body>
</html>