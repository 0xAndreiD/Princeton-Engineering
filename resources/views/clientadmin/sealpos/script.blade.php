<script>
var canvas;
var pageWidth;
var pageHeight;
function drawBackground(state){
    return new Promise((resolve, reject) => {
        $.ajax({
            url:"getSealImg",
            type:'post',
            data: {state: state},
            success:function(res){
                if (res.status == true) {
                    fabric.Image.fromURL(res.url + '?' + new Date().getTime(), function(image) {
                        swal.close();
                        canvas.setHeight(image.height * 900 / image.width);
                        image.set({
                            scaleX: canvas.width / image.width,
                            scaleY: canvas.height / image.height
                        });
                        pageWidth = image.width / 200;
                        pageHeight = image.height / 200;
                        $("#page-width").val(pageWidth.toFixed(2));
                        $("#page-height").val(pageHeight.toFixed(2));

                        canvas.setBackgroundImage(image);
                        canvas.renderAll();
                    });
                } else
                    swal.close();
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                var message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
    });
}

function addEffect(object){
    object.cornerStyle = 'circle';
    object.borderColor = '#000000';
    object.cornerColor = '#ffffff';
    object.cornerSize = 10;
    object.cornerStrokeColor = '#000000';
    object.setControlsVisibility({tl: true, tr: true, bl: true, br: true, ml: true, mt: true, mr: true, mb: true, mtr: false });
    object.setCoords();
}

function keyboardEvent(event){
    if(event.keyCode == 46){
        var activeObjects = canvas.getActiveObjects();
        canvas.discardActiveObject();
        for(let i = 0; i < activeObjects.length; i ++){
            canvas.remove(activeObjects[i]);
        }
        canvas.renderAll();
    }
}

function selectionHandler(){
    let object = canvas.getActiveObject();
    if(object)
        object.setControlsVisibility({tl: true, tr: true, bl: true, br: true, ml: true, mt: true, mr: true, mb: true, mtr: false });
    if(canvas.getActiveObjects().length == 1){
        $("#object-dimension-pane").css("display", "block");
        dimUpdateHandler();
    }
    else
        $("#object-dimension-pane").css("display", "none");
}

function dimUpdateHandler(){
    if(canvas.getActiveObjects().length == 1){
        let object = canvas.getActiveObject();
        $("#object-left").val((object.left * pageWidth / canvas.width).toFixed(2));
        $("#object-top").val((object.top * pageHeight / canvas.height).toFixed(2));
        $("#object-width").val((object.width * object.scaleX * pageWidth / canvas.width).toFixed(2));
        $("#object-height").val((object.height * object.scaleY * pageHeight / canvas.height).toFixed(2));
    }
}

function saveContent(){
    let state = $("#state-dropdown").html();
    if(!state || state.length != 2){
        swal.fire({ title: "Warning", text: 'Please select the state.', icon: "info", confirmButtonText: `OK` });
        return;
    }

    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    var objects = [];
    canvas.getObjects().forEach(object => {
        if(object.type == 'image') objects.push({type: object.type, scaleX: object.scaleX, left: object.left, top: object.top, width: object.width, height: object.height});
        if(object.type == 'textbox') objects.push({type: object.type, fontSize: object.fontSize, text: object.text, left: object.left, top: object.top, width: object.width, height: object.height});
    });

    $.ajax({
        url:"saveSealData",
        type:'post',
        data:{
            data: JSON.stringify(canvas.toJSON()),
            state: state,
            pageWidth: pageWidth,
            pageHeight: pageHeight,
            canvasWidth: canvas.width,
            canvasHeight: canvas.height,
            objects: objects
        },
        success:function(res){
            swal.close();
            if (res.status == true) {
                swal.fire({ title: "Success", text: "Successfully Saved!", icon: "success", confirmButtonText: `OK` });
            } else 
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            var message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
}

function updateTemplateList(){
    $.ajax({
        url:"getTemplateList",
        type:'post',
        success:function(res){
            if (res.status == true && res.data) {
                $("#template-list").html("");
                res.data.forEach(template => {
                    $("#template-list").append("<a class='dropdown-item' href='javascript:loadTemplate(\"" + template.state + "\")'>" + template.title + "</a>");
                })
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            var message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
}

function loadTemplate(wantedState){
    let state = $("#state-dropdown").html();
    if(!state || state.length != 2){
        swal.fire({ title: "Warning", text: 'Please select the state.', icon: "info", confirmButtonText: `OK` });
        return;
    }
    if(state == wantedState) return;


    loadContent(wantedState);
}

function saveAsTemplate(){
    let state = $("#state-dropdown").html();
    if(!state || state.length != 2){
        swal.fire({ title: "Warning", text: 'Please select the state.', icon: "info", confirmButtonText: `OK` });
        return;
    }
    swal.fire({ title: "Input the template title", input: 'text', confirmButtonText: `OK`, showCancelButton: true }).then((result => {
        if(result && result.value){
            swal.fire({ title: "Please wait...", showConfirmButton: false });
            swal.showLoading();
            $.ajax({
                url:"saveAsTemplate",
                type:'post',
                data:{
                    state: state,
                    template: result.value
                },
                success:function(res){
                    swal.close();
                    if (res.status == true) {
                        swal.fire({ title: "Success", text: "Successfully Saved!", icon: "success", confirmButtonText: `OK` });
                        updateTemplateList();
                    } else 
                        swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                },
                error: function(xhr, status, error) {
                    res = JSON.parse(xhr.responseText);
                    var message = res.message;
                    swal.fire({ title: "Error",
                            text: message == "" ? "Error happened while processing. Please try again later." : message,
                            icon: "error",
                            confirmButtonText: `OK` });
                }
            });
        }
    }));
}

function loadContent(state){
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"loadSealData",
        type:'post',
        data:{
            state: state
        },
        success:function(res){
            swal.close();
            if (res.status == true) {
                var data = JSON.parse(res.data);
                canvas.loadFromJSON(data, function(){
                    canvas.getObjects().forEach(object => { 
                        addEffect(object);
                        if(object.type == 'textbox')
                            object.set({editable: false});
                    });
                    canvas.renderAll();
                });
                if(data.backgroundImage && data.backgroundImage.src){
                    fabric.Image.fromURL(data.backgroundImage.src, function(image) {
                        canvas.setHeight(image.height * 900 / image.width);
                        pageWidth = image.width / 200;
                        pageHeight = image.height / 200;
                        $("#page-width").val(pageWidth.toFixed(2));
                        $("#page-height").val(pageHeight.toFixed(2));
                        
                        image.set({
                            scaleX: canvas.width / image.width,
                            scaleY: canvas.height / image.height
                        })

                        canvas.setBackgroundImage(image);
                        canvas.renderAll();
                    });
                }
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            var message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
}

function stateChange(state){
    $("#state-dropdown").html(state);
    canvas.clear();
    loadContent(state);
}

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    canvas = new fabric.Canvas('canvasPane');
    canvas.setWidth(900);

    updateTemplateList();

    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        // var label	 = input.nextElementSibling,
        //     labelVal = label.innerHTML;

        input.addEventListener( 'change', function( e )
        {
            // var fileName = '';
            // fileName = e.target.value.split("\\").pop();

            // if( fileName )
            //     label.querySelector( 'span' ).innerHTML = fileName;
            // else
            //     label.innerHTML = labelVal;
            let state = $("#state-dropdown").html();
            if(!state || state.length != 2){
                $(".inputfile").val('');
                swal.fire({ title: "Warning", text: 'Please select the state.', icon: "info", confirmButtonText: `OK` });
                return;
            }

            if($('#file')[0].files && $('#file')[0].files[0]){
                swal.fire({ title: "Please wait...", showConfirmButton: false });
                swal.showLoading();

                var formData = new FormData();
                formData.append('upl', $('#file')[0].files[0]);
                formData.append('state', state);

                $.ajax({
                    url:"extractImgFromPDF",
                    type:'post',
                    data:formData,
                    processData: false,
                    contentType: false,
                    success:function(res){
                        $(".inputfile").val('');
                        if (res.status == true) {
                            drawBackground(state);
                        } else {
                            swal.close();
                            // error handling
                            swal.fire({ title: "Error",
                                text: "Error happened while processing. Please try again later.",
                                icon: "error",
                                confirmButtonText: `OK` });
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.close();
                        res = JSON.parse(xhr.responseText);
                        var message = res.message;
                        swal.fire({ title: "Error",
                                text: message == "" ? "Error happened while processing. Please try again later." : message,
                                icon: "error",
                                confirmButtonText: `OK` });
                    }
                });
            }
        });
    });

    $(".draggable").draggable({
        cancel :false,
        containment: "window",
        scroll: false,
        helper: 'clone'
    });

    $("#canvasPane").droppable({
        accept: ".draggable",
        activeClass: "canvashighlight",
        drop: function (event, ui) {
            if(ui && ui.draggable){
                let elemId = ui.draggable.prop('id');
                if(elemId){
                    let checkObj;
                    if(elemId == 'sealImage')
                        checkObj = canvas.getObjects().filter(e => e.type == 'image');
                    else if(elemId == 'sealText')
                        checkObj = canvas.getObjects().filter(e => e.type == 'textbox' && e.text == 'Seal Text');
                    else if(elemId == 'sealSupplement')
                        checkObj = canvas.getObjects().filter(e => e.type == 'textbox' && e.text == 'Supplemental Seal Text');
                    else if(elemId == 'eSign')
                        checkObj = canvas.getObjects().filter(e => e.type == 'textbox' && e.text == 'eSign Text');

                    if(checkObj && checkObj[0])
                        swal.fire({ title: "Warning", text: 'You already have the element.', icon: "info", confirmButtonText: `OK` });
                    else{
                        if(elemId == 'sealImage'){
                            var imgTag = new Image();
                            imgTag.src = "{{ asset('img/sampleseal.png') }}";
                            imgTag.onload = (e) => {
                                const image = new fabric.Image(imgTag, {
                                    left: ui.offset.left - $(this).offset().left,
                                    top: ui.offset.top - $(this).offset().top,
                                    type: 'image'
                                });

                                addEffect(image);
                                canvas.add(image);
                                canvas.renderAll();
                            }
                        }
                        else if(elemId == 'sealText'){
                            const text = new fabric.Textbox("Seal Text", {
                                left: ui.offset.left - $(this).offset().left,
                                top: ui.offset.top - $(this).offset().top,
                                fontSize: 24,
                                fontFamily: 'Verdana',
                                width: 150,
                                textAlign: 'center',
                                editable: false,
                                type: 'textbox'
                            });

                            addEffect(text);
                            canvas.add(text);
                            canvas.renderAll();
                        } else if(elemId == 'sealSupplement'){
                            const text = new fabric.Textbox("Supplemental Seal Text", {
                                left: ui.offset.left - $(this).offset().left,
                                top: ui.offset.top - $(this).offset().top,
                                fontSize: 24,
                                fontFamily: 'Verdana',
                                width: 150,
                                textAlign: 'center',
                                editable: false,
                                type: 'textbox'
                            });

                            addEffect(text);
                            canvas.add(text);
                            canvas.renderAll();
                        } else if(elemId == 'eSign'){
                            const text = new fabric.Textbox("eSign Text", {
                                left: ui.offset.left - $(this).offset().left,
                                top: ui.offset.top - $(this).offset().top,
                                fontSize: 24,
                                fontFamily: 'Verdana',
                                width: 150,
                                textAlign: 'center',
                                editable: false,
                                type: 'textbox'
                            });

                            addEffect(text);
                            canvas.add(text);
                            canvas.renderAll();
                        }
                    }
                }
            }
        },
    });

    var canvasWrapper = document.getElementsByClassName('canvas-container')[0];
    canvasWrapper.tabIndex = 1000;
    canvasWrapper.addEventListener("keydown", keyboardEvent, false);

    canvas.on({ "selection:created" : selectionHandler, "selection:updated" : selectionHandler, "selection:cleared" : selectionHandler });
    canvas.on({ "object:moving" : dimUpdateHandler, "object:scaling": dimUpdateHandler });

    $("#object-left").on('input', function(){
        let object = canvas.getActiveObject();
        object.left = $(this).val() * canvas.width / pageWidth;
        canvas.renderAll();
    });

    $("#object-top").on('input', function(){
        let object = canvas.getActiveObject();
        object.top = $(this).val() * canvas.height / pageHeight;
        canvas.renderAll();
    });

    $("#object-width").on('input', function(){
        let object = canvas.getActiveObject();
        let newWidth = $(this).val() * canvas.width / pageWidth;
        object.scaleX = object.scaleX * (newWidth / (object.scaleX * object.width));
        canvas.renderAll();
    });

    $("#object-height").on('input', function(){
        let object = canvas.getActiveObject();
        let newHeight = $(this).val() * canvas.height / pageHeight;
        object.scaleY = object.scaleY * (newHeight / (object.scaleY * object.height));
        canvas.renderAll();
    });
});
</script>