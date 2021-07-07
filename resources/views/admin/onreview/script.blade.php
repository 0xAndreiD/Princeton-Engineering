<script>
var popUp;

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.onbeforeunload = function() {
        if (popUp && !popUp.closed) {
            popUp.close();
        }
    };
    window.onhashchange = function() {
        if (popUp && !popUp.closed) {
            popUp.close();
        }
    };

    var projectId = $('#projectId').val();

    window.setInterval(function(){
        $.ajax({
            url:"setReviewer",
            type:'post',
            data:{projectId: projectId},
            success:function(res){
                if (res.success == true) {
                    console.log('Reviewer set success!');
                }
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                message = res.message;
                console.log('Error: ' + res.message);
            }
        });
    }, 3000);
    
    $.ajax({
        url:"getProjectJson",
        type:'post',
        data:{projectId: projectId},
        success:function(res){
            var jobData = JSON.parse(res.data);
            if (res && res.success) {
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
                                let description = '';
                                if(jobData && jobData.LoadingCase && jobData.LoadingCase[i - 1] && jobData.LoadingCase[i - 1].RoofDataInput && jobData.LoadingCase[i - 1].RoofDataInput.A5)
                                    description = jobData.LoadingCase[i - 1].RoofDataInput.A5;
                                if(parseFloat(tabCollar) != 0)
                                    collarNotes = collarNotes + " FC" + i + " (" + description + ")" + " @ " + tabCollar + "'" + (5 * i == res.data.collarHeights.length ? "" : ",");
                            }
                            $("#ColTieKneeWalls-text").html("Collar Tie / Knee Walls: " + collarNotes);
                        } else
                            $("#ColTieKneeWalls-text").html("Collar Tie / Knee Walls: None");
                        if(res.data.summary)
                            $("#StructNotes-text").html("Structural Notes: " + res.data.summary + ' ...');
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

    $(document).on('submit','#submitChat', function(event){
        event.preventDefault();
		$('#send').attr('disabled','disabled');
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
		var formData = $(this).serialize();
		$.ajax({
			url:"submitChat",
			method:"POST",
			data:formData,
			success:function(data){
                swal.close();
                if(!data || !data.status){
                    swal.fire({ title: "Warning", text: data && data.message ? data.message : "Failed to submit your message.", icon: "warning", confirmButtonText: `OK` });
                } else {
                    window.msgCount++;
                    $("#addChatMsg").val("");
                    let note = 'Comment ' + window.msgCount + ': ' + data.user + ' on ' + data.datetime + '\n';
                    note += '    ' + data.message + '\n\n';
                    $('#send').attr('disabled', false);
                    $("#chatLog").val(note + $('#chatLog').val());

                    $.ajax({
                        url:"setESeal",
                        type:'post',
                        data:{projectId: $('#projectId').val()},
                        success:function(res){
                            if (res.success == true) {
                                $("#Review").attr('checked', false);
                                $("#Asbuilt").attr('checked', false);
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
			}
		})
	});

    @if(Auth::user()->auto_report_open == 1)
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.ajax({
			url:"getMainJobFiles",
			method:"POST",
			data:{projectId: $('#projectId').val()},
			success:function(data){
                swal.close();
                if(data.success && data.files){
                    data.files.forEach(file => {
                        window.open(file.link, '_blank');
                    })
                } else
                    swal.fire({ title: "Warning", text: data.message, icon: "warning", confirmButtonText: `OK` });
			}
		});
    @endif

    // $("#filetree").jstree({
    //     'plugins': ["wholerow", "types"],
    //     'core': {
    //         "check_callback": true,
    //         "themes" : {
    //             "responsive": true
    //         },
    //     },
    //     "types" : {
    //         "folder" : { "icon" : "fa fa-folder m--font-warning" },
    //         "file" : {"icon" : "fa fa-file m--font-warning" }
    //     },  
    // });

    // $("#filetree").on("select_node.jstree",
    //     function(evt, data){
    //         if(data && data.node && data.node.original && data.node.original.link){
    //             window.open(data.node.original.link, '_blank');
    //         }
    //     }
    // );
});

function checkboxAll(){
    $("input[type=checkbox]:enabled").each(function() {
        if($(this).attr('id') != 'Review' && $(this).attr('id') != 'Asbuilt'){
            $(this).prop('checked', $("#ControlCheck")[0].checked);
        }
    });
}
@if(Auth::user()->userrole != 4)
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
@endif

function clearNote(){
    $("#addChatMsg").val("");
}

function autoNote(){
    let note = '';
    let firstNote = 0;

    $("#envSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == true){
            if(!firstNote){
                firstNote = 1;
                note += ' Environmental:';
            }
            note += (' ' + $('#' + $(this).attr('id') + '-text').html() + ';');
        }
    });
    
    firstNote = 0;
    $("#codeSection input[type=checkbox]:enabled").each(function() {
        if($(this)[0].checked == true){
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
        if($(this)[0].checked == true){
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
        if($(this)[0].checked == true){
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
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"setESeal",
        type:'post',
        data:{projectId: $('#projectId').val()},
        success:function(res){
            swal.close();
            if (res.success == true) {
                $("#Review").attr('checked', false);
                $("#Asbuilt").attr('checked', false);
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

function openJobFiles(url){
    popUp = window.open(url,'targetWindow', `toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1000, height=500`);
    return false;
}

// function showReportDlg(){
//     swal.fire({ title: "Please wait...", showConfirmButton: false });
//     swal.showLoading();
//     $.ajax({
//         url:"getReportList",
//         type:'post',
//         data:{projectId: $('#projectId').val()},
//         success:function(res){
//             swal.close();
//              if (res.success == true && res.files) {
//                 $("#treeDlgTitle").html("Reports");
//                 $("#filetree").jstree('delete_node', $("#filetree").jstree(true).get_node('#').children);

//                 res.files.forEach((link, index) => {
//                     $("#filetree").jstree('create_node', null, {"text": link.filename + ' (' + parseInt(link.size / 1024) + 'KB / ' + link.modifiedDate + ')', "id": "item_" + index, "type": "file", "link": link.link}, 'last');
//                 });

//                 $("#treeDlg").modal();
//              }
//         },
//         error: function(xhr, status, error) {
//             res = JSON.parse(xhr.responseText);
//             message = res.message;
//             swal.fire({ title: "Error",
//                     text: message == "" ? "Error happened while processing. Please try again later." : message,
//                     icon: "error",
//                     confirmButtonText: `OK` });
//         }
//     });
// }

// function showInDirDlg(){
//     swal.fire({ title: "Please wait...", showConfirmButton: false });
//     swal.showLoading();
//     $.ajax({
//         url:"getInDIRList",
//         type:'post',
//         data:{projectId: $('#projectId').val()},
//         success:function(res){
//             swal.close();
//              if (res.success == true && res.files) {
//                 $("#treeDlgTitle").html("In Directory");
//                 $("#filetree").jstree('delete_node', $("#filetree").jstree(true).get_node('#').children);

//                 res.files.forEach((link, index) => {
//                     $("#filetree").jstree('create_node', null, {"text": link.filename + ' (' + parseInt(link.size / 1024) + 'KB / ' + link.modifiedDate + ')', "id": "item_" + index, "type": "file", "link": link.link}, 'last');
//                 });

//                 $("#treeDlg").modal();
//              }
//         },
//         error: function(xhr, status, error) {
//             res = JSON.parse(xhr.responseText);
//             message = res.message;
//             swal.fire({ title: "Error",
//                     text: message == "" ? "Error happened while processing. Please try again later." : message,
//                     icon: "error",
//                     confirmButtonText: `OK` });
//         }
//     });
// }
</script>