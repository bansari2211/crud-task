
// // loaddata()
$(document).ready(function ()
    {
      $("#myform").validate({
        rules: {
          full_name:{
              required: true,
              lettersonly: true
          },
          dob:{
              required: true
          },
          email: {
              required: true,
              email: true
          },
          phone: {
              required: true,
              minlength: 10,
              maxlength: 10
          },
          gender: {
              required: true
          },
          address: {
              required: true
          },
          pincode: {
              required: true,
              maxlength:6
          },
          country: {
              required: true
          },
          state: {
              required: true
          },
          city: {
              required: true
          },
          documents:
          {
              fileRequired: true,
              accept: "image/jpeg, image/jpg, image/png",
            // required: true,
            // accept: "image/jpg,image/jpeg,image/png"
          }
        },
        messages: {
            full_name: {
                required: "Name is required",
            },
            dob: {
                required: "Date of Birth is required"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            phone: {
                required: "Phone number is required",
                minlength: "Phone number must be 10 digits",
                maxlength: "Phone number must be 10 digits"
            },
            gender: {
                required: "Please select gender"
            },
            address: {
                required: "Address is required"
            },
            pincode: {
                required: "Pincode is required",
                maxlength:"Pincode have maximum 6 length"
            },
            country: {
                required: "Country is required"
            },
            state: {
                required: "State is required"
            },
            city: {
                required: "City is required"
            },
            documents: {
                fileRequired: "Please upload documents",
                 accept: "Only JPG, JPEG, and PNG formats are allowed"
                // required: 'Required!',
                // accept: 'Not an image!'
            }
        },
        errorElement: "div",
      errorClass: "text-danger",
    })

})
$.validator.addMethod("fileRequired", function (value, element)
{
    return element.files.length > 0;
}, "Please upload at least one file.");
jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
  }, "Letters only please");
// $(document).on('change', 'input[type="file"]', function() {
//     const file = this.files[0];
//     const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

//     if (file && !allowedTypes.includes(file.type)) {
//         alert('Please select a valid image file (jpg, jpeg, or png).');
//         this.value = ''; // Clear the invalid file selection
//     }
// });


    $(document).ready(function () {
        $('#documents').on('change', function () {
            let files = this.files;
            // let error = '';
            let allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

            for (let i = 0; i < files.length; i++) {
                let file = files[i];

                // Check file extension
                if (!allowedExtensions.exec(file.name)) {
                    alert("Only JPG and PNG files are allowed.");
                    break;
                }

                // Check file size (5 MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert("Each file must be less than 5 MB.");
                    break;
                }
            }

            // if (error) {
            //     $('#fileError').text(error);
            //     $(this).val(''); // Clear the file input
            // } else {
            //     $('#fileError').text('');
            // }
        });
    });
    // if ($stmt->execute()) {
    //     if (!empty($documentErr)) {
    //         echo json_encode([
    //             'status' => 'success',
    //             'message' => 'Some files were not uploaded.',
    //             'errors' => $documentErr
    //         ]);
    //     } else {
    //         echo json_encode(['status' => 'success', 'message' => 'Record saved successfully!']);
    //     }
    // } else {
    //     echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    // }
    


    //   if (errorMessage) {
    //     $("#error-message").text(errorMessage);
    //     event.preventDefault(); // Prevent form submission
    //   } else {
    //     $("#error-message").text(""); // Clear previous error messages
    //   }
<script>
$(document).ready(function () {
    $('#myform').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'submit_student.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert(res.message);
                        if (res.errors) {
                            alert("Details:\n" + res.errors.join("\n"));
                        }
                        window.location.href = "index.php";
                    } else {
                        alert("Error: " + res.message);
                    }
                } catch (err) {
                    alert("Unexpected response:\n" + response);
                }
            },
            error: function (xhr, status, error) {
                alert("AJAX error: " + error);
            }
        });
    })
});
</script>
