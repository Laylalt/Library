<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>
</head>
<body>
    <p><a href="http://localhost/library.php?acc=1"><--Go back</a></p>
    <?php
        //conecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
        //end conecting to database
        if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone_number"]) && isset($_POST["email"]) && isset($_POST["password_user"])){
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $phone_number = $_POST["phone_number"];
            $email = $_POST["email"];
            $password_user = $_POST["password_user"];
            $active = 1;
            $sql = "INSERT INTO students (first_name, last_name, phone_number, email, password_user, active) VALUES('$first_name','$last_name','$phone_number','$email', '$password_user', $active)";
            if($conn->query($sql) === TRUE){
                echo "New student added";
                $sql = "SELECT * FROM students ORDER BY id_students DESC LIMIT 1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $tabla = "<table>";
                        $tabla .= "<tr><td>id</td><td>First Name</td><td>Last name</td><td>phone_number</td>";
                        $tabla .= "<td>email</td><td>status</td></tr>";
                        $tabla .= "<tr>";
                        $tabla .= "<td>" . $row["id_students"] . "</td>";
                        $tabla .= "<td>" . $row["first_name"] . "</td>";
                        $tabla .= "<td>" . $row["last_name"] . "</td>";
                        $tabla .= "<td>" . $row["phone_number"] . "</td>";
                        $tabla .= "<td>" . $row["email"] . "</td>";
                        $tabla .= "<td>" . $row["active"] . "</td>";
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

    ?>
</body>
</html>