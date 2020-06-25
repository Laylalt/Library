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
            $conn = connect();
            head();
            $print = "<p><a href='http://localhost/admin/library_admin.php?acc=1'><--Go back</a></p>";
            echo $print;
            //-----------------------------------------------------------------------------------------------
            if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone_number"]) && isset($_POST["email"]) && isset($_POST["password_user"]) && isset($_POST["type"])){
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $phone_number = $_POST["phone_number"];
                $email = $_POST["email"];
                $password_user = $_POST["password_user"];
                $active = 1;
                $type = $_POST["type"];
                $sql = "INSERT INTO students (first_name, last_name, phone_number, email, password_user, active, type) VALUES('$first_name','$last_name','$phone_number','$email', '$password_user', $active, $type)";
                if($conn->query($sql) === TRUE){
                    echo "<div class = 'W'>New student added</div>";
                    $sql = "SELECT * FROM students ORDER BY id_students DESC LIMIT 1";
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
                    echo "Error updating data";
                }
            }else{
                echo "failed to receive data";
            }
        }else{
    ?>
            <p>you don't have authorization to acces this page, please <a href="http://localhost/">log in</a></p> 
    <?php
            }
        
    ?>
</body>
</html>