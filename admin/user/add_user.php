<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Document</title>
</head>
<body>
    <?php
        //checking authorization to access
        require_once('../../func.php');
        $x = check();
        if(isset($x) && $x == 0){  //end of check
            head();
            $print = "<p><a href='http://localhost/admin/library_admin.php?acc=1'><--Go back</a></p>";
            echo $print;
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
            $tabla .= "<input type=password name='password_user'><br>";
            $tabla .= "<label for='type'>type:</label>";
                $tabla .= "<select name='type'>";
                $tabla .= "<option value=1 >Student</option>";
                $tabla .= "<option value=0 >Admin</option>";
                $tabla .="</select>";
            $tabla .= "</form>";
            $tabla .= "<button type='submit' form='form1' value='submit'>Submit</button>";
            $tabla .= "</div>";
            echo $tabla;
        }else{
    ?>
            <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
    <?php
        }
    ?>
</body>
</html>