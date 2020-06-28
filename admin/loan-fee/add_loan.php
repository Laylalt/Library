<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Add loan</title>
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
                    $sql = "SELECT active, id_isbn FROM loans WHERE id_students = $id_students AND active = 1;";
                    $result = $conn->query($sql);
                    if($result->num_rows <= 1){
                        //check id_isbn of active fee(3.1)
                        if($result->num_rows == 1){
                            $row = $result->fetch_assoc();
                            if($row["id_isbn"] == $id_isbn){
                                echo "<div class = 'HW'>were sorry but this user has that book already, please borrow a different book</div>";
                                return 0;
                            }
                        }
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
                                    $availability = $availability - 1;
                                    $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $id_isbn;";
                                    if($conn->query($sql) == TRUE){
                                        return 1;
                                    }else{
                                        echo "error updating data, please try again later";
                                        return 0;
                                    }
                                }else{
                                    echo "<div class = 'HW'>were sorry but this user had a fee less than 7 days ago, please wait before borrowing other book</div>";
                                    return 0;
                                }

                            }else{
                                $availability = $availability - 1;
                                $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $id_isbn;";
                                if($conn->query($sql) == TRUE){
                                    return 1;
                                }else{
                                    return 0;
                                }
                            }
                            
                        }else{
                            echo "<div class = 'HW'>Were sorry but this student has unpaid fees :(</div>";
                            return 0;    
                        }
                    }else{
                        echo "<div class = 'HW'>Were sorry but this student already has already borrowed 2 books :(</div>";
                        return 0;
                    }
                }else{
                    echo "<div class = 'HW'>Were sorry but we don't have enough copies of this book, please wait a few days :(</div>";
                    return 0;
                }
            }else{
                echo "<div class = 'HW'>No book with that ISBN please try again</div>";
                return 0;
            }
        }else{
            echo "<div class = 'HW'>student not found in database please try again</div>";
            return 0;    
        }
    }
?>
<?php
    $x = check();
    if(isset($x) && $x == 0){
        head();
        $conn = connect();
        //get requested values
        if(isset($_REQUEST["al"])){
            $id_students = $_POST["id_students"];
            $id_isbn = $_POST["id_isbn"];
            $v = validation($id_students, $id_isbn, $conn);
            if($v == 1){
                //go to submit
                $send = $id_students . "-" . $id_isbn;
                ob_clean();
                header("Location: http://localhost/admin/loan-fee/add_loan_submit.php?acc=". $send);
                $conn->close();
                exit();
            }else{
                $table = "<div class = 'F'>";
                $table .= "<form action='' method='post' id='forml'><br> ";
                $table .= "<label for='id_students'>ID:</label>";
                $table .= "<input type=text name='id_students'><br>";
                $table .= "<label for='id_isbn'>ISBN:</label>";
                $table .= "<input type=text name='id_isbn'><br>";
                $table .= "<button type='submit' form='forml' value='submit' name='al'>Submit</button>";
                $table .= "</form>";
                $table .= "</div>";
                echo $table;
                    
            }
            $conn->close();   
        }else{
            $table = "<div class = 'F'>";
            $table .= "<form action='' method='post' id='forml'><br> ";
            $table .= "<label for='id_students'>ID:</label>";
            $table .= "<input type=text name='id_students'><br>";
            $table .= "<label for='id_isbn'>ISBN:</label>";
            $table .= "<input type=text name='id_isbn'><br>";
            $table .= "<button type='submit' form='forml' value='submit' name='al'>Submit</button>";
            $table .= "</form>";
            $table .= "</div>";
            echo $table;
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
    }
?>    
</body>
</html>