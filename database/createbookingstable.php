<?php
$servername  = 'localhost';
$username = 'root';
$password = '';
$dbname = 'covoiturage';

try {
    // 3. connection base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. creation bookings table
   $sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    trip_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (trip_id) REFERENCES trips(id)
)";

$conn->exec($sql);

    echo "Table created successfully ";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>