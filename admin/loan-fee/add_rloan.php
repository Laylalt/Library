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
    function validation($id_students, $id_isbn, $conn){
        //see if student exists(1)
        $sql = "SELECT id_students FROM students WHERE id_students = $id_students AND active = 1;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
        //number of books in library (2)
            $sql = "SELECT id_isbn, availability FROM books WHERE id_isbn = $id_isbn;";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                if ($row["availability"] > 1){
                    $availability = $row["availability"];
                    //how many books this person has borrowed (3)
                    $sql = "SELECT active FROM loans WHERE id_students = $id_students AND active = 1;";
                    $result = $conn->query($sql);
                    if($result->num_rows <= 1){
                        //if student has an unpaid fee (4)
                        $sql = "SELECT active FROM fees WHERE id_students = $id_students AND active = 1;";
                        $result = $conn->query($sql);
                        if($result->num_rows < 1){
                            //check date of last fee(5) 
                            $sql = "SELECT date_paid FROM fees WHERE id_students = $id_students ORDER BY date_paid DESC LIMIT 1;";
                            $result = $conn->query($sql);    
                            if($result->num_rows > 0){
                                $row = $result->fetch_assoc();
                                $today = strtotime(date("Y-m-d")); 
                                $date_paid = strtotime($row["date_paid"]);
                                $days = ($today - $date_paid)/60/60/24;
                                if($days >= 7){
                                    return 1;
                                }else{
                                    echo "were sorry but this user had a fee less than 7 days ago, please wait before borrowing other book";
                                    return 0;
                                }

                            }else{
                                return 1;
                            }
                            
                        }else{
                            echo "<div class = 'W'>Were sorry but this student has unpaid fees :(</div>";
                            return 0;    
                        }
                    }else{
                        echo "<div class = 'W'>Were sorry but this student already has already borrowed 2 books :(</div>";
                        return 0;
                    }
                }else{
                    echo "<div class = 'W'>Were sorry but we don't have enough copies of this book, please wait a few days :(</div>";
                    return 0;
                }
            }else{
                echo "<div class = 'W'>No book with that ISBN please try again</div>";
                return 0;
            }
        }else{
            echo "<div class = 'W'>student not found in database please try again</div>";
            return 0;    
        }
    }
?>  
<?php
$x = check();
if(isset($x) && $x == 0){
    head();
    $conn = connect();
    if (isset($_GET["i"])){
        $id_loan = $_GET["i"];
        $sql = "SELECT id_students, id_isbn FROM loans WHERE id_loan = $id_loan;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $id_students = $row["id_students"];
            $id_isbn = $row["id_isbn"];
            $v = validation($id_students, $id_isbn, $conn);
            if($v == 1){
                $id_admin_out = $_SESSION["id"];
                $date_out = date("Y-m-d");
                $date_sin = date('Y-m-d', strtotime("+7 days"));
                $sql = "UPDATE loans SET id_admin_out = $id_admin_out, date_out = '$date_out', date_sin = '$date_sin', active = 1 WHERE id_loan = $id_loan;";
                if($conn->query($sql) === TRUE){
                    echo "<div class = 'W'>New loan added</div>";
                    $sql = "SELECT id_students, id_isbn, id_admin_out, date_out, date_sin FROM loans ORDER BY date_out DESC LIMIT 1";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                        $tabla = "<table>";
                        $tabla .= "<tr><th>Borrow date</th><th>Return date</th><th>Id student</th>";
                        $tabla .= "<th>ISBN</th><th>Id admn</th></tr>";//header row
                        $row = $result->fetch_assoc();
                        $tabla .= "<tr>";
                        $tabla .= "<td>" . $row["date_out"] . "</td>";
                        $tabla .= "<td>" . $row["date_sin"] . "</td>";
                        $tabla .= "<td>" . $row["id_students"] . "</td>";
                        $tabla .= "<td>" . $row["id_isbn"] . "</td>";
                        $tabla .= "<td>" . $row["id_admin_out"] . "</td>";
                        $tabla .= "</tr>";
                        $tabla .= "</table>";
                        echo $tabla;
                    }
                }else{
                    echo "error";
                }
            }
        }else{
            echo "error, please contact the website administrator";
        }
    }else{
        echo "what are you doing?";
    }
}else{
    echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
}
?>
</body>
</html>