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
require_once('../../func.php');
 $x = check();
 if(isset($x) && $x == 0){
    head();
    if (isset($_GET["acc"])){
        $conn = connect();
        $isbn = $_GET["acc"];
        $print = "<p><a href='http://localhost/admin/library_admin.php?acc=3'><--Go back</a></p>";
        echo $print;
        $tabla = "<div>";
        $tabla .= "<form action='' method='post' id='form2'><br> ";
        $tabla .= "<label for='author'>Author(s):</label><br>";
        $tabla .= "<input class = 'S' type=text name='author'>";
        $tabla .= "<button class = 'S' type='submit' form='form2' value='submit' name='au'>Add author</button>";
        $tabla .= "</form>";
        $tabla .= "<form action='' method='post' id='form3'><br> ";
        $tabla .= "<label for='genre'>Genre(s):</label><br>";
        $tabla .= "<input class = 'S' type=text name='genre'>";
        $tabla .= "<button  class = 'S' type='submit' form='form3' value='submit' name='gen'>Add genre</button>";
        $tabla .= "</form>";
        //----------------------------------------------------------------------------------------------
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='title'>Title:</label>";
        $tabla .= "<input type=text name='title'><br>";
        $tabla .= "<label for='dewey_code'>Dewey code:</label>";
        $tabla .= "<input type=text name='dewey_code'><br>";
        $tabla .= "<label for='copy_number'>Copy number:</label>";
        $tabla .= "<input type=text name='copy_number'><br>";
        $tabla .= "<label for='availability'>Available copies:</label>";
        $tabla .= "<input type=text name='availability'><br>";
        $tabla .= "<label for='publisher'>Publisher:</label>";
        $tabla .= "<input type=text name='publisher'><br>";
        $tabla .= "</form>";
        $tabla .= "<button  type='submit' form='form1' value='submit' name='submit'>Add book</button>";
        $tabla .= "</div>";
        echo $tabla;
        //add authors
        if(isset($_REQUEST["au"])){
            $author = explode(" ", $_POST["author"]);
            $first_name = $author[0];
            $last_name = $author[1];
            $sql = "SELECT id_author FROM author WHERE first_name = '$first_name' AND last_name = '$last_name' ;";
            $result = $conn->query($sql);
            if ($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $id_author = $row["id_author"];
                $sql = "INSERT INTO relationba VALUES ($isbn,$id_author);";
                $result = $conn->query($sql);
            }else{
                $sql = "SELECT id_author FROM author ORDER BY id_author DESC LIMIT 1;";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $id_author = $row["id_author"] + 1;
                $sql = "INSERT INTO author VALUES ($id_author, '$first_name', '$last_name');";
                $result = $conn->query($sql);  
                $sql = "INSERT INTO relationba VALUES ($isbn,$id_author);";
                $result = $conn->query($sql);
            }     
        }
        //add Genres
        if(isset($_REQUEST["gen"])){
            $genre = $_POST["genre"];
            $sql = "SELECT id_genre FROM genre WHERE description = '$genre';";
            $result = $conn->query($sql);
            if ($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $id_genre = $row["id_genre"];
                $sql = "INSERT INTO relationbg VALUES ($isbn,$id_genre);";
                $result = $conn->query($sql);
            }else{
                $sql = "SELECT id_genre FROM genre ORDER BY id_genre DESC LIMIT 1;";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $id_genre = $row["id_genre"] + 1;
                $sql = "INSERT INTO genre VALUES ($id_genre, '$genre');";
                $result = $conn->query($sql);  
                $sql = "INSERT INTO relationbg VALUES ($isbn,$id_genre);";
                $result = $conn->query($sql);
            }     
        }
        //get post values of book
        if(isset($_REQUEST["submit"])){
            $title = $_POST["title"];
            $dewey_code = $_POST["dewey_code"];
            $copy_number = $_POST["copy_number"];
            $availability = $_POST["availability"];
            $publisher = $_POST["publisher"];
            $sql = "UPDATE books SET title = '$title', dewey_code = $dewey_code, availability = $availability, copy_number = $copy_number WHERE id_isbn = $isbn;";
            $result = $conn->query($sql);
            //publisher
            $sql = "SELECT id_publisher FROM publisher WHERE description = '$publisher';";
            $result = $conn->query($sql);
            if ($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $id_publisher = $row["id_publisher"];
                $sql = "INSERT INTO relationbp VALUES ($isbn,$id_publisher);";
                $result = $conn->query($sql);
            }else{
                $sql = "SELECT id_publisher FROM publisher ORDER BY id_publisher DESC LIMIT 1;";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $id_publisher = $row["id_publisher"] + 1;
                $sql = "INSERT INTO publisher VALUES ($id_publisher, '$publisher');";
                $result = $conn->query($sql);  
                $sql = "INSERT INTO relationbp VALUES ($isbn,$id_publisher);";
                $result = $conn->query($sql);
            }     
            //send to new page to print new book
            ob_clean();
            header("Location: http://localhost/admin/book/add_book_submit.php");
            exit();  
        }
    }else{
        echo "What are you doing here?";
    }
 }else{ ?>
     <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
 }
?>
</body>
</html>