<?php
$servername  = 'localhost';
$username = 'root';
$password = ''; 
$dbname = 'covoiturage';

try {

    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $conn->exec($sql);


    } 
    catch(PDOException $e) {
    echo "Error: " . $e->getMessage();

    }
















?>