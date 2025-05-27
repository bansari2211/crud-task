<?php
$servername = "localhost";
$username = "root";
$password = "Ami@2211!";
$dbname ="student_management";
$conn = new mysqli($servername,$username,$password,$dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "";
?>
