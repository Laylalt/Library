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
    if(isset($x)){
        head(); 
        $conn = connect();
        $id = $_SESSION["id"];
        $first_name = $_SESSION["first_name"];
        $last_name = $_SESSION["last_name"];
        $email = $_SESSION["email"];
        $phone = $_SESSION["phone"];
        $tabla = "<div>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='first_name'>First name:</label>";
        $tabla .= "<input type=text name='first_name' value =" . $first_name . "><br>";
        $tabla .= "<label for='last_name'>Last name:</label>";
        $tabla .= "<input type=text name='last_name' value =" . $last_name . "><br>";
        $tabla .= "<label for='phone'>Phone Number:</label>";
        $tabla .= "<input type=text name='phone' value =" . $phone ."><br>";
        $tabla .= "<label for='email'>E-mail:</label>";
        $tabla .= "<input type=text name='email' value =" . $email . "><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='c_p'>Apply</button>";
        $tabla .= "</div>";
        echo $tabla;
        if(isset($_REQUEST["c_p"])){
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone_number = '$phone'   WHERE id_students = $id;";
            if($conn->query($sql) === TRUE){
                $_SESSION["first_name"] =  $first_name;
                $_SESSION["last_name"] =  $last_name;
                $_SESSION["email"] =  $email;  
                $_SESSION["phone"] = $phone;           
                ob_clean();
                header("Location: http://localhost/profile/see_profile.php");
                $conn->close();
            }else{
                echo "Error updating information";
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>