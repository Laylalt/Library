
<?php
    require_once('../func.php');
    $x = check();
    if(isset($x) && $x == 0){  
?>
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
        head();
        $conn = connect();
        top_main($conn);
    ?>
</body>
</html>
<?php
    }else{
?>
<p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
    }
?>