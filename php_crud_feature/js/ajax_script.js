$(document).ready(function () {
    $('#myform').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Collect all form fields

        $.ajax({
            type: 'POST',
            url: 'RegistartionForm.php', // PHP file handling insert/update
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert(response); // Show plain message from PHP
                $('#myform')[0].reset(); // Reset the form
                window.location.href = "index.php"; // Redirect to index
            },
            error: function (xhr, status, error) {
                alert("AJAX Error: " + error);
            }
        });
    });
});


if(!empty($documentErr)) {
    $errorMsg = implode("\\n", $documentErr);
    echo json_encode([
        'status' => 'error',
        'message' => "Some file is not inserted!\n{$errorMsg}"
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'message' => 'New record inserted successfully!'
    ]);
}

$.ajax({
    method: "POST",
    url: "RegistartionForm.php",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "json",  // important: expect JSON response
    success: function(response) {
        alert(response.message);
        if(response.status === 'success') {
            window.location.href = "index.php";
        }
        // else: just show the alert and stay on the page
    },
    error: function() {
        alert("Something went wrong, please try again.");
    }
});
