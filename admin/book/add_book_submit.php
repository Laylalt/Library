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
            echo $print;
            echo "<div class = 'W'>New book added</div>";
            $sql = "SELECT * FROM books ORDER BY id_isbn DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();	
                //get book values
                $id = $row["id_isbn"];
                $title = $row["title"];
                $dewey_code = $row["dewey_code"];
                $copy_number = $row["copy_number"];
                $availability = $row["availability"];
                $author = "";
                $publisher = "";
                $genre = "";
                //get author
                $sql = "SELECT author.first_name, author.last_name FROM relationba JOIN author ON relationba.id_author = author.id_author WHERE relationba.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $author .= $row["first_name"] . " " . $row["last_name"] . "<br>"; 
                    }
                } else{
                    $author = "Unknown";
                }
                // get publisher
                $sql = "SELECT publisher.description FROM relationbp JOIN publisher ON relationbp.id_publisher = publisher.id_publisher WHERE relationbp.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $publisher .= $row["description"]; 
                    
                } else{
                    $publisher = "Unknown";
                }
                // get genre
                $sql = "SELECT genre.description FROM relationbg JOIN genre ON relationbg.id_genre = genre.id_genre WHERE relationbg.id_isbn = $id;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $genre .= $row["description"] . "<br>"; 
                    }
                } else{
                    $genre = "Unknown";
                }
    
                //print
                $tabla = "<table>";
                $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
                $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
                $tabla .= "<th>Number of copies</th><th>Available copies</th><td class='ed'></td></tr>";
                $tabla .= "<tr>";
                $tabla .= "<td>" . $id . "</td>";
                $tabla .= "<td>" . $title . "</td>";
                $tabla .= "<td>" . $dewey_code . "</td>";
                $tabla .= "<td>" . $author . "</td>";
                $tabla .= "<td>" . $publisher . "</td>";
                $tabla .= "<td>" . $genre . "</td>";
                $tabla .= "<td>" . $copy_number . "</td>";
                $tabla .= "<td>" . $availability . "</td>";
                $tabla .= "</tr>";
                $tabla .= "</table>";
                echo $tabla;
                } else {
                    echo "0 results";
                    }
        }else{
?>
            <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
 <?php
            }
        
?>
</body>
</html>