<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style.css">
    <title>Document</title>
</head>
<body>
<?php
    require_once('../../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        head();
        if (isset($_POST["isbn"])){
            $conn = connect();
            $isbn = $_POST["isbn"];
            $c = 1;
            //print book
            $tabla = "<table>";
            $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
            $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
            $tabla .= "<th>Number of copies</th><th>Available copies</th><th></th></tr>";
            $sql = "SELECT * FROM books WHERE id_isbn = '$isbn';";
            $result = $conn->query($sql);
            if ($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $id = $row["id_isbn"];
                $title = $row["title"];
                $dewey_code = $row["dewey_code"];
                $copy_number = $row["copy_number"];
                $availability = $row["availability"];
                $author = "";
                $publisher = "";
                $genre = "";
                //author
                $sql = "SELECT author.first_name, author.last_name FROM relationba JOIN author ON relationba.id_author = author.id_author WHERE relationba.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $author .= $row["first_name"] . " " . $row["last_name"] . "<br>"; 
                    }
                } else{
                    $author = "Unknown";
                }
                //publisher
                $sql = "SELECT publisher.description FROM relationbp JOIN publisher ON relationbp.id_publisher = publisher.id_publisher WHERE relationbp.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $publisher .= $row["description"];     
                } else{
                    $publisher = "Unknown";
                }
                //genre
                $sql = "SELECT genre.description FROM relationbg JOIN genre ON relationbg.id_genre = genre.id_genre WHERE relationbg.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $genre .= $row["description"] . "<br>"; 
                    }
                } else{
                    $genre = "Unknown";
                }
                //print table of row
                $tabla .= "<tr>";
                $tabla .= "<td>" . $id . "</td>";
                $tabla .= "<td>" . $title . "</td>";
                $tabla .= "<td>" . $dewey_code . "</td>";
                $tabla .= "<td>" . $author . "</td>";
                $tabla .= "<td>" . $publisher . "</td>";
                $tabla .= "<td>" . $genre . "</td>";
                $tabla .= "<td>" . $copy_number . "</td>";
                $tabla .= "<td>" . $availability . "</td>";
                $tabla .= "<td class='ed'><a href='book/mod_book.php?i=" . $id . "'>E</a><br>";
                $tabla .= "<a href='book/del_book.php?i=" . $id . "'>D</a></td>";
                $tabla .= "</tr>"; 
            }else{
                $c = 0;
            }
            if($c == 0){
                echo "<div class = 'W'>No books found :( </div>";
            }else{
                $tabla .= "</table>";
                echo $tabla;
            }           
        }else{
            echo "what are you doing here?";
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }

?>
</body>
</html>