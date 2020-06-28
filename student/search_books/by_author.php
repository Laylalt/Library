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
    //STUDENTS
    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 1){
        head();
        $conn = connect();
        $f = "<div class = 'S'>";
        $f .= "<form action='' method='post' id='form1'>";
        $f .= "<label for='author'>Author</label>";
        $f .= "<input class = 'S'type=text name='author'>";
        $f .= "<button class = 'S' type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        $f .= "</form>";
        $f .= "</div>";
        echo $f;
        $print = "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_author.php'>By author</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_genre.php'>By genre</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_title.php'>By Title</a></div>";
        echo $print;
        $v = sval($conn, $_SESSION["id"]);
        if(isset($_REQUEST["sbmt"])){
            $author = $_POST["author"];
            $sql = "SELECT books.id_isbn FROM books JOIN relationba ON relationba.id_isbn = books.id_isbn JOIN author ON relationba.id_author = author.id_author WHERE author.first_name LIKE '%$author%' OR author.last_name LIKE '%$author%' AND books.copy_number >=1;";
            $result = $conn->query($sql);
            $array_isbn = array();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array_isbn, $row["id_isbn"]);
                }
                $tabla = "<table>";
                $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
                $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
                $tabla .= "<th>Available copies</th><th></th></tr>";
                for($x = 0; $x < count($array_isbn); $x++){
                    $tabla .=  printbook($conn, $array_isbn[$x],$v);
                }
                $tabla .= "</table>";
                echo  $tabla;
            }else{
                echo "<div class = 'Ws'>No book found with that author</div>";
            }
        }else{
            $sql = "SELECT books.id_isbn FROM books JOIN relationba ON relationba.id_isbn = books.id_isbn JOIN author ON relationba.id_author = author.id_author WHERE books.copy_number >=1 ORDER BY author.first_name;";
            $result = $conn->query($sql);
            $array_isbn = array();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    array_push($array_isbn, $row["id_isbn"]);
                }
                $tabla = "<table>";
                $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
                $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
                $tabla .= "<th>Available copies</th><th></th></tr>";
                for($x = 0; $x < count($array_isbn); $x++){
                    $tabla .=  printbook($conn, $array_isbn[$x],$v);
                }
                $tabla .= "</table>";
                echo  $tabla;
            }
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
?>  
</body>
</html>