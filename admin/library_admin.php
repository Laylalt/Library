<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main menu</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
    require_once('../func.php');

    function users($conn){
        echo "<br><a href='user/add_user.php'>+ add user</a><br>";
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row		
            $tabla = "<table>";
            $tabla .= "<tr><th>id</th><th>First Name</th><th>Last name</th><th>phone_number</th>";
            $tabla .= "<th>email</th><th>status</th><td class='ed'></td></tr>";//header row
            while($row = $result->fetch_assoc()) {
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_students"] . "</td>";
                $tabla .= "<td>" . $row["first_name"] . "</td>";
                $tabla .= "<td>" . $row["last_name"] . "</td>";
                $tabla .= "<td>" . $row["phone_number"] . "</td>";
                $tabla .= "<td>" . $row["email"] . "</td>";
                $tabla .= "<td>" . $row["active"] . "</td>";
                $tabla .= "<td class='ed'><a href='user/mod_user.php?i=" . $row["id_students"] . "'>E</a><br>";
                $tabla .= "<a href='user/del_user.php?i=" . $row["id_students"] . "'>D</a></td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;
        } else {
            echo "0 results";
        }
    }

    function books($conn){
        echo "<br><a href='book/add_book.php'>+add book </a><br>";
        $sql = "SELECT id_isbn FROM books;";
        $result = $conn->query($sql);
        $array_isbn = array();
        while($row = $result->fetch_assoc()){
            array_push($array_isbn, $row["id_isbn"]);
        }
        $tabla = "<table>";
        $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
        $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
        $tabla .= "<th>Number of copies</th><th>Available copies</th><td class='ed'></td></tr>";
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
        }//end of for to print table                
        $tabla .= "</table>";
        echo $tabla;
    }

    function loans($conn){
        echo "<br><a href='loan-fee/add_loan.php'>Add loan </a><a href='loan-fee/return_loan.php'>| Return loan</a><br>";
        $sql = "SELECT id_students, id_isbn, id_admin_out, date_out, date_sin FROM loans WHERE active = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row		
            $tabla = "<table>";
            $tabla .= "<tr><th>id Student</th><th>ISBN</th><th>Admin out</th><th>Date borrowed</th>";
            $tabla .= "<th>Date to return</th></tr>";//header row
            while($row = $result->fetch_assoc()) {
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_students"] . "</td>";
                $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                $tabla .= "<td>" . $row["id_admin_out"] . "</td>";
                $tabla .= "<td>" . $row["date_out"] . "</td>";
                $tabla .= "<td>" . $row["date_sin"] . "</td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;
        } else {
            echo "0 results";
        }

    }
    function fees($conn){
        echo "FEES";
    }
?>
<?php
    $x = check();
    if(isset($x) && $x == 0){
        head(); 
        if (isset($_GET["acc"])){
            $conn = connect();
            $opcion = $_GET["acc"];
            if ($opcion == "1"){
                users($conn);
            }else if ($opcion == "2"){
                loans($conn);
            }else if ($opcion == "3"){
                books($conn);
            }else if($opcion == "4"){
                fees($conn);
            }else{
                echo "What are you doing here?";
            }
            $conn->close();
        }else{
            echo "What are you doing here?";
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
    }
    
?>
</body>
</html>