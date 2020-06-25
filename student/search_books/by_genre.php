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
    if(isset($x) && $x == 1){
        head();
        $conn = connect();
        $f = "<div>";
        $f .= "<form action='' method='post' id='form1'><br> ";
        $f .= "<label for='genre'>Genre:</label><br>";
        $f .= "<input class = 'S' type=text name='genre'>";
        $f .= "<button  class = 'S' type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        $f .= "</form>";
        $f .= "</div>";
        echo $f;
        $v = sval($conn, $_SESSION["id"]);
        if(isset($_REQUEST["sbmt"])){
            $genre = $_POST["genre"];
            $sql = "SELECT books.id_isbn FROM books JOIN relationbg ON relationbg.id_isbn = books.id_isbn JOIN genre ON relationbg.id_genre = genre.id_genre WHERE genre.description LIKE '%$genre%' AND books.copy_number >=1;";
            $result = $conn->query($sql);
            $array_isbn = array();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array_isbn, $row["id_isbn"]);
                }
                $tabla = "<table>";
                $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
                $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
                $tabla .= "<th>Available copies</th><td class='ed'></td></tr>";
                for($x = 0; $x < count($array_isbn); $x++){
                    $tabla .=  printbook($conn, $array_isbn[$x], $v);
                }
                $tabla .= "</table>";
                echo  $tabla;
            }else{
                echo "<div class = 'W'>No book found with that author</div>";
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>  
</body>
</html>