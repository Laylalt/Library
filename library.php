<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Books</title>
</head>
<body>
    <a href="http://localhost/">HOME</a> 
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
    ?>
    <?php
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
                    $tabla .= "<tr><td>id</td><td>First Name</td><td>Last name</td><td>phone_number</td>";
                    $tabla .= "<td>email</td><td>status</td><td></td></tr>";//header row
                    while($row = $result->fetch_assoc()) {
                        $tabla .= "<tr>";
                        $tabla .= "<td>" . $row["id_students"] . "</td>";
                        $tabla .= "<td>" . $row["first_name"] . "</td>";
                        $tabla .= "<td>" . $row["last_name"] . "</td>";
                        $tabla .= "<td>" . $row["phone_number"] . "</td>";
                        $tabla .= "<td>" . $row["email"] . "</td>";
                        $tabla .= "<td>" . $row["active"] . "</td>";
                        $tabla .= "<td><a href='user/mod_user.php?i=" . $row["id_students"] . "'>edit</a></td>";
                        $tabla .= "<td><a href='user/del_user.php?i=" . $row["id_students"] . "'>delete</a></td>";
                        $tabla .= "</tr>";
                    }
                    $tabla .= "</table>";
                    echo $tabla;
                    } else {
                        echo "0 results";
                    }
            } else if ($opcion == "2"){

            } else if ($opcion == "3"){
    ?>
    <p>books</p>
    <a href='book/add_book.php'>add book to library img.</a> <!-- html of books -->
    <?php
                $sql = "SELECT books.id_isbn, books.title, books.dewey_code, books.copy_number, books.availability, author.first_name, author.last_name, publisher.description
                FROM books
                JOIN relationba ON books.id_isbn = relationba.id_isbn
                JOIN author ON relationba.id_author = author.id_author
                JOIN relationbp ON relationbp.id_isbn = books.id_isbn
                JOIN publisher ON publisher.id_publisher = relationbp.id_publisher;
                ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                      // output data of each row		
                    $tabla = "<table>";
                    $tabla .= "<tr><td>ISBN</td><td>Title</td><td>Dewey Code</td>";
                    $tabla .= "<td>Author</td> <td></td><td>publisher</td>";
                    $tabla .= "<td>Number of copies</td><td>Available copies</td></tr>";//header row
                    while($row = $result->fetch_assoc()) {
                        $tabla .= "<tr>";
                        $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                        $tabla .= "<td>" . $row["title"] . "</td>";
                        $tabla .= "<td>" . $row["dewey_code"] . "</td>";
                        $tabla .= "<td>" . $row["first_name"] . "</td>";
                        $tabla .= "<td>" . $row["last_name"] . "</td>";
                        $tabla .= "<td>" . $row["description"] . "</td>";
                        $tabla .= "<td>" . $row["copy_number"] . "</td>";
                        $tabla .= "<td>" . $row["availability"] . "</td>";
                        $tabla .= "<td><a href='book/mod_book.php?i=" . $row["id_isbn"] . "'>edit</a></td>";
                        $tabla .= "<td><a href='book/del_book.php?i=" . $row["id_isbn"] . "'>delete</a></td>";
                        $tabla .= "</tr>";
                    }
                    $tabla .= "</table>";
                    echo $tabla;
                    } else {
                        echo "0 results";
                    }
            } else{ #end of books php
    ?>
    <?php
            }
            
        }else {
            echo ".l.";
        }
        $conn->close();
    ?>

    </body>
</html>