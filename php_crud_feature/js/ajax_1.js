$(document).ready(function(){
    $(document).on('click', '#submit', function(){
        var name = $('#full_name').val();
        var dob = $("#dob").val();
        var email = $("#email").val();
        var phone = $("#phone").val();
        var gender = $("#gender").val();
        var address = $("#address").val();
        var pincode = $("#pincode").val();
        var country = $("#country").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var documents = $("#documents").val();
        $.ajax({
            url:'insert.php',
            method:'POST',
            data:{
                'name':name,
                'dob':dob,
                'email':email,
                'phone':phone,
                'gender':gender,
                'address':address,
                'pincode':pincode,
                'country':country,
                'state':state,
                'city':city,
                'documents':documents
            },
            success:function(response){
                $('#name').val('');
                $("#dob").val();
                $("#email").val();
                $("#phone").val();
                $("#gender").val();
                $("#address").val();
                $("#pincode").val();
                $("#country").val();
                $("#state").val();
                $("#city").val();
                $("#documents").val();
                $('#display_area').append(response);
            }
        })
    })
})