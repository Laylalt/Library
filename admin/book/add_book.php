<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add book</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <?php

    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        head();
        $print = "<p><a href='http://localhost/admin/library_admin.php?acc=3'><--Go back</a></p>";
        echo $print;
        $tabla = "<div>";
        $tabla .= "<form action='add_book_submit.php' method='post' id='form1'><br> ";
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
        $tabla .= "</div>";
        echo $tabla;
    }else{ ?>
        <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
    }
?>
    
</body>
</html>