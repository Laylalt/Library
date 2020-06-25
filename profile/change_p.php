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
        $conn = connect();
        $tabla = "<div>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='id'>Current Password:</label>";
        $tabla .= "<input type=password name='cp'><br>";
        $tabla .= "<label for='np'>New password:</label>";
        $tabla .= "<input type=password name='np'><br>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='p'>Submit</button>";
        $tabla .= "</form>";
        echo $tabla;
        if(isset($_REQUEST["p"])){
            $cp = $_POST["cp"];
            $np = $_POST["np"];
            if($cp != $_SESSION["password"]){
                echo "<div>Wrong password, please try again</div>";
            }else{
                if($cp == $np){
                    echo "<div>You cannot use the same password!</div>";
                }else{
                    $id = $_SESSION["id"];
                    $sql = "UPDATE students SET password_user = '$np' WHERE id_students = $id;";
                    if($conn->query($sql) === TRUE){
                        $_SESSION["password"] = $np;
                        echo "<div class = 'W'>New password Added</div>";
                        echo "<button type='button'><a href='http://localhost/profile/see_profile.php'>Okey</a></p></button>";
                    }else{
                        echo "Error updating password";
                    }
                }
                
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>