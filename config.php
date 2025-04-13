<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'stroycomp';

    $conn = new mysqli($servername, $username, $password, $db_name);
    
    if ($conn->connect_error) {
        die('Ошибка подключения: ' . $conn->connect_error);
    }
    
    $conn->set_charset('utf8');
?>
