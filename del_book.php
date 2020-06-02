<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
            $isbn = $_GET["i"];
            $sql = "DELETE FROM relationba WHERE id_isbn = $isbn";
            if ($conn->query($sql) === TRUE) {
                $sql = "DELETE FROM relationbg WHERE id_isbn = $isbn";
                if ($conn->query($sql) === TRUE) {
                    $sql = "DELETE FROM relationbp WHERE id_isbn = $isbn";
                    if($conn->query($sql) === TRUE){
                        $sql = "DELETE FROM books WHERE id_isbn = $isbn";
                        if($conn->query($sql) === TRUE){
                            echo "Book deleted from database";
                        } else{
                            echo "Error deleting in table books" . $conn->error;     
                        }
                    } else{
                        echo "Error deleting in table BP" . $conn->error;    
                    }
                } else{
                    echo "Error deleting in table BG" . $conn->error;
                }
            } else {
                echo "Error deleting record in table BA " . $conn->error;
            }
              
              $conn->close();
        }//end of if(issset(GET))
        #when trying to delete with isbn we cant cus its foreign key of certain tables. 
        #when i tried with dewey code it showed succesfull but my database did not change 
    ?>
</body>
</html>