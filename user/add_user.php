<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
</head>
<body>
<p><a href="http://localhost/library.php?acc=1"><--Go back</a></p>
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
        $tabla = "<div>";
        $tabla .= "<form action='add_user_submit.php' method='post' id='form1'><br> ";
        $tabla .= "<label for='first_name'>First name:</label>";
        $tabla .= "<input type=text name='first_name'><br>";
        $tabla .= "<label for='last_name'>Last name:</label>";
        $tabla .= "<input type=text name='last_name'><br>";
        $tabla .= "<label for='phone_number'>Phone nuber:</label>";
        $tabla .= "<input type=text name='phone_number'><br>";
        $tabla .= "<label for='email'>e-mail:</label>";
        $tabla .= "<input type=text name='email'><br>";
        $tabla .= "<label for='password_user'>Password:</label>";
        $tabla .= "<input type=text name='password_user'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit'>Submit</button>";
        $tabla .= "</div>";
        echo $tabla;
    ?>
    <p>this is the add user webpage</p>
</body>
</html>