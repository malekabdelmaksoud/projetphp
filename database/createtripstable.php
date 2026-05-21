<?php
$servername  = 'localhost';
$username = 'root';
$password = '';
$dbname = 'covoiturage';

try {
    // 3. connection base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. creation trips table
    $sql = "CREATE TABLE IF NOT EXISTS trips (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    departure VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    trip_date DATE NOT NULL,
    seats INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

$conn->exec($sql);

    echo "Table created successfully ";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>