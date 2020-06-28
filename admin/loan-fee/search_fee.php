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
    require_once('../../func.php');
    $x = check();
    $conn = connect();
    if(isset($x) && $x == 0){
        head();
        $tabla = "<div class = 'S'>";
        $tabla .= "<form action='http://localhost/admin/loan-fee/search_rloan.php' method='post' id='form1'>";
        $tabla .= "<label for='id_students'>ID</label>";
        $tabla .= "<input class = 'S' type=text name='id_students'>";
        $tabla .= "<button class = 'S' type='submit' form='form1' value='submit' name='sbmt'>Search</button>";
        $tabla .= "</form>";
        $tabla .= "</div>";
        echo $tabla;
        if(isset($_POST["id_students"])){
            $id_students = $_POST["id_students"];
            $c = 0;
            $tablaf = "<table class = 'S'>";
            $tablaf .= "<tr><th>id</th><th>Name</th><th>Phone</th><th>ISBN</th>";
            $tablaf .= "<th>Due date</th><th>Returned date</th><th>Fee</th><th></th></tr>";//header row
            $sql = "SELECT fees.id_fee, fees.ammount, loans.id_isbn, loans.date_sin, loans.date_rin, loans.date_out, students.first_name, students.last_name, students.phone_number, students.id_students FROM fees JOIN loans ON fees.id_loan = loans.id_loan AND fees.active = 1 AND fees.id_students = '$id_students' JOIN students ON loans.id_students = students.id_students;";
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
            //late returns
            $sql = "SELECT loans.id_isbn, loans.id_loan, loans.date_sin, students.first_name, students.last_name, students.phone_number, students.id_students FROM loans JOIN students ON students.id_students = loans.id_students AND loans.active = 1 AND loans.id_students = '$id_students';";
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
                    $c = $c + 1;
                }
            }
            if($c == 0){
                echo "<div class = 'Ws'>This student does not have any active fees</div>";
                $c = 0;
                $search = "<div class = 'S'>";
                $search .= "<form action='loan-fee/search_fee.php' method='post' id='form1'>";
                $search .= "<label for='id_students'>ID</label>";
                $search .= "<input class= 'S' type=text name='id_students'>";
                $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
                $search .= "</form>";
                echo $search;
                //print active fees
                $tablaf = "<table>";
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
            }else{
                $tablaf .= "</table>";
                echo $tablaf;
            }
        }else{
            echo "what are you doing here?";
        } 
    }else{
            echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p> ";
        }
    
    ?>
</body>
</html>