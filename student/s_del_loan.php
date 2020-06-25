<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once('../func.php');
        $x = check();
        if(isset($x) && $x == 1){
            $id = $_SESSION["id"];
            if (isset($_GET["i"])){
                $conn = connect();
                $id_loan = $_GET["i"];
                $sql = "SELECT id_isbn FROM loans where id_loan = $id_loan;";
                if($conn->query($sql) == TRUE){
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $isbn = $row["id_isbn"];
                }
                $sql = "SELECT availability FROM books WHERE id_isbn = $isbn";
                if($conn->query($sql) == TRUE){
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $availability = $row["availability"] + 1;
                }
                $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $isbn;";
                if($conn->query($sql) == TRUE){
                    $sql = "DELETE FROM loans WHERE id_loan = $id_loan;";
                    if ($conn->query($sql) === TRUE){
                        ob_clean();
                        header("Location: http://localhost/student/library_student.php?acc=6");
                        $conn->close();
                        exit();
                    }else{
                        echo "ERROR";
                    }
                }
            }else{
                echo "what are you doing?";   
            }
        }else{
            echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
        }
        
    ?>
</body>
</html>