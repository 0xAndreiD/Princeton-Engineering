<script>

$(document).ready(function(){
    Dashmix.helpers('validation');
    var validateObj = $('#profileForm').validate({rules: {
        'name': {
            required: true,
            minlength: 3
        },
        'email': {
            required: true,
            email: true
        },
        'website': {
            required: true,
            url: true
        },
        'telno': {
            required: true,
            phoneUS: true
        },
        'digits': {
            required: true,
            digits: true
        },
        'number': {
            required: true,
            number: true
        },
    },
    messages: {
        'name': {
            required: 'Please enter a name',
            minlength: 'Your name must consist of at least 3 characters'
        },
        'email': 'Please enter a valid email address',
        'website': 'Please enter your website!',
        'telno': 'Please enter a US phone!',
        'digits': 'Please enter only digits!',
        'number': 'Please enter a number!',
    },
    submitHandler: function(){
        updateCompany();
    }
    });
})

function updateCompany() {
    let toast = Swal.mixin({
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-success m-1',
            cancelButton: 'btn btn-danger m-1',
            input: 'form-control'
        }
    });
    var data = {};
    data.id = $('input#id').val();
    data.name = $('input#name').val();
    data.number = $('input#number').val();
    data.telno = $('input#telno').val();
    data.address = $('input#address').val();
    data.email = $('input#email').val();
    data.website = $('input#website').val();
    
    $.post("updateCompany", {data: data}, function(result){
        if (result){
            toast.fire('Updated!', 'Company has been updated.', 'success');
        }
    });
}

</script>