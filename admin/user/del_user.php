<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        require_once('../../func.php');
        $x = check();
        if(isset($x) && $x == 0){
            $conn = connect();
            head();
            $print = "<p><a href='http://localhost/admin/library_admin.php?acc=1'><--Go back</a></p>";
            echo $print;
            if(isset($_GET["i"])){
                $id = $_GET["i"];
                $sql = "DELETE FROM students WHERE id_students = $id";
                if ($conn->query($sql) === TRUE) {
                    echo "student deleted succesfully";
                } else {
                    echo "Error deleting record" . $conn->error;
                }
            $conn->close();
        }//end of if(issset(GET))
    }else{
 ?>
        <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
    }
?>
</body>
</html>