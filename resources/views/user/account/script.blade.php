<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
});

function saveAccount(){
    if($("#username").val() == "" || $("#email").val() == "" || $("#password").val() == "")
    {
        swal.fire({ title: "Error", text: "Please fix empty values.", icon: "error", confirmButtonText: `OK` });
        return;
    }
    $.ajax({
        url:"updateMyAccount",
        type:'post',
        data: {
            'username': $("#username").val(),
            'email': $("#email").val(),
            'password': $("#password").val(),
            @if(Auth::user()->userrole == 2 || Auth::user()->userrole == 3 || Auth::user()->userrole == 4)
            'autoOpen': $("#automatic-open")[0].checked ? 1 : 0,
            @endif
        },
        success: function(res){
            if(!res.success)
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            else
                swal.fire({ title: "Success", text: "Successfully Updated!", icon: "success", confirmButtonText: `OK` });
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` });
        }
    });
}
</script>