<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Books</title>
</head>
<body>
    <?php
        require_once('../func.php');
        $x = check();
        if(isset($x) && $x == 0){ 
            head(); 
            $conn = connect();
            if (isset($_GET["acc"])){ 
                $opcion = $_GET["acc"];
                if ($opcion == "1"){ //USERS 
    ?>
    <p>Users</p>
    <a href='user/add_user.php'>add student to database img.</a> <!-- html of users-->
    <?php
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row		
            $tabla = "<table>";
            $tabla .= "<tr><td> </td><td>id</td><td>First Name</td><td>Last name</td><td>phone_number</td>";
            $tabla .= "<td>email</td><td>status</td></tr>";//header row
            while($row = $result->fetch_assoc()) {
                $tabla .= "<tr>";
                $tabla .= "<td><a href='user/mod_user.php?i=" . $row["id_students"] . "'>E</a><br>";
                $tabla .= "<a href='user/del_user.php?i=" . $row["id_students"] . "'>D</a></td>";
                $tabla .= "<td>" . $row["id_students"] . "</td>";
                $tabla .= "<td>" . $row["first_name"] . "</td>";
                $tabla .= "<td>" . $row["last_name"] . "</td>";
                $tabla .= "<td>" . $row["phone_number"] . "</td>";
                $tabla .= "<td>" . $row["email"] . "</td>";
                $tabla .= "<td>" . $row["active"] . "</td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;
        } else {
            echo "0 results";
        }
        } else if ($opcion == "2"){
            //loans

        } else if ($opcion == "3"){
    ?>
    <p>books</p>
    <a href='book/add_book.php'>add book to library img.</a> <!-- html of books -->
    <?php
        $sql = "SELECT id_isbn FROM books;";
        $result = $conn->query($sql);
        $array_isbn = array();
        while($row = $result->fetch_assoc()){
            array_push($array_isbn, $row["id_isbn"]);
        }
        $tabla = "<table>";
        $tabla .= "<tr><td> </td><td>ISBN</td><td>Title</td><td>Dewey Code</td>";
        $tabla .= "<td>Author</td><td>Publisher</td><td>Genre</td>";
        $tabla .= "<td>Number of copies</td><td>Available copies</td></tr>";
        for($x = 0; $x < count($array_isbn); $x++){
            $sql = "SELECT * FROM books WHERE id_isbn = $array_isbn[$x];";
            $result = $conn->query($sql);
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
            $tabla .= "<td><a href='book/mod_book.php?i=" . $id . "'>E</a><br>";
            $tabla .= "<a href='book/del_book.php?i=" . $id . "'>D</a></td>";
            $tabla .= "<td>" . $id . "</td>";
            $tabla .= "<td>" . $title . "</td>";
            $tabla .= "<td>" . $dewey_code . "</td>";
            $tabla .= "<td>" . $author . "</td>";
            $tabla .= "<td>" . $publisher . "</td>";
            $tabla .= "<td>" . $genre . "</td>";
            $tabla .= "<td>" . $copy_number . "</td>";
            $tabla .= "<td>" . $availability . "</td>";
            $tabla .= "</tr>"; 
        }//end of for to print table                
        $tabla .= "</table>";
        echo $tabla;
            } else if ($opcion == "4"){ 
                //fees
            }else{
                echo "Aqui no hay nada";
            }   
        }else {#if cc is not set (no option chosen)
            echo "Aqui no hay nada";
        }
        $conn->close();
    }else{
    ?>
<p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
    <?php
     }
    ?>
</body>
</html>