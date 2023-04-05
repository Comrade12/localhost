<?php
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'test';

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>