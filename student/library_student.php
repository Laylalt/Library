<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
    require_once('../func.php');

    function sbooks($conn){
        $print = "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_author.php'>By author</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_genre.php'>By genre</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/student/search_books/by_title.php'>By Title</a></div>";
        echo $print;
        $v = sval($conn, $_SESSION["id"]);
        $sql = "SELECT * FROM books WHERE availability >= 2 AND copy_number >= 1;";
        $result = $conn->query($sql);
        $array_isbn = array();
        while($row = $result->fetch_assoc()){
            array_push($array_isbn, $row["id_isbn"]);
        }
        $tabla = "<table class = 'BS'>";
        $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
        $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
        $tabla .= "<th>Available copies</th><th></th></td></tr>";
        for($x = 0; $x < count($array_isbn); $x++){
            $sql = "SELECT * FROM books WHERE id_isbn = $array_isbn[$x];";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $id = $row["id_isbn"];
            $title = $row["title"];
            $dewey_code = $row["dewey_code"];
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
            if($availability >= 2){
                if($v == 1){
                    $tabla .= "<td>" . $availability . "</td>";
                    $tabla .= "<td><a href='http://localhost/student/s_add_loan.php?i=" . $id . "'>Select</a></td>";
                }else{
                    $tabla .= "<td colspan = '2'>" . $availability . "</td>";
                }
            }else{
                $tabla .= "<td>No copies available at the moment</td>";
            }
            $tabla .= "</tr>";
        }
        $tabla .= "</table>";
        echo $tabla;
    }

    function hloans($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT books.id_isbn, books.title, loans.date_out, loans.date_sin, loans.date_rin FROM books JOIN loans ON books.id_isbn = loans.id_isbn AND loans.active = 0 AND loans.id_students = $id AND loans.id_admin_out != 0;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $tabla = "<table>";
            $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Borrowed date</th><th>Due date</th><th>Returned date</th></tr>";
            while($row = $result->fetch_assoc()){
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_isbn"] ."</td>";
                $tabla .= "<td>" . $row["title"] ."</td>";
                $tabla .= "<td>" . $row["date_out"] ."</td>";
                $tabla .= "<td>" . $row["date_sin"] ."</td>";
                $tabla .= "<td>" . $row["date_rin"] ."</td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;

        }else{
            echo "<div class = 'W'>You have not returned any books yet!</div>";
        }
    }
    function hfees($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT books.id_isbn, books.title, loans.date_out, loans.date_sin, loans.date_rin, fees.date_paid, fees.ammount FROM books JOIN loans ON books.id_isbn = loans.id_isbn JOIN fees ON loans.id_loan = fees.id_loan AND loans.active = 0 AND loans.id_students = $id;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $tabla = "<table>";
            $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Borrowed date</th><th>Due date</th><th>Returned date</th></tr>";
            while($row = $result->fetch_assoc()){
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_isbn"] ."</td>";
                $tabla .= "<td>" . $row["title"] ."</td>";
                $tabla .= "<td>" . $row["date_out"] ."</td>";
                $tabla .= "<td>" . $row["date_sin"] ."</td>";
                $tabla .= "<td>" . $row["date_rin"] ."</td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;

        }else{
            echo "<div class = 'W'>You never had a fee, Congratulations :) </div>";
        }
    }

    function aloans($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT * FROM loans WHERE id_students = $id AND active = 1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output 
            $tabla = "<table>";
            $tabla .= "<tr><th>ISBN<th>Date borrowed</th>";
            $tabla .= "<th>Date to return</th><th>Status</th><th>Fee</th></tr>";//header row
            while($row = $result->fetch_assoc()) {
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                $tabla .= "<td>" . $row["date_out"] . "</td>";
                $tabla .= "<td>" . $row["date_sin"] . "</td>";
                $today = date("Y-m-d");
                $due_date = $row["date_sin"];
                if($today > $due_date){ // if its late 
                    //check ammount
                    $today = date("Y-m-d");
                    $due_date = $row["date_sin"];
                    $today = strtotime($today);
                    $due_date = strtotime($due_date);
                    $days = intval(($today - $due_date)/60/60/24);//gets int from float
                    if($days == 0){ //checking number of days to get out ammount
                        $ammount = 20;
                    }else{
                        $ammount = 20 * $days;
                    }
                    $tabla .= "<td>Late</td>";
                    $tabla .= "<td>$". $ammount . "</td>";
                }else{
                    $tabla .= "<td>On time</td>";
                    $tabla .= "<td>No fee</td>";  
                }
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;
        } else {
            echo "<div class = 'W'>You don't have any active loans at the moment</div>";
        }
    }
    
    function afees($conn){
        $id = $_SESSION["id"];
        $c = 0;
        $tablaf = "<table>";
        $tablaf .= "<th>ISBN</th>";
        $tablaf .= "<th>Due date</th><th>Returned date</th><th>Fee</th></tr>";//header row
        $sql = "SELECT loans.id_isbn, loans.date_sin, loans.date_rin, fees.ammount FROM fees JOIN loans on loans.id_loan = fees.id_loan AND fees.id_students = $id AND fees.active = 1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tablaf .= "<tr>";
                $tablaf .= "<td>" . $row["id_isbn"] . "</td>";
                $tablaf .= "<td>" . $row["date_sin"] . "</td>";
                $tablaf .= "<td>" . $row["date_rin"] . "</td>";
                $tablaf .= "<td>$" . $row["ammount"] . "</td>";
                $tablaf .= "</tr>";
            }
            $c = $c + 1;
        }
        $sql = "SELECT * FROM loans WHERE active = 1 AND id_students = $id;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $today = date("Y-m-d");
                $due_date = $row["date_sin"];
                if($today > $due_date){ //cheking if a loan is  late
                    //check ammount 
                    $today = date("Y-m-d");
                    $due_date = $row["date_sin"];
                    $today = strtotime($today);
                    $due_date = strtotime($due_date);
                    $days = intval(($today - $due_date)/60/60/24);//gets int from float
                    if($days == 0){ //checking number of days to get out ammount
                        $ammount = 20;
                    }else{
                        $ammount = 20 * $days;
                    }
                    //print
                    $tablaf .= "<tr>";
                    $tablaf .= "<td>" . $row["id_isbn"] . "</td>";
                    $tablaf .= "<td>" . $row["date_sin"] . "</td>";
                    $tablaf .= "<td>Not returned yet</td>";
                    $tablaf .= "<td>$" . $ammount . "</td>";
                    $tablaf .= "</tr>";
                    $c = $c + 1;
                }
            }
        }
        if($c == 0){
            echo "<div class = 'W'>You do not have any fees :)</div>";
        }else{
            echo $tablaf;
        }
    }

    function rbooks($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT books.id_isbn, books.title, loans.id_loan FROM books JOIN loans ON books.id_isbn = loans.id_isbn WHERE id_students = $id AND active = 0 AND id_admin_out = 0;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $table = "<table>";
            $table .= "<tr><th>ISBN</th><th>Title</th><th></th></tr>";
            while($row = $result->fetch_assoc()){
                $table .= "<tr>";
                $table .= "<td>" . $row["id_isbn"] . "</td>";
                $table .= "<td>" . $row["title"] . "</td>";
                $table .= "<td><a href='http://localhost/student/s_del_loan.php?i=" . $row["id_loan"] . "'>Delete</a></td>";
                $table .= "</tr>";
            }
            $table .= "</table>";
            echo $table;
        }else{
            echo "<div class = 'W'>you havent requested a book yet</div>";
        }
    }

    $x = check();
    if(isset($x) && $x == 1){
        head();
        if (isset($_GET["acc"])){
            $conn = connect();
            $opcion = $_GET["acc"];
            if ($opcion == "1"){
                hloans($conn);
            }else if ($opcion == "2"){
                hfees($conn);
            }else if ($opcion == "3"){
                sbooks($conn);
            }else if ($opcion == "4"){
                aloans($conn);
            }else if($opcion == "5"){
                afees($conn);
            }else if($opcion == "6"){
                rbooks($conn);
            }
            else{
                echo "what are you doing here?";
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