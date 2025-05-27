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
session_start();

if(isset($_GET['id']))
{
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id); //
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0)
{
    while ($row = $result->fetch_assoc()) {
        // $existingFiles = !empty($row['documents']) ? explode(',', $row['documents']) : [];
?>
<div class="container mt-5">
    <!-- <div id="msg" hidden></div> -->
        <h2 class="mb-4">Student Data Management</h2>

        <h4 class="section-title">Personal Information</h4>

        <form id="myform" action="formeditdata.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div>
                    <label for="studentName" class="form-label">Full Name</label>
                    <input type="hidden" name="id" value="<?=$row['id'];?>">
                    <input type="text" class="form-control " id="full_name" name="full_name" value="<?= $row['full_name']; ?>" placeholder="Enter Full Name">
                    <span class="error"> <?php echo $nameErr; ?> </span><br>
                </div>
                <div>
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob" id="dob" value="<?= $row['dob']; ?>">
                    <span class="error"> <?php echo $dobErr; ?> </span><br>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?= $row['email']?>">
                    <span class="error"> <?php echo $emailErr; ?> </span><br>
                </div>
                <div>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone"placeholder="Enter Phone" value="<?=$row['phone']?>">
                    <span class="error"> <?php echo $phoneErr; ?> </span><br>
                </div>
            </div>
            <label for="gender" class="form-label">Choose Gender :</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Female" <?php if($gender == 'Female') echo 'checked'; ?>>
                <label class="form-check-label">Female</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Male" <?php if($gender == 'Male') echo 'checked'; ?>>
                <label class="form-check-label">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Other" <?php if($gender == 'Other') echo 'checked'; ?>>
                <label class="form-check-label">Other</label>
            </div>
            <span class="error"> <?php echo $genderErr; ?> </span>


            <h4 class="section-title">Address Information</h4>
            <div class="form-row">
                <div>
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="<?=$row['address']?>">
                    <span class="error"> <?php echo $addressErr; ?> </span>
                </div>
                <div>
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" value="<?=$row['pincode']?>">
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
                <label for="documents" class="form-label">Your Document</label> <br>
                <label> Select image for delete </label>
                <br>
                <table><td>
                    <?php
                        $images = json_decode($row['documents']);
                        foreach ($images as $img):
                            $img =$img;
                            if ($img):
                                $safeImg =$img;
                        ?>
                        <input type="checkbox" name="deletefile[]" value="<?= $safeImg ?>">
                        <img src="media/<?= $safeImg ?>" width="40" height="20" style="display:inline; margin-top:5px;" />
                        <?php endif; endforeach; ?>
                        </td></table>
                        <label for="documents" class="form-label">Upload New Documents</label>
                        <input class="form-control" type="file" id="documents" name="documents[]" multiple>
                </div>
                <?php
    
                    }
                }
                heder("location:formeditdata.php");
            }
               else{
                $nameErr = $emailErr = $phoneErr = $dobErr = $genderErr = $addressErr = $pincodeErr = $countryErr = $stateErr = $cityErr = $documentErr= "";

                if ($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    function test_input($data) {
                        return htmlspecialchars(stripslashes(trim($data)));
                    }
                    $fullName = $_POST['full_name'] ?? '';
                    $dob = $_POST['dob'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $phone = $_POST['phone'] ?? '';
                    $gender = $_POST['gender'] ?? '';
                    $address = $_POST['address'] ?? '';
                    $pincode = $_POST['pincode'] ?? '';
                    $country = $_POST['country'] ?? '';
                    $state = $_POST['state'] ?? '';
                    $city = $_POST['city'] ?? '';
                    $allfile = [];
                    $inputName = test_input($fullName);
                 //    print_r($_POST);
                    if (empty($inputName) || $inputName == '')
                    {
                        $nameErr = "Please enter your Full Name.";
                    }
                    elseif(!preg_match("/^[a-zA-Z-' ]*$/", $inputName))
                    {
                        $nameErr = "Only letters and white space allowed.";
                    }
                 //    echo $nameErr;
                    $inputEmail = test_input($email);
                    if (empty($inputEmail)) {
                        $emailErr = "Please enter an email address.";
                    }
                    elseif (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)){
                         $emailErr = "Please enter a valid email address.";
                    }
                    $inputPhone = test_input($phone);
                    if (empty($inputPhone)) {
                        $phoneErr = "Please enter your phone number.";
                    }
                    elseif (!preg_match("/^[0-9]{10}$/", $inputPhone)) {
                        $phoneErr = "Phone Number must contain 10 digits.";
                    }
                    if(empty($dob))
                    {
                        $dobErr = "Please choose your Date Of Birth.";
                    }
                    if(empty($gender))
                    {
                        $genderErr = "Please select your gender.";
                    }
                    if(empty($address))
                    {
                        $addressErr = "Please enter your address.";
                    }
                    if(empty($pincode))
                    {
                         $pincodeErr = "Please enter your pincode.";
                    }
                    elseif (!preg_match("/^[1-9][0-9]{5}$/", $pincode))
                    {
                          $pincodeErr = "Pincode must be 6 digits and cannot start with 0.";
                     }
                    if(empty($country))
                    {
                         $countryErr = "Please select a country.";
                    }
                    if(empty($state))
                    {
                        $stateErr = "Please select a state.";
                    }
                    if(empty($city))
                    {
                        $cityErr = "Please select a city.";
                    }
                    if (!$nameErr && !$emailErr && !$phoneErr && !$dobErr && !$genderErr && !$addressErr && !$pincodeErr && !$countryErr && !$stateErr && !$cityErr)
                    {
                          foreach ($_FILES['documents']['name'] as $key => $value)
                             {
                                $fileName = $value;
                                $tmpName = $_FILES['documents']['tmp_name'][$key];
                                 $destination = 'media/' . $fileName;
                                 if (move_uploaded_file($tmpName, $destination))
                                 {
                                   $allfile[] = $fileName;
                                }
                          }
                     }
                     // print_r($allfile);
                     $newimplode = json_encode($allfile);
         
                     $stmt = $conn->prepare("INSERT INTO students (full_name, dob, email, phone, gender, address, pincode, country, state, city, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                       $stmt->bind_param("sssssssssss", $fullName, $dob, $email, $phone, $gender, $address, $pincode, $country, $state, $city, $newimplode);
                       if ($stmt->execute()) {
                            echo "<script>alert('New record inserted successfully!'); window.location.href ='index.php';</script>";
                        } else {
                           echo "<script>alert('Record not inserted!');window.location.href = 'RegistartionForm.php'; </script>";
                      }
                 }

               }

                 ?>
                <input type="submit" class="btn btn-primary" name="submit"></button>
            </div>
            </form>
    <!-- <script>
        $(document).on('submit','#myform',function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method:"POST",
                url: "insert.php",
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