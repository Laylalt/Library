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
        $tabla = "<div class = 'F'>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='id'>Current Password:</label>";
        $tabla .= "<input type=password name='cp'><br>";
        $tabla .= "<label for='np'>New password:</label>";
        $tabla .= "<input type=password name='np'><br>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='p'>Submit</button>";
        $tabla .= "</form>";
        if(isset($_REQUEST["p"])){
            $cp = $_POST["cp"];
            $np = $_POST["np"];
            if($cp != $_SESSION["password"]){
                $tabla = "<div class = 'F'>";
                $tabla .= "<form action='' method='post' id='form1'><br> ";
                $tabla .= "<label for='id'>Current Password:</label>";
                $tabla .= "<input type=password name='cp'><br>";
                $tabla .= "<div class = 'HW'>Wrong password, please try again</div>";
                $tabla .= "<label for='np'>New password:</label>";
                $tabla .= "<input type=password name='np'><br>";
                $tabla .= "<button type='submit' form='form1' value='submit' name='p'>Submit</button>";
                $tabla .= "</form>";
                echo $tabla;
            }else{
                if($cp == $np){
                    $tabla = "<div class = 'F'>";
                    $tabla .= "<form action='' method='post' id='form1'><br> ";
                    $tabla .= "<label for='id'>Current Password:</label>";
                    $tabla .= "<input type=password name='cp'><br>";
                    $tabla .= "<label for='np'>New password:</label>";
                    $tabla .= "<input type=password name='np'><br>";
                    $tabla .= "<div class = 'HW'>You cannot use the same password, please choose a new one!</div>";
                    $tabla .= "<button type='submit' form='form1' value='submit' name='p'>Submit</button>";
                    $tabla .= "</form>";
                    echo $tabla;
                }else{
                    $id = $_SESSION["id"];
                    $sql = "UPDATE students SET password_user = '$np' WHERE id_students = $id;";
                    if($conn->query($sql) === TRUE){
                        $_SESSION["password"] = $np;
                        $tabla =  "<div class = 'W'>New password Added<br>";
                        $tabla .= "</div>";
                        echo $tabla;
                    }else{
                        echo "Error updating password";
                    }
                }
                
            }
        }else{
            echo $tabla;
        }
        
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>