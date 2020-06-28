
<?php
    require_once('../func.php');
    $x = check();
    function loans($conn, $id){
       $sql =  "SELECT id_loan FROM loans WHERE active = 1 AND id_students = $id;";
       $result = $conn->query($sql);
       if ($result->num_rows > 0){
            $loans = $result->num_rows;
            return $loans;
        }else{
            return 0;
        }
    }
        
    function fees($conn, $id){
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
        //count active fees
        $sql = "SELECT id_fee FROM fees WHERE active = 1 and id_students = $id;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $nfees = $result->num_rows;
            return $fees + $nfees;
        }
        return $fees;
    }
    if(isset($x) && $x == 0){  
?>
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
        head();
        $conn = connect();
        $id = $_SESSION["id"];
        $loans = loans($conn, $id);
        $fees = fees($conn, $id);
        $menu = "<div class = 'Bs'><div class = 'B'><a class = 'B' href='http://localhost/admin/main_admin.php'>Top</a></div>";
        $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=5'>My loans (" . $loans .")</a></div>";
        $menu .= "<div class = 'B'><a class = 'B' href='http://localhost/admin/library_admin.php?acc=6'>My Fees (" . $fees . ")</a></div></div>";
        echo $menu;
        top_main_s($conn);
    ?>
</body>
</html>
<?php
    }else{
?>
<p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php
    }
?>