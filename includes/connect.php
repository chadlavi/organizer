<?php
include "../secrets.php";
$servername = "localhost";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
#echo "<div style='display: block; position: absolute; top: 0; right: 0; background-color: green; color: white; font-family: monospace; padding: 10px 20px;'><p>Connection: successful</p></div>";

?> 
