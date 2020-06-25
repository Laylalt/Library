<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
         $servername = "localhost";
         $username = "root";
         $password = "";
         $conn = new mysqli($servername, $username, $password, "library");
         if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
        $sql = "SELECT date_out FROM loans WHERE id_loan = 9;"; // 2020-06-13
        $result = $conn->query($sql);
        if($result->num_rows <= 1){
            $row = $result->fetch_assoc();
            $due_dateo = $row["date_out"];
            $todayo = date("Y-m-d");
            if($todayo > $due_dateo){
                $today = strtotime(date("Y-m-d")) - (6*60*60); ////ADELANTADO 6 HRS
                $due_date = strtotime($due_dateo);
                echo intval(($today - $due_date)/60/60/24);        
            }else{
                echo "no te pasaste";
            }
        } 
        //calculating ammount for fees
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
        //------------------------------------
        for($x = 0; $x < 4; $x++){
            for($i = 0; $i < count($a_id_c); $i++){
                $n_loans = explode("-", $a_id_c[$i]);
                if($max < $n_loans[1]){
                    $max = $a_id_c[$i];
                }
            }
            //push that max value to ordered array
            array_push($a_order, $max);
            echo $a_order[$x];
            //see number key of that value to delete it from the a_id_c array
            $key = array_search($max, $a_id_c);
            unset($a_id_c[$key]);
            $max = 0;
        }
                    
    ?>
</body>
</html>