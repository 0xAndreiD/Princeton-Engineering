<script>
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var projectId = $('#projectId').val();

    $.ajax({
        url:"getProjectJson",
        type:'post',
        data:{projectId: projectId},
        success:function(res){
            if (res && res.success) {
                var jobData = JSON.parse(res.data);
                console.log(jobData);
                if(jobData){
                    if(jobData.Equipment){
                        if(jobData.Equipment.PVModule){
                            $("#Module-text").html("MFR / Model: " + jobData.Equipment.PVModule.Type + ' / ' + jobData.Equipment.PVModule.SubType);
                            $("#ModuleQuantity-text").html("Quantity: " + jobData.Equipment.PVModule.Quantity);
                        }
                        if(jobData.Equipment.PVInverter){
                            $("#Inverter-text").html("MFR / Model: " + jobData.Equipment.PVInverter.Type + ' / ' + jobData.Equipment.PVInverter.SubType);
                            $("#InverterQuantity-text").html("Quantity: " + jobData.Equipment.PVInverter.Quantity);
                        }
                    }
                }
                
            } else
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });


})
</script>