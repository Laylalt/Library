<?php
    if (isset($_GET["acc"]) && $_GET["acc"] == 0){
        logoff();
    }
    
    function logoff(){
        session_start();
        session_destroy();
        ob_clean();
        header("Location: http://localhost/");//send user to this page (user is admin cus type = 0)
        exit();
    }

    function check(){
        session_start();
        if(isset($_SESSION["type"])){
            return $_SESSION["type"];
        }else{
            return NULL;
        }
    }

    function connect(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return NULL; 
        }else{
            return $conn;
        }
    }

    function head(){
        if(isset($_SESSION["type"])){
            $conn = connect();
            check_r($conn);
            $print = "<div class='H'>";
            if($_SESSION["type"] == 0){
                $_SESSION["fees"] = check4fees($conn);
                $rbooks = ar_books($conn);
                $print .= "<a href='http://localhost/admin/main_admin.php'><img src='/img/logo.png'   height='150px'></a>";
                $print .= "<a class='Hi' href='http://localhost/profile/see_profile.php'>Hi " . $_SESSION["first_name"] . "!</a>";
                $print .= "<h4><a class='H' href='http://localhost/admin/loan-fee/add_loan.php'>Add Loan</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=2'>Active Loans</a>";
                $print .="<a class='H' href='http://localhost/admin/loan-fee/pending_loan.php'>Requested books (" . $rbooks . ")</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=4'>Fees (" . $_SESSION["fees"] . ")</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=1'>Users</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=3'>Books</a></h4>";
                $print .= "<span><a class='H' href='http://localhost/func.php?acc=0'>Log Off</a></span>";
                $print .= "</div>";
                
            }else if($_SESSION["type"] == 1){
                $aloans =  sa_loans($conn);
                $afees = sa_fees($conn);
                $rbooks = sr_books($conn);
                $print .= "<a href='http://localhost/student/main_student.php'><img src='/img/logo.png'   height='150px'></a>";
                $print .= "<a class='Hi' href='http://localhost/profile/see_profile.php'>Hi " . $_SESSION["first_name"] . "!</a>";
                $print .= "<h4><a class='H' href='http://localhost/student/library_student.php?acc=3'>Books</a>";
                $print .= "<a class='H' href='http://localhost/student/library_student.php?acc=6'>Requested Books (" . $rbooks . ")</a>";
                $print .= "<a class='H' href='http://localhost/student/library_student.php?acc=4'>Active Loans (" . $aloans . ")</a>";
                $print .= "<a class='H' href='http://localhost/student/library_student.php?acc=5'>Active Fees (" .  $afees . ")</a>";
                $print .= "<a class='H' href='http://localhost/student/library_student.php?acc=1'>History of loans</a>";
                $print .= "<a class='H' href='http://localhost/student/library_student.php?acc=2'>History of fees</a></h4>";
                $print .= "<span><a class='H' href='http://localhost/func.php?acc=0'>Log Off</a></span>";
                $print .= "</div>";
            }
            echo $print;
        }
    }

    function check4fees($conn){
        $sql = "SELECT id_loan, date_sin FROM loans WHERE active = 1;";
        $array_loan = array();
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            $today = date("Y-m-d");
            $due_date = $row["date_sin"];
            if($today > $due_date){ //cheking if a loan is  late
                array_push($array_loan, $row["id_loan"]);
            }
        }
        //put late loans in  session variable
        $fees = count($array_loan);
        //count active fees
        $sql = "SELECT id_fee FROM fees WHERE active = 1;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $nfees = $result->num_rows;
            return $fees + $nfees;
        }
        return $fees;
    }

    function ar_books($conn){
        $sql = "SELECT id_loan FROM loans WHERE  active = 0 AND id_admin_out = 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $c = $result->num_rows;
            return $c;
        }else{
            return 0;
        }
    }

    function sr_books($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT id_loan FROM loans WHERE id_students = $id AND active = 0 AND id_admin_out = 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $c = $result->num_rows;
            return $c;
        }else{
            return 0;
        }
    }

    function sa_loans($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT id_loan FROM loans WHERE id_students = $id AND active = 1;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $c = $result->num_rows;
            return $c;
        }else{
            return 0;
        }
    }

    function sa_fees($conn){
        $id = $_SESSION["id"];
        $sql = "SELECT id_fee FROM fees WHERE active = 1 AND id_students = $id;";
        $result = $conn->query($sql);
        $c = 0;
        $fees = 0;
        if($result->num_rows > 0){
            $c = $result->num_rows;
        }
        $sql = "SELECT id_loan, date_sin FROM loans WHERE active = 1 AND id_students = $id;";
        $array_loan = array();
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            $today = date("Y-m-d");
            $due_date = $row["date_sin"];
            if($today > $due_date){ //cheking if a loan is  late
                array_push($array_loan, $row["id_loan"]);
            }
        }
        //put late loans in  session variable
        $fees = count($array_loan);
        return $fees + $c;
    }

    function printbook($conn, $isbn, $v){
        $sql = "SELECT * FROM books WHERE id_isbn = $isbn;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
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
            $tabla = "<tr>";
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
            return $tabla;
        }else{
            $tabla = NULL;
            return $tabla;
        }  
    }

    function sval($conn, $id){
        $c = 0;
        //how many books this person has borrowed 
        $sql = "SELECT active FROM loans WHERE id_students = $id AND active = 1;";
        $result = $conn->query($sql);
        if($result->num_rows <= 1){
            if($result->num_rows == 1){
                $c = $c + 1;
            }
            //if student has an unpaid fee 
            $sql = "SELECT active FROM fees WHERE id_students = $id AND active = 1;";
            $result = $conn->query($sql);
            if($result->num_rows < 1){
                //check date of last fee
                $sql = "SELECT date_paid FROM fees WHERE id_students = $id ORDER BY date_paid DESC LIMIT 1;";
                $result = $conn->query($sql);    
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $today = strtotime(date("Y-m-d")); 
                    $date_paid = strtotime($row["date_paid"]);
                    $days = ($today - $date_paid)/60/60/24;
                    if($days >= 7){
                        //check how many borrowed books
                        $sql = "SELECT id_loan FROM loans WHERE id_students = $id AND active = 0 AND id_admin_out = 0;";
                        $result = $conn->query($sql);    
                        if($result->num_rows <= 1){
                            if($result->num_rows == 1){
                                $c = $c + 1;
                            }
                            if($c == 2){
                                echo "<div class = 'W'>You have 1 request and 1 active loan, please return the book to be able to request another one</div>";
                                return 0;
                            }else{
                                return 1;
                            }
                        }else{
                            echo "<div class = 'W'>You already requested 2 books, please wait to be able to request a book</div>";
                            return 0;
                        }
                    }else{
                        echo "<div class = 'W'>You had a fee no less than 7 days ago, please wait to be able to request a book</div>";
                        return 0;
                    }
                }else{
                    //check if it has a borrowed book and a request
                    $sql = "SELECT id_loan FROM loans WHERE id_students = $id AND active = 0 AND id_admin_out = 0;";
                    $result = $conn->query($sql);    
                    if($result->num_rows <= 1){
                        if($result->num_rows == 1){
                            $c = $c + 1;
                        }
                        if($c == 2){
                            echo "<div class = 'W'>You have 1 request and 1 active loan, please return the book to be able to request another one</div>";
                            return 0;
                        }else{
                            return 1;
                        }
                    }
                }
            }else{
                echo "<div class = 'W'>You have unpaid fees, please pay them to be able to request a book</div>";   
                return 0; 
            }
        }else{
            echo "<div class = 'W'>You already have 2 borrowed books, please return one to be able to request a book</div>";
            return 0;
        }
    }
     
    function check_r($conn){
        $sql = "SELECT date_out, id_loan, id_isbn FROM loans WHERE active = 0 AND id_admin_out = 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $today = date("Y-m-d");
                $out_date = $row["date_out"];
                $today = strtotime($today);
                $out_date = strtotime($out_date);
                $days = intval(($today - $out_date)/60/60/24);
                if($days >= 2){
                    $id_loan = $row["id_loan"];
                    $isbn = $row["id_isbn"];
                    $sql = "SELECT availability FROM books WHERE id_isbn = $isbn";
                    if($conn->query($sql) == TRUE){
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $availability = $row["availability"] + 1;
                    }
                    $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $isbn;";
                    if($conn->query($sql) == TRUE){
                        $sql = "DELETE FROM loans WHERE id_loan = $id_loan;";
                        if ($conn->query($sql) === TRUE){
                            return 1;
                        }
                    }
                }
            }
        }
    }

    function top_main_s($conn){
        //array of id_students--------------------------------------------------------------------------
        $a_id = array();
        $sql = "SELECT id_students FROM students WHERE type = 1;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            array_push($a_id, $row["id_students"]);
        }
        //see number of loans of every student
        $a_id_c = array();
        $count_loans = 0;
        for($x = 0; $x < count($a_id); $x++){
            $sql = "SELECT id_loan FROM loans WHERE id_students = $a_id[$x];";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $count_loans = $count_loans + 1;
            }
            $r = $a_id[$x] . "-" . $count_loans;
            array_push($a_id_c, $r);
            $count_loans = 0;
        }
        //order from max to min in an array
        $a_order = array();
        for($x = 0; $x < count($a_id) ; $x++){
            $max = 0;
            $holder = 0 . "-" . 0;
            $f = count($a_id_c);
            for($i = 0; $i < $f; $i++){
                $n_loans = explode("-", $a_id_c[$i]);
                $compare = $n_loans[1];
                if($compare > $max){
                    $max = $compare;
                    $holder = $a_id_c[$i];
                }
            }
            $key = array_search($holder, $a_id_c);
            $a_id_c[$key] = 0 . "-" . 0;
            array_push($a_order, $holder);
        }
        //$a_order has ordered top students, printing
        $print = "<div class = 'top'><table class = 'top'><tr><td class = 'top' colspan='3'>Top 5 Students</td></tr>";
        $print .= "<tr><th>ID</th><th>Name</th><th>Loans</th></tr>";
        for($x = 0; $x < 5  ; $x++){
            $string = explode("-", $a_order[$x]);
            if($string[0] != 0){
                $id_students = $string[0];
                $n_loans = $string[1];
                $sql = "SELECT first_name, last_name FROM students WHERE id_students = $id_students;";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    $print .= "<tr>";
                    $print .= "<td>" . $id_students . "</td>";
                    $print .= "<td>" . $row["first_name"] . " " . $row["last_name"]  . "</td>";
                    $print .= "<td>" . $n_loans . "</td>";
                    $print .= "</tr>";
                }

            }
            
        }
        $print .= "</table>";
        //array of id_boooks--------------------------------------------------------------------------
        $a_isbn = array();
        $sql = "SELECT id_isbn FROM books;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            array_push($a_isbn, $row["id_isbn"]);
        }
        //see number of loans of every book
        $a_id_c = array();
        $count_loans = 0;
        for($x = 0; $x < count($a_isbn); $x++){
            $sql = "SELECT id_isbn FROM loans WHERE id_isbn = $a_isbn[$x];";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $count_loans = $count_loans + 1;
            }
            $r = $a_isbn[$x] . "-" . $count_loans;
            array_push($a_id_c, $r);
            $count_loans = 0;
        }
        //order from max to min in an array
        $a_order = array();
        for($x = 0; $x < count($a_isbn) ; $x++){
            $max = 0;
            $holder = 0 . "-" . 0;
            $f = count($a_id_c);
            for($i = 0; $i < $f; $i++){
                $n_loans = explode("-", $a_id_c[$i]);
                $compare = $n_loans[1];
                if($compare > $max){
                    $max = $compare;
                    $holder = $a_id_c[$i];
                }
            }
            $key = array_search($holder, $a_id_c);
            $a_id_c[$key] = 0 . "-" . 0;
            array_push($a_order, $holder);
        }
        //$a_order has ordered top book, printing
        $print .= "<table class = 'top' id = 'books'><tr><td colspan='3' class = 'top'>Top Five books</td></tr>";
        $print .= "<tr><th>ISBN</th><th>Title</th><th>Loans</th></tr>";
        for($x = 0; $x < 5  ; $x++){
            $string = explode("-", $a_order[$x]);
            if($string[0] != 0){
                $id_isbn = $string[0];
                $n_loans = $string[1];
                $sql = "SELECT title FROM books WHERE id_isbn = $id_isbn;";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    $print .= "<tr>";
                    $print .= "<td>" . $id_isbn . "</td>";
                    $print .= "<td>" . $row["title"]  . "</td>";
                    $print .= "<td>" . $n_loans . "</td>";
                    $print .= "</tr>";
                }

            }
            
        }
        $print .= "</table></div>";
        echo $print;
        

    }
    
    function top_main_a($conn){
        //array of id_students--------------------------------------------------------------------------
        $a_id = array();
        $sql = "SELECT id_students FROM students WHERE type = 1;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            array_push($a_id, $row["id_students"]);
        }
        //see number of loans of every student
        $a_id_c = array();
        $count_loans = 0;
        for($x = 0; $x < count($a_id); $x++){
            $sql = "SELECT id_loan FROM loans WHERE id_students = $a_id[$x];";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $count_loans = $count_loans + 1;
            }
            $r = $a_id[$x] . "-" . $count_loans;
            array_push($a_id_c, $r);
            $count_loans = 0;
        }
        //order from max to min in an array
        $a_order = array();
        for($x = 0; $x < count($a_id) ; $x++){
            $max = 0;
            $holder = 0 . "-" . 0;
            $f = count($a_id_c);
            for($i = 0; $i < $f; $i++){
                $n_loans = explode("-", $a_id_c[$i]);
                $compare = $n_loans[1];
                if($compare > $max){
                    $max = $compare;
                    $holder = $a_id_c[$i];
                }
            }
            $key = array_search($holder, $a_id_c);
            $a_id_c[$key] = 0 . "-" . 0;
            array_push($a_order, $holder);
        }
        //$a_order has ordered top students, printing
        $print = "<div class = 'border'><table class = 'top'><tr><td class = 'top' colspan='3'>Top 5 Students</td></tr>";
        $print .= "<tr><th>ID</th><th>Name</th><th>Loans</th></tr>";
        for($x = 0; $x < 5  ; $x++){
            $string = explode("-", $a_order[$x]);
            if($string[0] != 0){
                $id_students = $string[0];
                $n_loans = $string[1];
                $sql = "SELECT first_name, last_name FROM students WHERE id_students = $id_students;";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    $print .= "<tr>";
                    $print .= "<td>" . $id_students . "</td>";
                    $print .= "<td>" . $row["first_name"] . " " . $row["last_name"]  . "</td>";
                    $print .= "<td>" . $n_loans . "</td>";
                    $print .= "</tr>";
                }

            }
            
        }
        $print .= "</table>";
        //array of id_boooks--------------------------------------------------------------------------
        $a_isbn = array();
        $sql = "SELECT id_isbn FROM books;";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            array_push($a_isbn, $row["id_isbn"]);
        }
        //see number of loans of every book
        $a_id_c = array();
        $count_loans = 0;
        for($x = 0; $x < count($a_isbn); $x++){
            $sql = "SELECT id_isbn FROM loans WHERE id_isbn = $a_isbn[$x];";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $count_loans = $count_loans + 1;
            }
            $r = $a_isbn[$x] . "-" . $count_loans;
            array_push($a_id_c, $r);
            $count_loans = 0;
        }
        //order from max to min in an array
        $a_order = array();
        for($x = 0; $x < count($a_isbn) ; $x++){
            $max = 0;
            $holder = 0 . "-" . 0;
            $f = count($a_id_c);
            for($i = 0; $i < $f; $i++){
                $n_loans = explode("-", $a_id_c[$i]);
                $compare = $n_loans[1];
                if($compare > $max){
                    $max = $compare;
                    $holder = $a_id_c[$i];
                }
            }
            $key = array_search($holder, $a_id_c);
            $a_id_c[$key] = 0 . "-" . 0;
            array_push($a_order, $holder);
        }
        //$a_order has ordered top book, printing
        $print .= "<table class = 'top' id = 'books'><tr><td colspan='3' class = 'top'>Top Five books</td></tr>";
        $print .= "<tr><th>ISBN</th><th>Title</th><th>Loans</th></tr>";
        for($x = 0; $x < 5  ; $x++){
            $string = explode("-", $a_order[$x]);
            if($string[0] != 0){
                $id_isbn = $string[0];
                $n_loans = $string[1];
                $sql = "SELECT title FROM books WHERE id_isbn = $id_isbn;";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    $print .= "<tr>";
                    $print .= "<td>" . $id_isbn . "</td>";
                    $print .= "<td>" . $row["title"]  . "</td>";
                    $print .= "<td>" . $n_loans . "</td>";
                    $print .= "</tr>";
                }

            }
            
        }
        $print .= "</table></div>";
        echo $print;
        

    }
    
