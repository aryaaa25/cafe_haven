<?php
$host = 'localhost'; // Change if your DB is on a remote server
$username = 'root';
$password = '';
$dbname = 'food_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
?>

