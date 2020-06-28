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
        $search = "<div class = 'S'>";
        $search .= "<form action='http://localhost/admin/user/search_user.php' method='post' id='form1'>";
        $search .= "<label for='isbn'>ID</label>";
        $search .= "<input class= 'S' type=text name='id'>";
        $search .= "<button class= 'S' type='submit' form='form1' value='submit' name='sbmt'>search</button></div>";
        $search .= "</form>";
        echo $search;
        if (isset($_POST["id"])){
            $conn = connect();
            $id = $_POST["id"];
            $sql = "SELECT * FROM students WHERE active = 1 and id_students = $id;";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row		
                $tabla = "<table class = 'S'>";
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
                echo "<div class = 'Ws'>No user found with this ID</div>";
                $sql = "SELECT * FROM students WHERE active = 1;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row		
                    $tabla = "<table class = 'S'>";
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