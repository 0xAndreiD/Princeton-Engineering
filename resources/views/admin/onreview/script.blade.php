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

    $.ajax({
        url:"getDataCheck",
        type:'post',
        data:{projectId: projectId},
        success:function(res){
            if(res && res.success == true && res.data){
                console.log(res.data);
                $("#exposure-text").html("Exposure: " + res.data.exposureUnit);
                $("#occupancy-text").html("Occupancy: " + res.data.occupancyUnit);
                $("#wind-text").html("Wind: " + res.data.windLoadingValue);
                $("#snow-text").html("Snow: " + res.data.snowLoadingValue);
                $("#IBC-text").html("IBC: " + res.data.IBC);
                $("#ASCE-text").html("ASCE: " + res.data.ASCE);
                $("#NEC-text").html("NEC: " + res.data.NEC);
                $("#StateCode-text").html("State Code: " + res.data.stateCode);
                $("#Power-text").html("Tot DC watts: " + res.data.DCWatts);
                $("#SumInvAmps-text").html("Sum Inv Amps: " + res.data.InverterAmperage);
                $("#MinA-text").html("Min A: " + res.data.OCPDRating);
                $("#RecommendedA-text").html("Recommended A: " + res.data.RecommendOCPD);
                $("#MinCuWireSize-text").html("Min Cu Wire Size: " + res.data.MinCu);
                if(res.data.collarHeights){
                    let collarNotes = '';
                    for(let i = 1; 5 * i <= res.data.collarHeights.length; i ++){
                        let tabCollar = res.data.collarHeights.slice(5 * (i - 1), 5 * i);
                        collarNotes = collarNotes + " " + i + ": MP" + i + " @ " + parseFloat(tabCollar).toFixed(2) + (5 * i == res.data.collarHeights.length ? "" : ",");
                    }
                    $("#ColTieKneeWalls-text").html("Collar Tie / Knee Walls: " + collarNotes);
                } else
                    $("#ColTieKneeWalls-text").html("Collar Tie / Knee Walls: None");
                if(res.data.summary)
                    $("#StructNotes-text").html("Structural Notes: " + res.data.summary);
                else
                    $("#StructNotes-text").html("Structural Notes: None");
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            resolve(false);
        }
    });

    $(document).on('submit','#submitChat', function(event){
        event.preventDefault();
		$('#send').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"submitChat",
			method:"POST",
			data:formData,
			success:function(data){
                if(!data || !data.status){
                    swal.fire({ title: "Warning", text: data && data.message ? data.message : "Failed to submit your message.", icon: "warning", confirmButtonText: `OK` });
                } else {
                    window.msgCount++;
                    $("#addChatMsg").val("");
                    let note = 'Comment ' + window.msgCount + ': ' + data.user + ' on ' + data.datetime + '\n';
                    note += '    ' + data.message + '\n\n';
                    $('#send').attr('disabled', false);
                    $("#chatLog").val(note + $('#chatLog').val());
                }
			}
		})
	});
});

function checkboxAll(){
    $("input[type=checkbox]:enabled").each(function() {
        if($(this).attr('id') != 'Review' && $(this).attr('id') != 'Asbuilt'){
            $(this).prop('checked', $("#ControlCheck")[0].checked);
        }
    });
}

function togglePlanCheck(jobId){
    $.ajax({
        url:"togglePlanCheck",
        type:'post',
        data:{jobId: jobId},
        success: function(res){
            if(res.success){
                return;
                // $('#projects').DataTable().draw();
            }else
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
}

function toggleAsBuilt(jobId){
    $.ajax({
        url:"toggleAsBuilt",
        type:'post',
        data:{jobId: jobId},
        success: function(res){
            if(res.success){
                return;
                // $('#projects').DataTable().draw();
            }else
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
}

function clearNote(){
    $("#addChatMsg").val("");
}

function autoNote(){
    let note = '';
    let firstNote = 0;

    $("#envSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == false){
            if(!firstNote){
                firstNote = 1;
                note += ' Environmental:';
            }
            note += (' ' + $('#' + $(this).attr('id') + '-text').html() + ';');
        }
    });
    
    firstNote = 0;
    $("#codeSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == false){
            if(!firstNote){
                firstNote = 1;
                if(note != '') note += '\n';
                note += ' Code:';
            }
            note += (' ' + $('#' + $(this).attr('id') + '-text').html() + ';');
        }
    });

    firstNote = 0;
    $("#elecSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == false){
            if(!firstNote){
                firstNote = 1;
                if(note != '') note += '\n';
                note += ' Electrical:';
            }
            note += (' ' + $('#' + $(this).attr('id') + '-text').html() + ';');
        }
    });

    firstNote = 0;
    $("#structSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == false){
            if(!firstNote){
                firstNote = 1;
                if(note != '') note += '\n';
                note += ' Structural:';
            }
            note += (' ' + $('#' + $(this).attr('id') + '-text').html() + ';');
        }
    });

    if(note)
        note = 'Items Different between iRoof and Plans:\n' + note;

    $("#addChatMsg").val($("#addChatMsg").val() == "" ? note : $("#addChatMsg").val() + "\n" + note);
}

function eSealUpload(){
    $.ajax({
        url:"setESeal",
        type:'post',
        data:{projectId: $('#projectId').val(), value: 1},
        success:function(res){
            if (res.success == true) {
                window.location.href = "{{ route('projectlist') }}";
            }
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
}

function openReportFiles(){
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"getReportList",
        type:'post',
        data:{projectId: $('#projectId').val()},
        success:function(res){
            swal.close();
            if (res.success == true && res.data) {
                res.data.forEach(link => {
                    let filelink = (res.reportpath + link).split(" ").join("-");
                    window.open(filelink, '_blank');
                });
            }
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
}
</script>