<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
    require_once('../func.php');
    $x = check();
    if(isset($x) && $x == 1){
        head();
        $conn = connect();
        if (isset($_GET["i"])){
            $isbn = $_GET["i"];
            $v = sval($conn, $_SESSION["id"]);
            if($v == 1){
                $sql = "SELECT availability FROM books WHERE id_isbn = $isbn";
                if($conn->query($sql) == TRUE){
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $availability = $row["availability"] - 1;
                    $today = date("Y-m-d");
                    $id = $_SESSION["id"];
                    $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $isbn;";
                    if($conn->query($sql) == TRUE){
                        $sql = "INSERT INTO loans(id_students, id_isbn, active, id_admin_out,date_out) VALUES($id, $isbn, 0, 0, '$today');";
                        if($conn->query($sql) === TRUE){
                            echo "<div class = 'W'>Loan request registered. You have two days to get the book from the library</div>";
                        }else{
                            echo "<div>Error</div>";
                        }
                    }else{
                        echo "<div>Error</div>";    
                    }
                }else{
                    echo "<div>Error</div>";
                }
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>