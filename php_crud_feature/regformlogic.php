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
