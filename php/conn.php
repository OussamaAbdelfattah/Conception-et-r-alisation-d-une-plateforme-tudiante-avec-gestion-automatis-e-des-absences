<?php
    $database = 'App_isga_direct'; 
    $lignesTableHTML = "";
    $conn = new mysqli('localhost', 'root', '', $database);
    
    if ($conn->connect_error) {
        die('Erreur de connexion : ' . $conn->connect_error);
    }
?>