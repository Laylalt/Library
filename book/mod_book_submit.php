<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <p><a href="http://localhost/library.php?acc=3"><--Go back</a></p>
    <?php 
        //conecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
        if(isset($_POST["isbn"]) && isset($_POST["title"]) && isset($_POST["dewey_code"]) && isset($_POST["copy_number"]) && isset($_POST["availability"])){
            $isbn = $_POST["isbn"];
            $title = $_POST["title"];
            $dewey_code = $_POST["dewey_code"];
            $copy_number = $_POST["copy_number"];
            $availability = $_POST["availability"];
            $sql = "UPDATE books SET title = '$title' WHERE id_isbn = $isbn";
            if($conn->query($sql) === TRUE){
                $sql = "UPDATE books SET dewey_code = '$dewey_code' WHERE id_isbn = $isbn"; 
                if($conn->query($sql) === TRUE){
                    $sql = "UPDATE books SET copy_number = '$copy_number' WHERE id_isbn = $isbn";
                    if($conn->query($sql) === TRUE){
                        $sql = "UPDATE books SET availability = '$availability' WHERE id_isbn = $isbn";
                        if($conn->query($sql) === TRUE){
                            echo "Book updated to:";
                            $tabla = "<table border =2px>";
                            $tabla .= "<tr><td>ISBN</td><td>Title</td><td>Dewey Code</td>";
                            $tabla .= "<td>Number of copies</td><td>Available copies</td></tr>";
                            $tabla .= "<tr>";
                            $tabla .= "<td>" . $isbn  . "</td>";
                            $tabla .= "<td>" . $title . "</td>";
                            $tabla .= "<td>" . $dewey_code . "</td>";
                            $tabla .= "<td>" . $copy_number . "</td>";
                            $tabla .= "<td>" . $availability . "</td>";
                            $tabla .= "</tr>";
                            $tabla .= "</table>";
                            echo $tabla;
                        }else{
                            echo "Error updating availability" . $conn->error; 
                        }
                    }else {
                        echo "Error updating copy number" . $conn->error;
                    }
                }else{
                    echo "Error updating dewey code" . $conn->error;
                }
            }else{
                echo "Error updating title" . $conn->error;
            }
        }else{
            echo "error reciving the data";
        }
        
        $conn->close();
    ?>
</body>
</html>