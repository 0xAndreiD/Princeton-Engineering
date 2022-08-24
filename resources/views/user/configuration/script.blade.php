<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url:"getUserSetting",
        type:'post',
        success: function(res){
            if(res.success){
                $("#font-size").val(res.inputFontSize);
                $("#cell-height").val(res.inputCellHeight);
                $("#cell-font").val(res.inputFontFamily);
                if(res.includeFolderName)
                    $("#include-folder").prop('checked', true);

                $(".h13 input").css('font-size', res.inputFontSize + 'pt');
                $(".h13 td").css('font-size', res.inputFontSize + 'pt');
                $(".h13").css('height', res.inputCellHeight + 'pt');
                $(".h13 input").css('font-family', res.inputFontFamily);
                $(".h13 td").css('font-family', res.inputFontFamily);
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` });
        }
    });
});

function updateSetting(){
    $(".h13 input").css('font-size', $("#font-size").val() + 'pt');
    $(".h13 td").css('font-size', $("#font-size").val() + 'pt');
    $(".h13").css('height', $("#cell-height").val() + 'pt');
    $(".h13 input").css('font-family', $("#cell-height").val());
    $(".h13 td").css('font-family', $("#cell-font").val());

    $.ajax({
        url:"updateUserSetting",
        type:'post',
        data: {
            'inputFontSize': $("#font-size").val(),
            'inputCellHeight': $("#cell-height").val(),
            'inputFontFamily': $("#cell-font").val(),
            'includeFolderName': $("#include-folder")[0] && $("#include-folder")[0].checked ? 1 : 0,
        },
        success: function(res){
            if(!res.success)
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error", text: message == "" ? "Error happened while processing." : message, icon: "error", confirmButtonText: `OK` });
        }
    });
}
</script>