<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <p><a href="http://localhost/library.php?acc=1"><--Go back</a></p>
    <?php 
    //conecting to database
    require_once('../../func.php');
    $x = check();
    if(isset($x) && $x == 0){
        $conn = connect();
        head();
        $print = "<p><a href='http://localhost/admin/library_admin.php?acc=1'><--Go back</a></p>";
        echo $print;
    //-------------------------------------------------------------------------------------------------------------------------
        if(isset($_POST["id"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone_number"]) && isset($_POST["email"]) && isset($_POST["password_user"]) && isset($_POST["active"]) && isset($_POST["type"])){
            $id = $_POST["id"];
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $phone_number = $_POST["phone_number"];
            $email = $_POST["email"];
            $password_user = $_POST["password_user"];
            $active = $_POST["active"];
            $type = $_POST["type"];
            $sql = "UPDATE students SET first_name = '$first_name' WHERE id_students = $id";
            if($conn->query($sql) === TRUE){
                $sql = "UPDATE students SET last_name = '$last_name' WHERE id_students = $id";
                if($conn->query($sql) === TRUE){
                    $sql = "UPDATE students SET phone_number = '$phone_number' WHERE id_students = $id";
                    if($conn->query($sql) === TRUE){
                        $sql = "UPDATE students SET email = '$email' WHERE id_students = $id";
                        if($conn->query($sql) === TRUE){ //last check 
                            $sql = "UPDATE students SET password_user = '$password_user' WHERE id_students = $id";
                            if($conn->query($sql) === TRUE){
                                $sql = "UPDATE students SET active = '$active' WHERE id_students = $id";
                                if($conn->query($sql) === TRUE){
                                    $sql = "UPDATE students SET type = '$type' WHERE id_students = $id";
                                    if($conn->query($sql) === TRUE){
                                    //------------------------------------------------------------------------
                                    echo "<div class = 'W'>student updated to</div>";
                                    $sql = "SELECT * FROM students WHERE id_students = $id";
                                    $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $tabla = "<table>";
                                            $tabla .= "<tr><th>id</th><th>First Name</th><th>Last name</th><th>phone_number</th>";
                                            $tabla .= "<th>email</th><th>status</th><th>Type</th></tr>";
                                            $tabla .= "<tr>";
                                            $tabla .= "<td>" . $row["id_students"] . "</td>";
                                            $tabla .= "<td>" . $row["first_name"] . "</td>";
                                            $tabla .= "<td>" . $row["last_name"] . "</td>";
                                            $tabla .= "<td>" . $row["phone_number"] . "</td>";
                                            $tabla .= "<td>" . $row["email"] . "</td>";
                                            $tabla .= "<td>" . $row["active"] . "</td>";
                                            $tabla .= "<td>" . $row["type"] . "</td>";
                                            $tabla .= "</tr>";
                                            $tabla .= "</table>";
                                            echo $tabla;
                                        } else {
                                            echo "0 results";
                                        }
                                    }else{
                                        echo "Error updating type" . $conn->error;    
                                    }
                                }else{
                                    echo "Error updating status" . $conn->error;
                                }
                            }else{
                                echo "Error updating password" . $conn->error;
                            }
                        }else{
                            echo "Error updating email" . $conn->error; 
                        }
                    }else {
                        echo "Error updating phone number" . $conn->error;
                    }
                }else{
                    echo "Error updating last name" . $conn->error;
                }
            }else{
                echo "Error updating first name" . $conn->error;
            }
        }else{
            echo "error reciving the data";
        }
        
        $conn->close();
    }else{
?>
        <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
<?php 
    }
    ?>
</body>
</html>