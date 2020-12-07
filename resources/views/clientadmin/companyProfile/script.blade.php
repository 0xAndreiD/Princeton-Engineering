<script>

function mySubmitFunction(e) {
  e.preventDefault();
  updateCompany();
  return false;
}

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