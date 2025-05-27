<?php

require_once "db_connection.php";
$name = $email = $phone = $dob = $gender = $address = $pincode = $country = $state = $city = $documents = "";
$nameErr = $emailErr = $phoneErr = $dobErr = $genderErr = $addressErr = $pincodeErr = $countryErr = $stateErr = $cityErr = $documentsErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Name Validation
    $inputName = test_input($_POST["fullName"]);
    if(empty($inputName)) {
        $nameErr = "Please enter your Full Name.";
    } else {
        $name = $inputName;
        if(!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed.";
        }
    }
    //Email Validation
    $inputEmail =  test_input($_POST["email"]);
    if(empty($inputEmail)) {
        $emailErr = "Please enter an email address.";
    } else {
        $email = $inputEmail;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Please enter a valid email address.";
        }
    }
    //Phone Validation
    $inputPhone = test_input($_POST["phone"]);
    if(empty($inputPhone)) {
        $phoneErr = "Please enter your phone number.";
    } else {
        $phone = $inputPhone;
        if(!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneErr = "Phone Number contain only 10 digits.";
        }
    }
    //DOB Validation
    $inputDob = test_input($_POST["dob"]);
    if(empty($inputDob)) {
        $dobErr = "Please choose your Date Of Birth.";
    } else {
        $dob = $inputDob;
    }
    //Gender Validation
    $inputGender = test_input($_POST["gender"]);
    if(empty($inputGender)) {
        $genderErr = "Please select your gender.";
    } else {
        $gender = $inputGender;
    }
    //Address Validation
    $inputAddress = test_input($_POST["address"]);
    if(empty($inputAddress)) {
        $addressErr = "Please enter your address.";
    } else {
        $address = $inputAddress;
    }
    //Pincode Validation
    $inputPin = test_input($_POST["pincode"]);
    if(empty($inputPin)) {
        $pincodeErr = "Please enter your pincode.";
    } else {
        $pincode = $inputPin;
        if(!preg_match("/^[1-9][0-9]{5}$/", $pincode)) {
            $pincodeErr = "Pincode must be in 6 digit and cannot start with 0.";
        }
    }
    //Country Validation
    $inputCountry = test_input($_POST["country"]);
    if(empty($inputCountry)) {
        $countryErr = "Please select a country.";
    } else {
        $country = $inputCountry;
    }
    //State Validation
    $inputState = test_input($_POST["state"]);
    if(empty($inputState)) {
        $stateErr = "Please select a state.";
    } else {
        $state = $inputState;
    }
    //City Validation
    $inputCity = test_input($_POST["city"]);
    if(empty($inputCity)) {
        $cityErr = "Please select a city.";
    } else {
        $city = $inputCity;
    }
    if(empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($dobErr) && empty($genderErr) &&
        empty($addressErr) && empty($pincodeErr) && empty($countryErr) && empty($stateErr) &&
        empty($cityErr)) {
        $stmt = $conn->prepare("INSERT INTO students (full_name, dob, email, phone, gender, address,
                                pincode, country, state, city, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?,
                                ?, ?, ?)");

        $stmt->bind_param("sssssssssss", $name, $dob, $email, $phone, $gender, $address, $pincode,
                            $country, $state, $city, $documents);
        $stmt->execute();
        echo "<script>
                alert('New record inserted successfully!');
                window.location.href = '../index.php';
            </script>";
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);     // removes backslash
    $data = htmlspecialchars($data);    // converts special characters to HTML entities
    return $data;
}


    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxSize = 5 * 1024 * 1024;
    $documentErr = [];
    $allfile2 = [];

    if (!isset($_FILES['documents'])) {
        echo "<script>alert('No files uploaded.'); window.location.href = 'RegistrationForm.php';</script>";
        exit;
    }

    foreach ($_FILES['documents']['name'] as $key => $value) {
        $fileType = $_FILES['documents']['type'][$key];
        $fileSize = $_FILES['documents']['size'][$key];
        $fileName = basename($value);
        $fileName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $fileName);

        if (!in_array($fileType, $allowedTypes)) {
            $documentErr[] = "File $fileName has invalid type.";
            continue;
        }

        if ($fileSize > $maxSize) {
            $documentErr[] = "File $fileName exceeds 5 MB.";
            continue;
        }

        $tmpName = $_FILES['documents']['tmp_name'][$key];
        $destination = 'media/' . $fileName;

        if (move_uploaded_file($tmpName, $destination)) {
            $allfile2[] = $fileName;
        }
    }

    if (empty($documentErr)) {
        $docs = json_encode($allfile2);
        $stmt = $conn->prepare("INSERT INTO students (full_name, dob, email, phone, gender, address, pincode, country, state, city, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $fullName, $dob, $email, $phone, $gender, $address, $pincode, $country, $state, $city, $docs);
        if ($stmt->execute()) {
            echo "<script>alert('New record inserted successfully!'); window.location.href ='index.php';</script>";
        }
    } else {
        echo "<script>alert('" . implode("\\n", $documentErr) . "'); window.location.href = 'RegistrationForm.php';</script>";
    }


?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <style>
        body {
            background-image: url("../images/bg-1.jpeg");
        }
    </style>
</head>
 
<body>
<div class="container pb-2 mt-3" id="insertContainer">
    <div class="card shadow-lg">
        <h2 class="pt-2 pb-4 mb-4 border-dark border-bottom text-center text-black border-2">Student Data Management</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="<?php echo $name; ?>"
                        id="name" name="fullName" placeholder="Enter Name">
                    <span class="error"> <?php echo $nameErr; ?> </span><br>
                </div>
 
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                    <span class="error"> <?php echo $emailErr; ?> </span><br>
                </div>
 
                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                    <span class="error"> <?php echo $phoneErr; ?> </span><br>
                </div>
            </div><br>
 
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob">
                    <span class="error"> <?php echo $dobErr; ?> </span><br>
                </div>
 
                <div class="col-md-6">
                    <label for="gender" class="form-label" id="gender">Gender</label><br>
 
                    <div class="form-check form-check-inline" id="gFemale">
                        <input class="form-check-input" type="radio" name="gender" id="female"
                        <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
 
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male"
                        <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">
                        <label class="form-check-label" for="male">Male</label>
                    </div>
 
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="other"
                        <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">
                        <label class="form-check-label" for="other">Other</label>
                    </div><br>
                    <span class="error" style="margin-left:70px;"> <?php echo $genderErr; ?> </span>
                </div>
            </div><br>
 
            <div class="row g-3">
                <div class="col">
                    <label for="address" class="form-label">Address</label>
                    <input class="form-control" id="address" name="address" style="width:600px;" placeholder="Enter Address">
                    <span class="error"> <?php echo $addressErr; ?> </span>
                </div>
 
                <div class="col">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode">
                    <span class="error"> <?php echo $pincodeErr; ?> </span>
                </div>
            </div><br>
 
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" id="country" name="country" onfocus="selectFun()">
                        <option value="" selected="selected">Select Country</option>
                    </select>
                    <span class="error"> <?php echo $countryErr; ?> </span>
                </div>
 
                <div class="col-md-4">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state">
                        <option value="" selected="selected">Select State</option>
                    </select>
                    <span class="error"> <?php echo $stateErr; ?> </span>
                </div>
 
                <div class="col-md-4">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city">
                        <option value="" selected="selected">Select City</option>
                    </select>
                    <span class="error"> <?php echo $cityErr; ?> </span>
                </div>
            </div><br>
 
            <div class="text-center">
                <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
                <a href="../index.php" class="btn btn-secondary" id="btnCancel">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</body>
</html>
?>