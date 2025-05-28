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

$(document).ready(function(){
    $('#myform').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'RegistartionForm.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if(response.status === 'success'){
                    alert(response.message);
                    // Optionally reset form or redirect
                    $('#myform')[0].reset();
                    // location.href = 'index.php'; // if you want to redirect
                } else if(response.status === 'warning'){
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(){
                alert('An error occurred while submitting the form.');
            }
        });
    });
});



$(document).on('click', '.delete-button', function() {
    const id = $(this).data('id');
    const row = $(this).closest('tr');

    $.ajax({
        url: 'deletedata.php',
        type: 'GET', // or POST if you want
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);   // Show success alert
                row.remove();              // Remove deleted row from the table
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Something went wrong while deleting the record.');
        }
    });
});
<a href="#" class="btn-delete" data-id="<?= $row['id'] ?>">Delete</a>

