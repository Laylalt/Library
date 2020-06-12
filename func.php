<?php
    if (isset($_GET["acc"]) && $_GET["acc"] == 0){
        logoff();
    }
    
    function logoff(){
        session_start();
        session_destroy();
        echo "bitch im dead";
    }

    function check(){
        session_start();
        if(isset($_SESSION["type"])){
            return $_SESSION["type"];
        }else{
            return NULL;
        }
    }

    function connect(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($servername, $username, $password, "library");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return NULL; 
        }else{
            return $conn;
        }
    }

    function head(){
        if(isset($_SESSION["first_name"])){
            $print = "<div class='H'>";
            if($_SESSION["type"] == 0){
                $print .= "<a href='http://localhost/admin/main_admin.php'>HOME</a>";
                $print .= "<p class='H'>Hi " . $_SESSION["first_name"] . "</p>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=4'> Fees()  |</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=2'> Loans  |</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=1'> Users  |</a>";
                $print .= "<a class='H' href='http://localhost/admin/library_admin.php?acc=3'> Books  |</a>";
                $print .= "<p class='H'><a class='H' href='../func.php?acc=0'>Log Off  |</a>";
                $print .="</p>";
                $print .= "</div>";
            echo $print;
            }else{
                //alumnos header
            }
            
        }
    }

    
    
