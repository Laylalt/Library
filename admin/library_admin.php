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
        $search = "<div class = 'S'>";
        $search .= "<form action='http://localhost/admin/user/search_user.php' method='post' id='form1'>";
        $search .= "<label for='isbn'>ID</label>";
        $search .= "<input class= 'S' type=text name='id'>";
        $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
        $search .= "</form>";
        echo $search;
        echo "<div class = 'add'><a class = 'add' href='user/add_user.php'>+ add user</a></div>";
        $sql = "SELECT * FROM students WHERE active = 1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row		
            $tabla = "<table class = 'addU'>";
            $tabla .= "<tr><th>id</th><th>First Name</th><th>Last name</th><th>phone_number</th>";
            $tabla .= "<th>email</th><th>status</th><th></th></tr>";//header row
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
        $print = "<div class = 'B'><a class = 'B' href='http://localhost/admin/book/search/by_author.php'>By author</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/book/search/by_genre.php'>By genre</a></div>";
        $print .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/book/search/by_title.php'>By Title</a></div>";
        $search = "<div class = 'S'>";
        $search .= "<form action='http://localhost/admin/book/search/by_isbn.php' method='post' id='form1'>";
        $search .= "<label for='isbn'>ISBN</label>";
        $search .= "<input class= 'S' type=text name='isbn'>";
        $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
        $search .= "</form>";
        echo $search;
        echo $print;
        echo "<div class = 'add' ><a class = 'add' href='book/add_book.php'>+add book </a></div>";
        $sql = "SELECT id_isbn FROM books WHERE copy_number >= 1 ;";
        $result = $conn->query($sql);
        $array_isbn = array();
        while($row = $result->fetch_assoc()){
            array_push($array_isbn, $row["id_isbn"]);
        }
        $tabla = "<table>";
        $tabla .= "<tr><th>ISBN</th><th>Title</th><th>Dewey Code</th>";
        $tabla .= "<th>Author</th><th>Publisher</th><th>Genre</th>";
        $tabla .= "<th>Number of copies</th><th>Available copies</th><th></th></tr>";
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
        $search = "<div class = 'S'>";
        $search .= "<form action='loan-fee/search_loan.php' method='post' id='form1'>";
        $search .= "<label for='id'>ID</label>";
        $search .= "<input class= 'S' type=text name='id'>";
        $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
        $search .= "</form>";
        echo $search;
        $sql = "SELECT loans.id_students, loans.id_loan, loans.id_isbn, loans.date_out, loans.date_sin, students.first_name, students.last_name, students.phone_number from loans join students ON loans.id_students = students.id_students AND loans.active = 1 ORDER BY loans.date_sin DESC;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row		
            $tabla = "<table class = 'S'>";
            $tabla .= "<tr><th>id</th><th>Name</th><th>phone</th><th>ISBN</th><th>Date borrowed</th><th></th>";
            $tabla .= "<th>Date to return</th></tr>";//header row
            while($row = $result->fetch_assoc()) {
                $tabla .= "<tr>";
                $tabla .= "<td>" . $row["id_students"] . "</td>";
                $tabla .= "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                $tabla .= "<td>" . $row["phone_number"] . "</td>";
                $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                $tabla .= "<td>" . $row["date_out"] . "</td>";
                $tabla .= "<td>" . $row["date_sin"] . "</td>";
                $tabla .= "<td><a href='http://localhost/admin/loan-fee/return_loan_submit.php?acc=" . $row["id_loan"]  . "'>Return</a></td>";
                $tabla .= "</tr>";
            }
            $tabla .= "</table>";
            echo $tabla;
        } else {
            echo "<div class = 'Ws'>There are no active loans at the moment<div> ";
        }
    }
    
    function fees($conn){
        $c = 0;
        $search = "<div class = 'S'>";
        $search .= "<form action='loan-fee/search_fee.php' method='post' id='form1'>";
        $search .= "<label for='id_students'>ID</label>";
        $search .= "<input class= 'S' type=text name='id_students'>";
        $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
        $search .= "</form>";
        echo $search;
        //print active fees
        $tablaf = "<table class = 'S'>";
        $tablaf .= "<tr><th>id</th><th>Name</th><th>Phone</th><th>ISBN</th>";
        $tablaf .= "<th>Due date</th><th>Returned date</th><th>Fee</th><th></th></tr>";//header row
        $sql = "SELECT fees.id_fee, fees.ammount, loans.id_isbn, loans.date_sin, loans.date_rin, loans.date_out, students.first_name, students.last_name, students.phone_number, students.id_students FROM fees JOIN loans ON fees.id_loan = loans.id_loan AND fees.active = 1 JOIN students ON loans.id_students = students.id_students;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tablaf .= "<tr>";
                $tablaf .= "<td>" . $row["id_students"] . "</td>";
                $tablaf .= "<td>" . $row["first_name"] . " ". $row["last_name"] . "</td>";
                $tablaf .= "<td>" . $row["phone_number"] . "</td>";
                $tablaf .= "<td>" . $row["id_isbn"] . "</td>";
                $tablaf .= "<td>" . $row["date_sin"] . "</td>";
                $tablaf .= "<td>" . $row["date_rin"] . "</td>";
                $tablaf .= "<td>$" . $row["ammount"] . "</td>";
                $tablaf .= "<td><a href='http://localhost/admin/loan-fee/pay_fee.php?acc=" . $row["id_fee"]  . "'>Pay fee</a></td>";
                $tablaf .= "</tr>";
            }
            $c = $c + 1;
        }
        //print late returns
        $sql = "SELECT loans.id_isbn, loans.id_loan, loans.date_sin, students.first_name, students.last_name, students.phone_number, students.id_students FROM loans JOIN students ON students.id_students = loans.id_students AND loans.active = 1;";
        $result = $conn->query($sql);
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
                $tablaf .= "<td>" . $row["id_students"] . "</td>";
                $tablaf .= "<td>" . $row["first_name"] . " " . $row["last_name"] ."</td>";
                $tablaf .= "<td>" . $row["phone_number"] . "</td>";
                $tablaf .= "<td>" . $row["id_isbn"] . "</td>";
                $tablaf .= "<td>" . $row["date_sin"] . "</td>";
                $tablaf .= "<td>Not returned yet</td>";
                $tablaf .= "<td>$" . $ammount . "</td>";
                $tablaf .= "<td><a href='http://localhost/admin/loan-fee/return_loan_submit.php?acc=" . $row["id_loan"]  . "'>Return book</a></td>";
                $tablaf .= "</tr>";
                $c = $c+1;
            }
        }
        if($c == 0){
            echo "<div class = 'Ws'>There are no fees at the moment</div>";
        }else{
            $tablaf .= "</table>";
            echo $tablaf;
        }
    }

    function ploans($conn){
        //active
        echo "<div class = 'Bsi'><h3>Active loans</h3>";
        $id = $_SESSION["id"];
        $sql = "SELECT * FROM loans WHERE id_students = $id AND active = 1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output 
            $tabla = "<table class = 'Bsi'>";
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
        //inactive
        echo "<h3>History of loans</h3>";
        $id = $_SESSION["id"];
        $sql = "SELECT books.id_isbn, books.title, loans.date_out, loans.date_sin, loans.date_rin FROM books JOIN loans ON books.id_isbn = loans.id_isbn AND loans.active = 0 AND loans.id_students = $id AND loans.id_admin_out != 0;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $tabla = "<table class = 'Bsi'>";
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
            $tabla .= "</table></div>";
            echo $tabla;

        }else{
            echo "<div class = 'W'>You don't have a history of borrowed books yet</div></div>";
        }
    }

    function pfees($conn){
        //active
        echo "<div class = 'Bsi'><h3>Active Fees</h3>";
        $id = $_SESSION["id"];
        $c = 0;
        $tablaf = "<table class = 'Bsi'>";
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
            echo "<div class = 'W'>You do not have any fees to pay :)</div>";
        }else{
            echo $tablaf;
        }
        //history 
        echo "<h3>History of fees</h3>";
        $id = $_SESSION["id"];
        $sql = "SELECT books.id_isbn, books.title, loans.date_out, loans.date_sin, loans.date_rin, fees.date_paid, fees.ammount FROM books JOIN loans ON books.id_isbn = loans.id_isbn JOIN fees ON loans.id_loan = fees.id_loan AND loans.active = 0 AND loans.id_students = $id;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $tabla = "<table class = 'Bsi'>";
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
            $tabla .= "</table></div>";
            echo $tabla;

        }else{
            echo "<div class = 'W'>You don´t have a history of fees </div></div>";
        }

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
            }else if($opcion == "5"){
                $menu = "<div class = 'Bs'><div class = 'B'><a class = 'B' href='http://localhost/admin/main_admin.php'>Top</a></div>";
                $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=5'>My loans</a></div>";
                $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=6'>My Fees</a></div></div>";
                echo $menu;
                ploans($conn);
            }else if($opcion == "6"){
                $menu = "<div class = 'Bs'><div class = 'B'><a class = 'B' href='http://localhost/admin/main_admin.php'>Top</a></div>";
                $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=5'>My loans</a></div>";
                $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=6'>My Fees</a></div></div>";
                echo $menu;
                pfees($conn);
            }else{
                echo "what are you doing?";
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