<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="assets/images/crud.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            font-weight: bold;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-row > div {
            flex: 1;
        }
    </style>
</head>
<body>
<?php
include 'db_connection.php';
if(isset($_POST['serach'])){

$search = $_POST['search'];
print_r($_POST);
$sql = "SELECT * FROM students WHERE full_name LIKE '%$search%'";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    echo "<table class='table table-hover table-striped mt-5'>";
    echo "<tr>
        <th>FullName</th>
        <th>DOB</th>
        <th>Email</th>
        <th>Phoneno</th>
        <th>Gender</th>
        <th>address</th>
        <th>City</th>

    </tr>";
    while($row = $result->fetch_assoc() ){
        echo "<tr>";
        echo "<td>".$row["full_name"]."</td>";
        echo "<td>".$row["dob"]."</td>";
        echo "<td>".$row["email"]."</td>";
        echo "<td>".$row["phone"]."</td>";
        echo "<td>".$row["gender"]."</td>";
        echo "<td>".$row["address"]."</td>";
        echo "<td>".$row["city"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
}
}
else{
    $sql = "SELECT * FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    echo "<table class='table table-hover table-striped mt-5'>";
    echo "<tr>
        <th>FullName</th>
        <th>DOB</th>
        <th>Email</th>
        <th>Phoneno</th>
        <th>Gender</th>
        <th>address</th>
        <th>City</th>

    </tr>";
    while($row = $result->fetch_assoc() ){
        echo "<tr>";
        echo "<td>".$row["full_name"]."</td>";
        echo "<td>".$row["dob"]."</td>";
        echo "<td>".$row["email"]."</td>";
        echo "<td>".$row["phone"]."</td>";
        echo "<td>".$row["gender"]."</td>";
        echo "<td>".$row["address"]."</td>";
        echo "<td>".$row["city"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
}

}


?>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="form_validation.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>