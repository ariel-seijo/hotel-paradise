<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paradise";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Si llegas aquí, la conexión fue exitosa
