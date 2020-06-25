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
    $x = check();
    if(isset($x) && $x == 0){
        head();
        $conn = connect();
        echo "<p><a href='http://localhost/admin/main_admin.php'><--Go back</a></p>";
        $tabla = "<div>";
        $tabla .= "<form action='http://localhost/admin/loan-fee/search_rloan.php' method='post' id='form1'><br> ";
        $tabla .= "<label for='id_students'>id Student:</label>";
        $tabla .= "<input type=text name='id_students'><br>";
        $tabla .= "</form>";
        $tabla .= "<button type='submit' form='form1' value='submit' name='sbmt'>Submit</button>";
        $tabla .= "</div>";
        echo $tabla;
        //all of 'em
        $sql = "SELECT books.id_isbn, books.title, students.id_students, students.first_name, students.last_name, loans.id_loan FROM loans JOIN students ON students.id_students = loans.id_students JOIN books ON books.id_isbn = loans.id_isbn WHERE loans.active = 0 AND loans.id_admin_out = 0;";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $table = "<table>";
            $table .= "<tr><th>ID</th><th>Name</th><th>ISBN</th><th>Title</th></tr>";
            while($row = $result->fetch_assoc()){
                $table .= "<tr>";
                $table .= "<td>" . $row["id_students"] ."</td>";
                $table .= "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                $table .= "<td>" . $row["id_isbn"] ."</td>";
                $table .= "<td>" . $row["title"] ."</td>";
                $table .= "<td><a href='http://localhost/admin/loan-fee/add_rloan.php?i=" . $row["id_loan"] . "'>Complete</a></td>";
                $table .= "</tr>";
            }
            $table .= "</table>";
            echo $table;     
        }else{
            echo "<div class = 'W'>No user has requested a book yet</div>";
        }
    }else{
        echo "<p>you don't have authorization to acces this page, please <a href='http://localhost/'>log in</a></p>";
    }
?>
</body>
</html>