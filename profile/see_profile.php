<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Profile</title>
</head>
<body>
<?php
    require_once('../func.php');
    $x = check();
    if(isset($x)){
        head(); 
        $id = $_SESSION["id"];
        $first_name = $_SESSION["first_name"];
        $last_name = $_SESSION["last_name"];
        $email = $_SESSION["email"];
        $phone = $_SESSION["phone"];
        $password = $_SESSION["password"];
        $tabla = "<div class ='F'>";
        $tabla .= "<form action='mod_profile.php' method='post' id='form1'><br> ";
        $tabla .= "<label for='id'>ID:</label>";
        $tabla .= "<input type=text name='id' value=" . $id . " readonly><br>";
        $tabla .= "<label for='first_name'>First name:</label>";
        $tabla .= "<input type=text name='first_name' value =" . $first_name . " readonly><br>";
        $tabla .= "<label for='last_name'>Last name:</label>";
        $tabla .= "<input type=text name='last_name' value =" . $last_name . " readonly><br>";
        $tabla .= "<label for='phone'>Phone Number:</label>";
        $tabla .= "<input type=text name='phone' value =" . $phone . " readonly><br>";
        $tabla .= "<label for='email'>E-mail:</label>";
        $tabla .= "<input type=text name='email' value =" . $email . " readonly><br>";
        $tabla .= "</form>";
        $tabla .= "<button id = 'edit_profile' type='submit' form='form1' value='submit'>Edit profile</button>";
        $tabla .= "<form action='change_p.php' method='post' id='form2'><br> ";
        $tabla .= "<label for='password'>Password:</label><br>";
        $tabla .= "<input class= 'S' type=password name='password' value =" . $password . " readonly>";
        $tabla .= "<button class= 'Sp' type='submit' form='form2' value='submit' name='sbmt'>Change</button>";
        $tabla .= "</form>";
        $tabla .= "</div>";
        echo $tabla;
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>