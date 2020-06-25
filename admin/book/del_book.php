<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <?php 
    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        $conn = connect();
        head();
        $print = "<p><a href='http://localhost/admin/library_admin.php?acc=3'><--Go back</a></p>";
        echo $print;
        //-----------------------------------------------------------------------------------------------
        if(isset($_GET["i"])){
                $isbn = $_GET["i"];
                $sql = "UPDATE books SET copy_number = 0 WHERE id_isbn = $isbn;";
                if ($conn->query($sql) === TRUE) {
                    echo "<div class = 'W'>Book deleted succesfully</div>";
                }else{
                    echo "error";
                }
                $conn->close();    
            }
            
            //end of if(issset(GET))
        }else{?>
            <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
        }
?>
</body>
</html>