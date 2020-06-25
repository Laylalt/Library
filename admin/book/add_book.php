<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add book</title>
</head>
<body>
<?php
    require_once('../../func.php');
    $x = check();
    $conn = connect();
    if(isset($x) && $x == 0){
        $sql = "INSERT INTO books (title) VALUES(0);";
        if($conn->query($sql) === TRUE){
            $sql = "SELECT * FROM books ORDER BY id_isbn DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                echo $row ["id_isbn"];
                ob_clean();
                header("Location: http://localhost/admin/book/add_book_form.php?acc=" . $row["id_isbn"] . "");
                exit(); 
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>
</body>
</html>