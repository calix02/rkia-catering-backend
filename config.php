<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "rkia"; 
    $conn = "";

    
    $conn = mysqli_connect($db_server, 
                            $db_user, 
                            $db_password, 
                            $db_name);    

   
    if ($conn->connect_error) {
        die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
    }
  

?>