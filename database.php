
<?php
    $db_server = 'localhost';
    $db_username = 'duong';
    $db_password = 'Duong@88999';
    $db_name = 'food';

    $conn = new mysqli($db_server, $db_username, $db_password, $db_name);

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    
?>
