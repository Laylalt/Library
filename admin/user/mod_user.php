<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Document</title>
</head>
<body>
    <?php //conecting to database
    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        $conn = connect();
        head();
        $print = "<p><a href='http://localhost/admin/library_admin.php?acc=1'><--Go back</a></p>";
        echo $print;
        //------------------------------------------------------------------------------------------------------------
        if(isset($_GET["i"])){
            $id = $_GET["i"];
            $sql = "SELECT * FROM students WHERE id_students = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tabla = "<div class = 'F'>";
                $tabla .= "<form action='mod_user_submit.php' method='post' id='form1'><br> ";
                $tabla .= "<label for='id'><p>ID:</p></label>";
                $tabla .= "<input type=text name='id' id='isbn' value=" . $row["id_students"] . " readonly>";
                $tabla .= "<label for='first_name'>First name:</label>";
                $tabla .= "<input type=text name='first_name' value=" . $row["first_name"] . " >";
                $tabla .= "<label for='last_name'>Last name:</label>";
                $tabla .= "<input type=text name='last_name' value=" . $row["last_name"] . " >";
                $tabla .= "<label for='phone_number'>Phone nuber:</label>";
                $tabla .= "<input type=text name='phone_number' value=" . $row["phone_number"] . " >";
                $tabla .= "<label for='email'>e-mail:</label>";
                $tabla .= "<input type=text name='email' value=" . $row["email"] . ">";
                $tabla .= "<label for='password_user'>Password:</label>";
                $tabla .= "<input type=text name='password_user' value=" . $row["password_user"] . " >";
                $tabla .= "<label for='active'>Status:</label>";
                $tabla .= "<select name='active'>";
                $tabla .= "<option value=1 >active</option>";
                $tabla .= "<option value=0 >inactive</option>";
                $tabla .="</select>";
                $tabla .= "<label for='type'>type:</label>";
                $tabla .= "<select name='type'>";
                $tabla .= "<option value=1 >Student</option>";
                $tabla .= "<option value=0 >Admin</option>";
                $tabla .="</select>";
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