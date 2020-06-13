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
                                $today = strtotime(date("Y-m-d")) - (6*60*60); ////ADELANTADO 6 HRS; 
                                $days = $today - $row["date_paid"]/60/60/24;
                                if($days >= 7){
                                    $availability = $availability - 1;
                                    $sql = "UPDATE books SET availability = $availability WHERE id_isbn = $id_isbn;";
                                    if($conn->query($sql) == TRUE){
                                        return 1;
                                    }else{
                                        return 0;
                                    }
                                    
                                }else{
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
                            echo "<div>Were sorry but this student has unpaid fees :(</div>";
                            return 0;    
                        }
                    }else{
                        echo "<div>Were sorry but this student already has already borrowed 2 books :(</div>";
                        return 0;
                    }
                }else{
                    echo "<div>Were sorry but we don't have enough copies of this book, please wait a few days :(</div>";
                    return 0;
                }
            }else{
                echo "<div>No book with that ISBN please try again</div>";
                return 0;
            }
        }else{
            echo "<div>student not found in database please try again</div>";
            return 0;    
        }
    }
?>
<?php
    $x = check();
    if(isset($x) && $x == 0){
        head();
        echo "<p><a href='http://localhost/admin/library_admin.php?acc=2'><--Go back</a></p>";
        $conn = connect();
        $tabla = "<div>";
        $tabla .= "<form action='' method='post' id='form1'><br> ";
        $tabla .= "<label for='id_students'>Id student:</label>";
        $tabla .= "<input type=text name='id_students'><br>";
        $tabla .= "<label for='id_isbn'>ISBN:</label>";
        $tabla .= "<input type=text name='id_isbn'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        //$tabla .= "<button><a href='http://localhost/admin/library_admin.php?acc=2'>cancel</a></button>";
        $tabla .= "</div>";
        echo $tabla;
        //get requested values
        if(isset($_REQUEST["sbmt"])){
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
            }     
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
    }
?>    
</body>
</html>