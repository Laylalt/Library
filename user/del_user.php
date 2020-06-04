<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<p><a href="http://localhost/library.php?acc=1"><--Go back</a></p>
    <?php //conecting to database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error); 
        }
    ?>
    
    <!--js with alert to make sure-->

    <?php //Deleting the book 
        if(isset($_GET["i"])){
            $id = $_GET["i"];
            $sql = "DELETE FROM students WHERE id_students = $id";
            if ($conn->query($sql) === TRUE) {
                echo "student deleted succesfully";
            } else {
                echo "Error deleting record" . $conn->error;
            }
              $conn->close();
        }//end of if(issset(GET))
        #when trying to delete with isbn we cant cus its foreign key of certain tables. 
        #when i tried with dewey code it showed succesfull but my database did not change 
    ?>
</body>
</html>