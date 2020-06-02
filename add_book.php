<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add book</title>
</head>
<body>
    <?php
        //conecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
        //End of conecting to database
        $tabla = "<form action='/add_book_submit.php' method='post' id='form1'><br> ";
        $tabla .= "<label for='title'>Title:</label>";
        $tabla .= "<input type=text name='title'><br>";
        $tabla .= "<label for='dewey_code'>Dewey code:</label>";
        $tabla .= "<input type=text name='dewey_code'><br>";
        $tabla .= "<label for='copy_number'>Copy number:</label>";
        $tabla .= "<input type=text name='copy_number'><br>";
        $tabla .= "<label for='availability'>Available copies:</label>";
        $tabla .= "<input type=text name='availability'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit'>Submit</button>";
        echo $tabla;
    ?>
    
</body>
</html>