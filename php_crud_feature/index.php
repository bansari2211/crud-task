<?php
    include 'db_connection.php';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="assets/images/crud.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<div class="container">

    <h2 style='text-align:center;' class='m-5'>All Students Data</h2>
    <a href="RegistartionForm.php" class ="btn btn-success"> Added New Student </a>

    <form method="POST" action="" id="searchForm">
        <input type="text" class="form-control mt-3 " id="search" name="search" placeholder="search here" >
        <div id="table-container">
        <input type="submit" name="searchBtn" value="Search" class="btn btn-primary mt-2">
    </form>
    <div id="result">
    <?php
        if(isset($_POST["searchBtn"])){
            $search = $_POST['search'];
            // print_r($_POST);
            $sql = "SELECT * FROM students WHERE full_name LIKE '%$search%'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0)
            {
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
                    echo "<input type='hidden' name='id' value='{$row['id']}'>";
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
        else{
            echo "<script>alert('No Record found in the Database');</script>";
           $sql = "SELECT * FROM students";
           $result = $conn->query($sql);
           if ($result->num_rows > 0)
           {
               echo "<table id='table-container'class='table table-hover table-striped mt-5'>";
               echo "<tr>
                   <th>FullName</th>
                   <th>DOB</th>
                   <th>Email</th>
                   <th>Phoneno</th>
                   <th>Gender</th>
                   <th>address</th>
                   <th>City</th>
                   <th>Documents</th>
                   <th>Action</th>
               </tr>";
               while($row = $result->fetch_assoc()) {
               echo "<tr>";
               echo "<input type='hidden' name='id' value='{$row['id']}'>";
               echo "<td>".$row["full_name"]."</td>";
               echo "<td>".$row["dob"]."</td>";
               echo "<td>".$row["email"]."</td>";
               echo "<td>".$row["phone"]."</td>";
               echo "<td>".$row["gender"]."</td>";
               echo "<td>".$row["address"]."</td>";
               echo "<td>".$row["city"]."</td>";
               $images = json_decode($row['documents']);
               echo "<td>";
               foreach ($images as $img):
                   $img = trim($img);
                   if ($img):
               ?>
               <img src="media/<?=$img?>" width="40" height="20" style="margin:5px;" />
               <?php endif; endforeach;?>
               <?php echo   "</td>";
               echo "<td>";
                   echo "<a href=\"edit_form.php?id=$row[id]\" class='btn btn-sm btn-secondary'> <i class='fa fa-edit'></i></a>";
                   echo "<a href=\"deletedata.php?id=$row[id]\" class='btn btn-sm btn btn-danger m-1'><i class='fa fa-trash-o'></i></a>";
                   echo "</td>";
               echo "</tr>";
               }
               echo "</table>";
           }

        }
    }
    else{
       $sql = "SELECT * FROM students";
       $result = $conn->query($sql);
       if ($result->num_rows > 0)
       {
           echo "<table id='table-container'class='table table-hover table-striped mt-5'>";
           echo "<tr>
               <th>FullName</th>
               <th>DOB</th>
               <th>Email</th>
               <th>Phoneno</th>
               <th>Gender</th>
               <th>address</th>
               <th>City</th>
               <th>Documents</th>
               <th>Action</th>
           </tr>";
           while($row = $result->fetch_assoc()) {
           echo "<tr>";
           echo "<input type='hidden' name='id' value='{$row['id']}'>";
           echo "<td>".$row["full_name"]."</td>";
           echo "<td>".$row["dob"]."</td>";
           echo "<td>".$row["email"]."</td>";
           echo "<td>".$row["phone"]."</td>";
           echo "<td>".$row["gender"]."</td>";
           echo "<td>".$row["address"]."</td>";
           echo "<td>".$row["city"]."</td>";
           $images =json_decode($row['documents']);
           echo "<td>";
           foreach ($images as $img):
               $img = trim($img);
               if ($img):
           ?>
           <img src="media/<?=($img)?>" width="40" height="20" style="margin:5px;" />
           <?php endif; endforeach;?>
           <?php echo "</td>";
           echo "<td>";
               echo "<a href=\"RegistartionForm.php?id=$row[id]\" class='btn btn-sm btn-secondary'> <i class='fa fa-edit'></i></a>";
               echo "<a href='deletedata.php?id={$row['id']}' class='btn  btn-sm btn-danger'> <i class='fa fa-trash'></i></a>";
            ?>
            <?php
                echo "</td>";
            echo "</tr>";
            }
            echo "</table>";
        }
        }
    ?>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="form_validation.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>