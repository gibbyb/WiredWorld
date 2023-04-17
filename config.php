<?php
$host = "wwdb.gibbyb.com"; // Your database host (usually localhost)
$db_name = "wired-world-db"; // The name of your database
$username = "ww-db"; // Your MySQL username
$password = "ww2023"; // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


