<?php
session_start();
$host = "mysql"; // Your database host (usually localhost)
$db_name = "wiredworld_db"; // The name of your database
$username = "wiredworld_user"; // Your MySQL username
$password = "wiredworld2023"; // Your MySQL password

try
{
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

date_default_timezone_set('America/Chicago'); // Set the default time zone to Central Time (CT)
