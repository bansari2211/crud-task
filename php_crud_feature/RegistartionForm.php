<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Data Management UI</title>
    <link rel="icon" href="assets/images/crud.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
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
        .error {
    color: red;
    font-size: 0.9em;
}

    </style>
</head>
<body>
    <?php
    include 'db_connection.php';
    $fullName =$dob=$email=$phone=$gender=$address=$pincode=$country=$state=$city= "";
    $nameErr = $emailErr = $phoneErr = $dobErr = $genderErr = $addressErr = $pincodeErr = $countryErr = $stateErr = $cityErr = "";
    $existingFiles=[];
    $allfile = [];
    $allfile2 = [];

    function test_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // echo "hello";

        $id = $_POST['id'] ?? '';
        $fullName = test_input($_POST['full_name'] ?? '');
        $dob = $_POST['dob'] ?? '';
        $email = test_input($_POST['email'] ?? '');
        $phone = test_input($_POST['phone'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $address = $_POST['address'] ?? '';
        $pincode = $_POST['pincode'] ?? '';
        $country = $_POST['country'] ?? '';
        $state = $_POST['state'] ?? '';
        $city = $_POST['city'] ?? '';

        if (empty($fullName))
        {
            $nameErr = "Please enter your Full Name.";
        }
        elseif (!preg_match("/^[a-zA-Z-' ]*$/", $fullName)) {
            $nameErr = "Only letters and white space allowed.";
        }

        if (empty($email)){
            $emailErr = "Please enter an email address.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email address.";
        }

        if (empty($phone)) {
            $phoneErr = "Please enter your phone number.";
        }
        elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneErr = "Phone Number must be 10 digits.";
        }
        if (empty($dob)){
            $dobErr = "Please choose your Date Of Birth.";
        }
        if (empty($gender)){
            $genderErr = "Please select your gender.";
        }
        if (empty($address)) {
            $addressErr = "Please enter your address.";
        }
        if (empty($pincode)) {
            $pincodeErr = "Please enter your pincode.";
        }
        elseif (!preg_match("/^[1-9][0-9]{5}$/", $pincode))
        {
            $pincodeErr = "Invalid pincode format.";
        }
        if (empty($country))
        {
            $countryErr = "Please select a country.";
        }
        if (empty($state))
        {
            $stateErr = "Please select a state.";
        }
        if (empty($city))
        {
            $cityErr = "Please select a city.";
        }

        if (!$nameErr && !$emailErr && !$phoneErr && !$dobErr && !$genderErr && !$addressErr && !$pincodeErr && !$countryErr && !$stateErr && !$cityErr)
        {
            if (!empty($id))
            {
                $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc())
                {
                    $existingFiles = !empty($row['documents']) ? json_decode($row['documents']) : [];
                }

                if (!empty($_POST['deletefile']))
                {
                    foreach ($_POST['deletefile'] as $delfile)
                    {
                        $filepath = 'media/' . $delfile;
                        if (file_exists($filepath))
                        {
                            unlink($filepath);
                        }
                        $existingFiles = array_diff($existingFiles, [$delfile]);
                    }
                }
                $allfile = $existingFiles;
                if (!empty($_FILES['documents']['name'][0]))
                {
                    foreach ($_FILES['documents']['name'] as $key => $value)
                    {
                        $filename = basename($value);
                        $tmpName = $_FILES['documents']['tmp_name'][$key];
                        $destination = 'media/' . $filename;
                        if (move_uploaded_file($tmpName, $destination))
                        {
                            $allfile[] = $filename;
                        }
                    }
                }
                $docs = json_encode($allfile);
                $stmt = $conn->prepare("UPDATE students SET full_name=?, dob=?, email=?, phone=?, gender=?, address=?, pincode=?, country=?, state=?, city=?, documents=? WHERE id=?");
                $stmt->bind_param("sssssssssssi", $fullName, $dob, $email, $phone, $gender, $address, $pincode, $country, $state, $city, $docs, $id);
                if ($stmt->execute()) {
                    echo "<script>alert('Record updated successfully!'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('Failed to update.');</script>";
                }
            }
            else
            {
                $allowedTypes = ['image/jpeg','image/jpg','image/png'];
                $maxSize = 5*1024*1024;
                $documentErr=[];
                foreach ($_FILES['documents']['name'] as $key=>$value)
                {
                    $filename=$value;
                    if($_FILES['documents']['size'][$key]>$maxSize)
                    {
                        $documentErr[] = "File size must be less than 5 MB.";
                        continue;
                    }
                    if(!in_array($_FILES['documents']['type'][$key],$allowedTypes))
                    {
                        $documentErr[] = "only jpg,png and jpeg will be allowed";
                        continue;
                    }
                    $tmpName=$_FILES['documents']['tmp_name'][$key];
                    $destination='media/'.$filename;
                    if(move_uploaded_file($tmpName, $destination))
                    {
                        $allfile2[] = $filename;
                    }
                }
                $docs = json_encode($allfile2);
                $stmt = $conn->prepare("INSERT INTO students (full_name, dob, email, phone, gender, address, pincode, country, state, city, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $fullName, $dob, $email, $phone, $gender, $address, $pincode, $country, $state, $city, $docs);
                if ($stmt->execute()) {
                    if(!empty($documentErr))
                    {
                        $errorMsg = implode("\n", $documentErr);
                        echo "<script>alert('Some file is not inserted!\\n{$errorMsg}'); window.location.href ='index.php';</script>";
                    }
                    else{
                            echo "<script>alert('New record inserted successfully!'); window.location.href ='index.php';</script>";
                    }
                }
                //  else {
                //     echo "<script>alert('file is not uploaded".implode("\n",$documentErr)."'); window.location.href = 'RegistartionForm.php';</script>";
                // }
            }
        }
    }

?>
<?php

    isset($_GET['id']);
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>
    <div class="container mt-5">
    <!-- <div id="msg" hidden></div> -->
        <h2 class="mb-4">Student Data Management</h2>

        <h4 class="section-title">Personal Information</h4>

        <form id="myform" action="<?php
         echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div>
                    <label for="studentName" class="form-label">Full Name</label>
                    <input type="hidden" name="id" value="<?php echo isset($id)?$id : ''; ?>">

                    <input type="text" class="form-control " id="full_name" name="full_name" value="<?= isset($id) ? $user['full_name']:$fullName ; ?>"placeholder="Enter Full Name">
                    <span class="error"> <?php echo $nameErr; ?> </span><br>
                </div>
                <div>
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob" id="dob" value="<?=  isset($id) ? $user['dob'] : $dob; ?>">
                    <span class="error"> <?php echo $dobErr; ?> </span><br>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?= isset($id) ? $user['email']:$email ;?>">
                    <span class="error"> <?php echo $emailErr; ?> </span><br>
                </div>
                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone"placeholder="Enter Phone" value="<?= isset($id) ? $user['phone']:$phone ; ?>">
                    <span class="error"> <?php echo $phoneErr; ?> </span><br>
                </div>
            </div>
            <label for="gender" class="form-label">Choose Gender :</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Female" <?php if($gender == 'Female') echo 'checked'; ?>  <?=$user['gender'] == 'Female' ? 'checked' : ''?>>
                <label class="form-check-label">Female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Male" <?php if($gender == 'Male') echo 'checked'; ?> <?=$user['gender'] == 'Male' ? 'checked' : ''?>>
                <label class="form-check-label">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Other" <?php if($gender == 'Other') echo 'checked'; ?>   <?=$user['gender'] == 'Other' ? 'checked' : ''?>>
                <label class="form-check-label">Other</label>
            </div>
            <span class="error"> <?php echo $genderErr; ?> </span>


            <h4 class="section-title">Address Information</h4>
            <div class="form-row">
                <div>
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="<?=isset($id) ? $user['address']:$address ; ?>">
                    <span class="error"> <?php echo $addressErr; ?> </span>
                </div>
                <div>
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" value="<?= isset($id) ? $user['pincode']:$pincode;  ?>">
                    <span class="error"> <?php echo $pincodeErr; ?> </span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" name="country" id="country" onfocus="selectFun()">
                        <option value="" selected="selected">Select Country</option>
                        <span class="error"> <?php echo $countryErr; ?> </span>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" name="state" id="state">
                        <option value="">Select State</option>
                        <span class="error"> <?php echo $stateErr; ?> </span>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" name="city" id="city">
                        <option value="" selected="selected">Select City</option>
                        <span class="error"> <?php echo $cityErr; ?> </span>

                    </select>
                </div>
            </div>
            <h4 class="section-title">Document Upload</h4>
            <div class="mb-3">
                <label> Select image for delete </label>
                <br>
                <table><td>
                    <?php
                        $images = json_decode($user['documents']);
                        foreach ($images as $img):
                            $img =$img;
                            if ($img):
                                $allImg =$img;
                        ?>
                        <input type="checkbox" name="deletefile[]" value="<?= $allImg ?>">
                        <img src="media/<?= $allImg ?>" width="40" height="20" style="display:inline; margin-top:5px;" />
                        <?php endif; endforeach; ?>
                        </td></table>
                        <label for="documents" class="form-label">Upload New Documents</label>
                        <input class="form-control" type="file" id="documents" name="documents[]" multiple>
                </div>
            <input type="submit" class='btn btn-secondary' name="submit" id="btn"></button>
        </div>
    </form>
    <?php ?>
    <!-- <script>
        $(document).on('submit','#myform',function(e)
        {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method:"POST",
                url: "RegistartionForm.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                     $('#msg').html(data);
                    $('#myform')[0].reset();
                }});
        });
    </script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- <script src="form_validation.js"></script> -->
    <script src="js/script.js"></script>
</body>
</html>
