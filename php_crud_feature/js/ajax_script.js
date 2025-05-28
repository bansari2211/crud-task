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
