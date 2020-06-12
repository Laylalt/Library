<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <p>CUCEA Library</p>
    <?php
        //conecting to database
        require_once('func.php');
        $conn = connect();
        //End of conecting to database
        $tabla = "<div>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='email'>E-mail:</label>";
        $tabla .= "<input type=text name='email'><br>";
        $tabla .= "<label for='password'>Password:</label>";
        $tabla .= "<input type=password name='password'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        $tabla .= "</div>";
        echo $tabla;
    
        //get submited values
        if(isset($_REQUEST["sbmt"])){
            $email = $_POST["email"];
            $password = $_POST["password"];    
            //check database info
            $sql = "SELECT * FROM students WHERE email = '$email' ";
            $result = $conn->query($sql);
            if($result->num_rows > 0){//email found in database
                $row = $result->fetch_assoc();
                if($row["password_user"] == $password){
                    //check active status
                    if($row["active"] == 1){
                        //sesion
                        $_SESSION["id"] =  $row["id_students"];
                        $_SESSION["first_name"] =  $row["first_name"];
                        $_SESSION["last_name"] =  $row["last_name"];
                        $_SESSION["email"] =  $row["email"];
                        $_SESSION["password"] =  $row["password_user"];
                        $_SESSION["active"] =  $row["active"];
                        $_SESSION["type"] =  $row["type"];         
                        //check type and send to new page 
                        if($row["type"] == 0){
                            ob_clean();
                            header("Location: http://localhost/admin/main_admin.php");//send user to this page (user is admin cus type = 0)
                            $conn->close();
                            exit();
                        }else{
                            ob_clean();
                            header("Location: http://localhost/student/main_student.php");//send user to this page (user is student cus type = 1)
                            $conn->close();
                            exit();
                        }
                    }else{//inactive stautss
                        $print = "<div><p>you are no longer part of CUCEA so you cannot have access to the library :(</p></div>";
                        echo $print;
                    }
                }else{//incorrect password
                    $print = "<div><p>INCORRECT password</p></div>";
                    echo $print;
                }
                $conn->close();
            }else{//no email found in database
                $print = "<div><p>INCORRECT USER</p></div>";
                echo $print;
            }
        }
    ?>
</body>
</html>