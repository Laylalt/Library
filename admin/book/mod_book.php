<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>  
    <?php
        require_once('../../func.php');
        $x = check();
        if(isset($x) && $x == 0){ 
            $conn = connect();
            head();
            $print = "<p><a href='http://localhost/admin/library_admin.php?acc=3'><--Go back</a></p>";
            echo $print;
            //------------------------------------------------------------------------------------------------------------
            if(isset($_GET["i"])){
                $isbn = $_GET["i"];
                $sql = "SELECT * FROM books WHERE id_isbn = $isbn";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tabla = "<div>";
                    $tabla .= "<form action='mod_book_submit.php' method='post' id='form1'><br> ";
                    $tabla .= "<label for='isbn'><p>ISBN:</p></label>";
                    $tabla .= "<input type=text name='isbn' id='isbn' value=" . $row["id_isbn"] . " readonly><br>";
                    $tabla .= "<label for='title'>Title:</label>";
                    $tabla .= "<input type=text name='title' value =" . $row["title"] . "><br>";
                    $tabla .= "<label for='dewey_code'>Dewey code:</label>";
                    $tabla .= "<input type=text name='dewey_code' value =" . $row["dewey_code"] . "><br>";
                    $tabla .= "<label for='copy_number'>Copy number:</label>";
                    $tabla .= "<input type=text name='copy_number' value =" . $row["copy_number"] . "><br>";
                    $tabla .= "<label for='availability'>Available copies:</label>";
                    $tabla .= "<input type=text name='availability' value =" . $row["availability"] . "><br>";
                    $tabla .= "</form>";
                    $tabla .= "<button type='submit' form='form1' value='submit'>Submit</button>";
                    $tabla .= "</div>";
                    echo $tabla;
                    $conn->close();   
                } else{
                echo "0 results";
                }
            }
        }else{
            ?>
            <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
    <?php
        }
    ?>
</body>
</html>