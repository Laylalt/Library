<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php //conecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
    ?>
    <?php
        if(isset($_GET["i"])){
            $isbn = $_GET["i"];
            $sql = "SELECT * FROM books WHERE id_isbn = $isbn";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tabla = "<form action='/mod_book_submit.php' method='post' id='form1'><br> ";
                $tabla .= "<label for='isbn'><p>Modifiyng book ISBN-" . $row["id_isbn"] . " </p></label>";
                $tabla .= "<input type=text name='isbn' id='isbn' value=" . $row["id_isbn"] . " ><br>";
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
                echo $tabla;
                $conn->close();   
        } else{
            echo "0 results";
        }
    }
    ?>
</body>
</html>