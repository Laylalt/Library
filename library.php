<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
</head>
<body>
    <a href="">HOME</a> <!-- what should we put here to go back home? -->
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
            if ($opcion == "1"){
    ?>
    <p>users</p>
    <?php
            } else if ($opcion == "2"){
    ?>
    <p>loans</p>
    <?php
            } else if ($opcion == "3"){
    ?>
    <p>books</p>
    <div><a href='add_book.php'>add book to library img.</a></div> <!-- html of books -->
    <?php
                $sql = "SELECT * FROM books";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                      // output data of each row		
                    $tabla = "<table border =2px>";
                    $tabla .= "<tr><td>ISBN</td><td>Title</td><td>Dewey Code</td>";
                    $tabla .= "<td>Number of copies</td><td>Available copies</td></tr>";//header row
                    while($row = $result->fetch_assoc()) {
                        $tabla .= "<tr>";
                        $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                        $tabla .= "<td>" . $row["title"] . "</td>";
                        $tabla .= "<td>" . $row["dewey_code"] . "</td>";
                        $tabla .= "<td>" . $row["copy_number"] . "</td>";
                        $tabla .= "<td>" . $row["availability"] . "</td>";
                        $tabla .= "<td><a href='mod_book.php?i=" . $row["id_isbn"] . "'>edit</a></td>";
                        $tabla .= "<td><a href='del_book.php?i=" . $row["id_isbn"] . "'>delete</a></td>";
                        $tabla .= "</tr>";
                    }
                    $tabla .= "</table>";
                    echo $tabla;
                    } else {
                        echo "0 results";
                    }
            } else{ #end of books php
    ?>
    <p>nada</p>
    <?php
            }
            
        }else {
            echo ".l.";
        }
        $conn->close();
    ?>

    </body>
</html>