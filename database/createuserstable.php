<?php
$servername  = 'localhost';
$username = 'root';
$password = '';
$dbname = 'covoiturage';

try {
    // 3. connection base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. creation users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(20) NOT NULL,
        email VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )";

    $conn->exec($sql);

    echo "Table created successfully ";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>