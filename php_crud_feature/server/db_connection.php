<?php
$servername = "localhost";
$username = "root";
$password = "Ami@2211!";
$dbname = "student_management";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PODException $err){
    echo $err.getMessage();
}


?>