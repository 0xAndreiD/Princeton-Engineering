<script>
var canvas;
function drawBackground(){
    return new Promise((resolve, reject) => {
        $.ajax({
            url:"getSealImg",
            type:'post',
            success:function(res){
                if (res.status == true) {
                    fabric.Image.fromURL(res.url + '?' + new Date().getTime(), function(image) {
                        canvas.setHeight(image.height * 900 / image.width);
                        image.set({
                            scaleX: canvas.width / image.width,
                            scaleY: canvas.height / image.height
                        })

                        canvas.setBackgroundImage(image);
                        canvas.renderAll();
                    });
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
}

function saveContent(){
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    $.ajax({
        url:"saveSealData",
        type:'post',
        data:{
            data: JSON.stringify(canvas.toJSON())
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

function loadContent(){
    $.ajax({
        url:"loadSealData",
        type:'post',
        data:{
            data: JSON.stringify(canvas.toJSON())
        },
        success:function(res){
            swal.close();
            if (res.status == true) {
                var data = JSON.parse(res.data);
                canvas.loadFromJSON(data, function(){
                    canvas.getObjects().forEach(object => { addEffect(object) });
                    canvas.renderAll();
                });
                if(data.backgroundImage && data.backgroundImage.src){
                    fabric.Image.fromURL(data.backgroundImage.src + '?' + new Date().getTime(), function(image) {
                        canvas.setHeight(image.height * 900 / image.width);
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

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    canvas = new fabric.Canvas('canvasPane');
    canvas.setWidth(900);
    
    loadContent();

    var inputs = document.querySelectorAll( '.inputfile' );
    Array.prototype.forEach.call( inputs, function( input )
    {
        var label	 = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener( 'change', function( e )
        {
            var fileName = '';
            fileName = e.target.value.split("\\").pop();

            if( fileName )
                label.querySelector( 'span' ).innerHTML = fileName;
            else
                label.innerHTML = labelVal;

            if($('#file')[0].files && $('#file')[0].files[0]){
                swal.fire({ title: "Please wait...", showConfirmButton: false });
                swal.showLoading();

                var formData = new FormData();
                formData.append('upl', $('#file')[0].files[0]);

                $.ajax({
                    url:"extractImgFromPDF",
                    type:'post',
                    data:formData,
                    processData: false,
                    contentType: false,
                    success:function(res){
                        swal.close();
                        if (res.status == true) {
                            drawBackground();
                        } else {
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
                if(ui.draggable.prop('id') == 'sealImageBtn'){
                    var imgTag = new Image();
                    imgTag.src = "{{ asset('img/sampleseal.png') }}";
                    imgTag.onload = (e) => {
                        const image = new fabric.Image(imgTag, {
                            left: ui.offset.left - $(this).offset().left,
                            top: ui.offset.top - $(this).offset().top
                        });

                        addEffect(image);
                        canvas.add(image);
                        canvas.renderAll();
                    }
                }
                if(ui.draggable.prop('id') == 'sealTextBtn'){
                    const text = new fabric.Textbox("Sample Text", {
                        left: ui.offset.left - $(this).offset().left,
                        top: ui.offset.top - $(this).offset().top,
                        fontSize: 24,
                        fontFamily: 'Verdana',
                        width: 150
                    });

                    addEffect(text);
                    canvas.add(text);
                    canvas.renderAll();
                }
            }
        },
    });

    var canvasWrapper = document.getElementsByClassName('canvas-container')[0];
    canvasWrapper.tabIndex = 1000;
    canvasWrapper.addEventListener("keydown", keyboardEvent, false);

    canvas.on({ "selection:created" : selectionHandler, "selection:updated" : selectionHandler });
});
</script>