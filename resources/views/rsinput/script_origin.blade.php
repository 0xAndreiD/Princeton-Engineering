<script>
window.conditionId = 1;
var domChanged = false;
function openRfdTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("rfdTabContent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    
    if (tabName == "tab_test")
        document.getElementById('subPageTitle').innerHTML = 'test';
    else if (tabName == "electric")
        document.getElementById('subPageTitle').innerHTML = 'Electrical Data Input';
    else if( tabName == "tab_first" )
        document.getElementById('subPageTitle').innerHTML = 'Site and Equipment Data Input';
    else if( tabName == "tab_override" )
        document.getElementById('subPageTitle').innerHTML = 'Custom Program Data Overrides';
    else if( tabName == "tab_upload" )
        document.getElementById('subPageTitle').innerHTML = 'Multiple Documents Upload';
    else if( tabName == "tab_permit" )
        document.getElementById('subPageTitle').innerHTML = 'Permit Info Filling';
    else if( tabName == "tab_PIL" )
        document.getElementById('subPageTitle').innerHTML = 'Post Installation Letter Filling';
    else 
    {
        window.conditionId = parseInt(tabName.slice(3));
        document.getElementById('subPageTitle').innerHTML = 'Framing Data Input';
    }
    
    console.log('Tab No:', window.conditionId);
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();

function fcChangeType( conditionId, type ){
    if( type == 1 ) // Truss
    {
        $(`#label-A-1-${conditionId}`).attr('rowspan', 8);
        $(`#label-B-1-${conditionId}`).attr('rowspan', 5);
        $(`#label-B-1-${conditionId}`)[0].style.display = "none";
        $(`#title-B-3-${conditionId}`)[0].style.display = "table-cell";
        $(`#label-G-1-${conditionId}`).attr('rowspan', 4);
        
        var elements = $(`#inputform-${conditionId} .class-IBC-hide`); // show all IBC
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-GroundMount-hide`); // show all GroundMount
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-truss-hide`); // hide all truss
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'none';

        $(`#label-A-2-${conditionId}`).html('Roof Average Height');
        $(`#label-A-3-${conditionId}`).html('Plan View Length of Building Section');
        $(`#label-A-4-${conditionId}`).html('Plan View Width of Building Section');
        $(`#label-A-7-${conditionId}`).html('Roof Slope');
        $(`#label-A-8-${conditionId}`).html('Diagonal Rafter Length from Plate to Ridge');
        $(`#label-A-9-${conditionId}`).html('Rise from Truss Plate to Top Ridge');
        $(`#label-A-10-${conditionId}`).html('Horiz Len from Outside of Truss Plate to Ridge');
        $(`#label-A-11-${conditionId}`).html('Diagonal Overhang Length past Truss Plate');
        $(`#label-B-3-${conditionId}`).html('Truss Spacing - Center to Center');
        $(`#label-B-4-${conditionId}`).html('Truss Material');
        $(`#label-F-1-${conditionId}`).html('Maximum # Modules along Truss');
        document.getElementById(`trussInput-${conditionId}`).style.display = "block";

        $(`#input-f-1-${conditionId}`).html(`<select id="f-1-${conditionId}" tabindex="47" onchange="maxModuleNumChange(${conditionId})">\
            <option data-value="1">1</option>\
            <option data-value="2" selected="">2</option>\
            <option data-value="3">3</option>\
            <option data-value="4">4</option>\
            <option data-value="5">5</option>\
            <option data-value="6">6</option>\
            <option data-value="7">7</option>\
            <option data-value="8">8</option>\
            <option data-value="9">9</option>\
            <option data-value="10">10</option>\
            <option data-value="11">11</option>\
            <option data-value="12">12</option>\
        </select>`);
        maxModuleNumChange(conditionId);

        $(`#label-D-0-${conditionId}`).attr('rowspan', 7);
        $(`#inputform-${conditionId} .class-IBC-only`).css('display', 'none');

        $(`#f-1-${conditionId}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });

        drawTrussGraph(conditionId);
    }    
    else if(type == 0) // Stick
    {
        $(`#label-A-1-${conditionId}`).attr('rowspan', 12);
        $(`#label-B-1-${conditionId}`).attr('rowspan', 5);
        $(`#label-B-1-${conditionId}`)[0].style.display = "table-cell";
        $(`#title-B-3-${conditionId}`)[0].style.display = "none";
        $(`#label-G-1-${conditionId}`).attr('rowspan', 4);
        
        var elements = $(`#inputform-${conditionId} .class-IBC-hide`); // show all IBC
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-truss-hide`); // show all Truss
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-GroundMount-hide`); // show all GroundMount
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';

        $(`#label-A-2-${conditionId}`).html('Roof Average Height');
        $(`#label-A-3-${conditionId}`).html('Plan View Length of Building Section');
        $(`#label-A-4-${conditionId}`).html('Plan View Width of Building Section');
        $(`#label-A-7-${conditionId}`).html('Roof Slope');
        $(`#label-A-8-${conditionId}`).html('Diagonal Rafter Length from Plate to Ridge');
        $(`#label-A-9-${conditionId}`).html('Rise from Rafter Plate to Top Ridge');
        $(`#label-A-10-${conditionId}`).html('Horiz Len from Outside of Rafter Plate to Ridge');
        $(`#label-A-11-${conditionId}`).html('Diagonal Overhang Length past Rafter Plate');
        $(`#label-B-3-${conditionId}`).html('Joist Spacing - Center to Center');
        $(`#label-B-4-${conditionId}`).html('Rafter Material');
        $(`#label-F-1-${conditionId}`).html('Maximum # Modules along Rafter');
        document.getElementById(`trussInput-${conditionId}`).style.display = "none";

        $(`#input-f-1-${conditionId}`).html(`<select id="f-1-${conditionId}" tabindex="47" onchange="maxModuleNumChange(${conditionId})">\
            <option data-value="1">1</option>\
            <option data-value="2" selected="">2</option>\
            <option data-value="3">3</option>\
            <option data-value="4">4</option>\
            <option data-value="5">5</option>\
            <option data-value="6">6</option>\
            <option data-value="7">7</option>\
            <option data-value="8">8</option>\
            <option data-value="9">9</option>\
            <option data-value="10">10</option>\
            <option data-value="11">11</option>\
            <option data-value="12">12</option>\
        </select>`);
        maxModuleNumChange(conditionId);

        $(`#label-D-0-${conditionId}`).attr('rowspan', 7);
        $(`#inputform-${conditionId} .class-IBC-only`).css('display', 'none');

        $(`#f-1-${conditionId}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });

        drawStickGraph(conditionId);
    } else if(type == 2){ // IBC 5%
        $(`#label-A-1-${conditionId}`).attr('rowspan', 10);
        $(`#label-B-1-${conditionId}`).attr('rowspan', 3);
        $(`#label-B-1-${conditionId}`)[0].style.display = "table-cell";
        $(`#title-B-3-${conditionId}`)[0].style.display = "none";
        $(`#label-G-1-${conditionId}`).attr('rowspan', 4);
        
        var elements = $(`#inputform-${conditionId} .class-truss-hide`); // show all Truss
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-GroundMount-hide`); // show all GroundMount
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-IBC-hide`); // hide all IBC
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'none';

        for(let i = 1; i <= 12; i ++)
            $(`#Module-${i}-${conditionId}`)[0].style.display = "none";

        $(`#label-A-2-${conditionId}`).html('Roof Average Height');
        $(`#label-A-3-${conditionId}`).html('Plan View Length of Building Section');
        $(`#label-A-4-${conditionId}`).html('Plan View Width of Building Section');
        $(`#label-A-7-${conditionId}`).html('Roof Slope');
        $(`#label-A-8-${conditionId}`).html('Diagonal Rafter Length from Plate to Ridge');
        $(`#label-A-9-${conditionId}`).html('Rise from Rafter Plate to Top Ridge');
        $(`#label-A-10-${conditionId}`).html('Horiz Len from Outside of Rafter Plate to Ridge');
        $(`#label-A-11-${conditionId}`).html('Diagonal Overhang Length past Rafter Plate');
        $(`#label-B-3-${conditionId}`).html('Joist Spacing - Center to Center');
        $(`#label-B-4-${conditionId}`).html('Rafter Material');
        $(`#label-F-1-${conditionId}`).html('# Modules on Roof Plane');
        document.getElementById(`trussInput-${conditionId}`).style.display = "none";

        $(`#input-f-1-${conditionId}`).html(`<input id="f-1-${conditionId}" tabindex="47" value="2" type="number" min="1" style="overflow: hidden;border: 0px;width: 100%;background: transparent;padding-left: 4px;text-align: center;">`);
        $(`#f-1-${conditionId}`).on('keypress', function(event){
            if(event.key == "-")
            {
                event.preventDefault();
                return false;
            }
        });

        $(`#label-D-0-${conditionId}`).attr('rowspan', 10);
        $(`#inputform-${conditionId} .class-IBC-only`).css('display', 'table-row');

        $(`#f-1-${conditionId}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });

        drawStickGraph(conditionId);
    } else if(type == 3){ // Ground Mount
        $(`#label-A-1-${conditionId}`).attr('rowspan', 11);
        $(`#label-B-1-${conditionId}`).attr('rowspan', 3);
        $(`#label-B-1-${conditionId}`)[0].style.display = "table-cell";
        $(`#title-B-3-${conditionId}`)[0].style.display = "none";
        $(`#label-G-1-${conditionId}`).attr('rowspan', 3);
        
        var elements = $(`#inputform-${conditionId} .class-truss-hide`); // show all truss
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-IBC-hide`); // show all IBC
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'table-row';
        elements = $(`#inputform-${conditionId} .class-GroundMount-hide`); // hide all GroundMount
        for(let i = 0; i < elements.length; i ++)
            elements[i].style.display = 'none';

        $(`#label-A-2-${conditionId}`).html('Average Height');
        $(`#label-A-3-${conditionId}`).html('Plan View Length');
        $(`#label-A-4-${conditionId}`).html('Plan View Width');
        $(`#label-A-7-${conditionId}`).html('Slope');
        $(`#label-A-8-${conditionId}`).html('Slope Length');
        $(`#label-A-9-${conditionId}`).html('Rise');
        $(`#label-A-10-${conditionId}`).html('Horiz Len');
        $(`#label-A-11-${conditionId}`).html('Diagonal Overhang Length past Bottom Rail');
        $(`#label-F-1-${conditionId}`).html('Maximum # Modules along Slope');
        document.getElementById(`trussInput-${conditionId}`).style.display = "none";

        $(`#input-f-1-${conditionId}`).html(`<select id="f-1-${conditionId}" tabindex="47" onchange="maxModuleNumChange(${conditionId})">\
            <option data-value="1">1</option>\
            <option data-value="2" selected="">2</option>\
            <option data-value="3">3</option>\
            <option data-value="4">4</option>\
            <option data-value="5">5</option>\
            <option data-value="6">6</option>\
            <option data-value="7">7</option>\
            <option data-value="8">8</option>\
            <option data-value="9">9</option>\
            <option data-value="10">10</option>\
            <option data-value="11">11</option>\
            <option data-value="12">12</option>\
        </select>`);
        maxModuleNumChange(conditionId);

        $(`#label-D-0-${conditionId}`).attr('rowspan', 7);
        $(`#inputform-${conditionId} .class-IBC-only`).css('display', 'none');

        $(`#f-1-${conditionId}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });

        drawStickGraph(conditionId);
    }
}

function maxModuleNumChange( conditionId ){
    var moduleNum = $(`#f-1-${conditionId}`).val();
    let i;
    $(`#Module-Left-Text-${conditionId}`).attr('rowspan', Math.min(moduleNum, 12));
    for(i = 1; i <= 12 && i <= moduleNum; i ++)
    {
        $(`#Module-${i}-${conditionId}`)[0].style.display = "table-row";
    }
    for(; i <= 12; i ++)
    {
        $(`#Module-${i}-${conditionId}`)[0].style.display = "none";
    }
}

// ---------- Canvas Code -------------
var grid_size = [25, 25, 25, 25, 25, 25, 25, 25 , 25, 25, 25];
var x_axis_starting_point = new Array(11); x_axis_starting_point.fill({ number: 1, suffix: '' });
var y_axis_starting_point = new Array(11); y_axis_starting_point.fill({ number: 1, suffix: '' });

var canvas = new Array(11);
var ctx = new Array(11);
var canvas_width = new Array(11);
var canvas_height = new Array(11);
var num_lines_x = new Array(11);
var num_lines_y = new Array(11);
var x_axis_distance_grid_lines = new Array(11);
var y_axis_distance_grid_lines = new Array(11);
var show_axis = new Array(11);
for( let i = 1; i <= 10; i ++ )
{
    canvas[i] = document.getElementById(`canvas-${i}`);
    ctx[i] = canvas[i].getContext("2d");
    canvas_width[i] = canvas[i].width;
    canvas_height[i] = canvas[i].height;
    num_lines_x[i] = Math.floor(canvas_height[i] / grid_size[i]);
    num_lines_y[i] = Math.floor(canvas_width[i] / grid_size[i]);
    x_axis_distance_grid_lines[i] = num_lines_x[i] - 1;
    y_axis_distance_grid_lines[i] = 0;
    show_axis[i] = false;
    
    // Translate to the new origin. Now Y-axis of the canvas is opposite to the Y-axis of the graph. So the y-coordinate of each element will be negative of the actual
    ctx[i].translate(y_axis_distance_grid_lines[i] * grid_size[i], x_axis_distance_grid_lines[i] * grid_size[i]);
}
//Let's call conditionId as condId here
var drawBaseLine = function( condId ) {
    // erase
    // ctx.clearRect( 0, grid_size, canvas_width, - canvas_height);
    ctx[condId].clearRect( 0, 100, canvas_width[condId] + 100, - canvas_height[condId] - 100);

    var angleRadian = degreeToRadian( parseFloat($(`#txt-roof-degree-${condId}`).val()) );
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
    var overhangX = Math.floor(overhangLength * grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * grid_size[condId] * Math.sin(angleRadian));

    ctx[condId].translate(overhangX, - overhangY);
    
    if( show_axis[condId] )
    {
        // Draw grid lines along X-axis
        for(var i = 0; i <= num_lines_x[condId]; i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            
            // If line represents X-axis draw in different color
            if(i == 0/*x_axis_distance_grid_lines*/) 
                ctx[condId].strokeStyle = "#000000";
            else
                ctx[condId].strokeStyle = "#e9e9e9";
            
            if(i == num_lines_x[condId]) {
                ctx[condId].moveTo(0, grid_size[condId] * i);
                ctx[condId].lineTo(canvas_width[condId], grid_size[condId] * i);
            }
            else {
                ctx[condId].moveTo(0, -grid_size[condId] * i + 0.5);
                ctx[condId].lineTo(canvas_width[condId], -grid_size[condId] * i + 0.5);
            }
            ctx[condId].stroke();
        }

        // Draw grid lines along Y-axis
        for(i = 0; i <= num_lines_y[condId]; i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            
            // If line represents X-axis draw in different color
            if(i == y_axis_distance_grid_lines[condId]) 
                ctx[condId].strokeStyle = "#000000";
            else
                ctx[condId].strokeStyle = "#e9e9e9";
            
            if(i == num_lines_y[condId]) {
                ctx[condId].moveTo(grid_size[condId] * i, 0);
                ctx[condId].lineTo(grid_size[condId] * i, -canvas_height[condId]);
            }
            else {
                ctx[condId].moveTo(grid_size[condId] * i + 0.5, 0);
                ctx[condId].lineTo(grid_size[condId] * i + 0.5, -canvas_height[condId]);
            }
            ctx[condId].stroke();
        }


        // Ticks marks along the positive X-axis
        for(i = 2; i < (num_lines_y[condId] - y_axis_distance_grid_lines[condId]); i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            ctx[condId].moveTo(grid_size[condId] * i + 0.5, -3);
            ctx[condId].lineTo(grid_size[condId] * i + 0.5, 3);
            ctx[condId].stroke();

            // Text value at that point
            ctx[condId].font = '9px Arial';
            ctx[condId].textAlign = 'start';
            ctx[condId].fillStyle = '#000000';
            ctx[condId].fillText(x_axis_starting_point[condId].number * i + x_axis_starting_point[condId].suffix, grid_size[condId] * i - 2, 15);
        }

        // Ticks marks along the negative X-axis
        for(i = 2; i < y_axis_distance_grid_lines[condId]; i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            ctx[condId].moveTo(-grid_size[condId] * i + 0.5, -3);
            ctx[condId].lineTo(-grid_size[condId] * i + 0.5, 3);
            ctx[condId].stroke();

            // Text value at that point
            ctx[condId].font = '9px Arial';
            ctx[condId].textAlign = 'end';
            ctx[condId].fillStyle = '#000000';
            ctx[condId].fillText(-x_axis_starting_point[condId].number * i + x_axis_starting_point[condId].suffix, -grid_size[condId] * i + 3, 15);
        }

        // Ticks marks along the positive Y-axis
        // Positive Y-axis of graph is negative Y-axis of the canvas
        for(i = 2; i < (num_lines_x[condId] - x_axis_distance_grid_lines[condId]); i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            ctx[condId].moveTo(-3, grid_size[condId] * i + 0.5);
            ctx[condId].lineTo(3, grid_size[condId] * i + 0.5);
            ctx[condId].stroke();

            // Text value at that point
            ctx[condId].font = '9px Arial';
            ctx[condId].textAlign = 'start';
            ctx[condId].fillStyle = '#000000';
            ctx[condId].fillText(-y_axis_starting_point[condId].number * i + y_axis_starting_point[condId].suffix, 8, grid_size[condId] * i + 3);
        }

        // Ticks marks along the negative Y-axis
        // Negative Y-axis of graph is positive Y-axis of the canvas
        for(i = 2; i < x_axis_distance_grid_lines[condId]; i += 2) {
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 1;
            ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            ctx[condId].moveTo(-3, -grid_size[condId] * i + 0.5);
            ctx[condId].lineTo(3, -grid_size[condId] * i + 0.5);
            ctx[condId].stroke();

            // Text value at that point
            ctx[condId].font = '9px Arial';
            ctx[condId].textAlign = 'start';
            ctx[condId].fillStyle = '#000000';
            ctx[condId].fillText(y_axis_starting_point[condId].number * i + y_axis_starting_point[condId].suffix, 8, -grid_size[condId] * i + 3);
        }
    }
}

var adjustDrawingPanel = function( condId, isIBC = false ) {
    var topYPoint = 0, topXPoint = 0;

    for (var key in globalRoofLines[condId]) {
        if (globalRoofLines[condId][key][0][1] > topYPoint) { topYPoint = globalRoofLines[condId][key][0][1]; }
        if (globalRoofLines[condId][key][1][1] > topYPoint) { topYPoint = globalRoofLines[condId][key][1][1]; }
        if (globalRoofLines[condId][key][0][0] > topXPoint) { topXPoint = globalRoofLines[condId][key][0][0]; }
        if (globalRoofLines[condId][key][1][0] > topXPoint) { topXPoint = globalRoofLines[condId][key][1][0]; }
    }

    // draw floor plane
    for (var key in globalFloorLines[condId]) {
        if (globalFloorLines[condId][key][0][0] > topXPoint) { topXPoint = globalFloorLines[condId][key][0][0]; } 
        if (globalFloorLines[condId][key][1][0] > topXPoint) { topXPoint = globalFloorLines[condId][key][1][0]; }
        if (globalFloorLines[condId][key][0][1] > topYPoint) { topYPoint = globalFloorLines[condId][key][0][1]; } 
        if (globalFloorLines[condId][key][1][1] > topYPoint) { topYPoint = globalFloorLines[condId][key][1][1]; }
    }
 
    // console.log("topXpoint : " + topXPoint);
    // console.log("topYpoint : " + topYPoint);

    var angle = parseFloat($(`#txt-roof-degree-${condId}`).val());
    var angleRadian = degreeToRadian(angle);
    var overhangX = parseFloat($(`#a-11-${condId}`).val()) * Math.sin(Math.PI / 2 - angleRadian);
    var overhangY = parseFloat($(`#a-11-${condId}`).val()) * Math.sin(angleRadian );

    topXPoint += overhangX;
    topYPoint += overhangY;
    
    var moduleDepth = 1.17 / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
    var moduleGap = parseFloat($(`#g-1-${condId}`).val()) / 12;

    var moduleCount = parseInt($(`#f-1-${condId}`).val());
    
    var moduleLengthSum = parseFloat($(`#e-1-${condId}`).val());
    if(!isIBC){
        var orientation = false;
        for(let i = 1; i <= moduleCount; i ++)
        {
            orientation = false;
            if($(`#a-6-${condId}`).val() == "Portrait")
                orientation = true;
            if($(`#h-${i}-${condId}`)[0].checked)
                orientation = !orientation;

            moduleLengthSum += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
        }
        moduleLengthSum -= moduleGap;

        // Show alert when module length is longer
        if(topXPoint < moduleLengthSum * Math.cos(angleRadian) || topYPoint < moduleLengthSum * Math.sin(angleRadian))
            $(`#truss-module-alert-${condId}`).css('display', 'block');
        else
            $(`#truss-module-alert-${condId}`).css('display', 'none');
    } else {
        moduleLengthSum = 0;
        moduleHeight = 0;
        $(`#truss-module-alert-${condId}`).css('display', 'none');
    }

    topXPoint = Math.max(topXPoint, moduleLengthSum * Math.cos(angleRadian));
    topYPoint = Math.max(topYPoint, moduleLengthSum * Math.sin(angleRadian) + moduleHeight * Math.sin(degreeToRadian( parseFloat($(`#g-2-${condId}`).val()) )));
    
    var xx = Math.floor((canvas_width[condId] - 100) / topXPoint);
    var yy = Math.floor((canvas_height[condId] - 100) / topYPoint);  // for height adjustment

    // if (xx > yy) { 
    //     if (grid_size > yy) { grid_size = yy; }
    // }
    // else { 
    //     if (grid_size > xx) { grid_size = xx; }
    // }

    if (xx > yy) { grid_size[condId] = yy; }
    else { grid_size[condId] = xx; }

    // adjust grid_size        
    num_lines_x[condId] = Math.floor(canvas_height[condId] / grid_size[condId]);
    num_lines_y[condId] = Math.floor(canvas_width[condId] / grid_size[condId]);
    x_axis_distance_grid_lines[condId] = num_lines_x[condId] - 1;
}

var getDistance = function(start, end) {
    return Math.sqrt((start[0] - end[0]) * (start[0] - end[0]) + (start[1] - end[1]) * (start[1] - end[1]));
}

var drawLine = function(condId, start, end, label, rotateMethod = 0) {
    
    ctx[condId].beginPath();
    ctx[condId].lineWidth = 2;
    ctx[condId].strokeStyle = "#0000FF";
    ctx[condId].moveTo(start[0] * grid_size[condId], - start[1] * grid_size[condId]);
    ctx[condId].lineTo(end[0] * grid_size[condId], - end[1] * grid_size[condId]);
    ctx[condId].stroke();
    //ctx[condId].closePath();

    // start, end points
    ctx[condId].beginPath();
    ctx[condId].fillStyle = "#0000FF";
    ctx[condId].arc(start[0] * grid_size[condId], - start[1] * grid_size[condId], 4, 0, Math.PI * 2);
    ctx[condId].fill();

    ctx[condId].beginPath();
    ctx[condId].fillStyle = "#0000FF";
    ctx[condId].arc(end[0] * grid_size[condId], - end[1] * grid_size[condId], 4, 0, Math.PI * 2);
    ctx[condId].fill();

    ctx[condId].fillStyle = "#FF0000";
    ctx[condId].translate((start[0] + end[0]) * grid_size[condId] / 2, - (start[1] + end[1]) * grid_size[condId] / 2);
    ctx[condId].rotate(Math.atan((start[0] - end[0]) / (start[1] - end[1])));
    ctx[condId].fillRect(-4, -4, 8, 8);
    ctx[condId].rotate(-Math.atan((start[0] - end[0]) / (start[1] - end[1])));
    ctx[condId].translate(- (start[0] + end[0]) * grid_size[condId] / 2, (start[1] + end[1]) * grid_size[condId] / 2);
    
    //ctx[condId].fillRect(start[0] * grid_size[condId] - 3, - start[1] * grid_size[condId] -3, 6, 6);
    //ctx[condId].fillRect(end[0] * grid_size[condId] -3 , - end[1] * grid_size[condId] -3, 6, 6);

    // label txt
    ctx[condId].fillStyle = "#000000";
    ctx[condId].font = "12px Arial";
    ctx[condId]['font-weight'] = 'bold';
    ctx[condId].translate((start[0] + end[0]) * grid_size[condId] / 2 - 4, - (start[1] + end[1]) * grid_size[condId] / 2 - 4);
    let rotateDegree;
    let xx, yy;
    if(rotateMethod == 1)
    {
        rotateDegree = Math.atan((start[0] - end[0]) / (start[1] - end[1])) - Math.PI / 2;
        xx = -8; yy = 22;
    }
    else if(rotateMethod == 2) 
    {
        rotateDegree = Math.atan((start[0] - end[0]) / (start[1] - end[1])) + Math.PI / 2;
        xx = -4; yy = -5;
    }
    else if(start[0] < end[0])
    {
        rotateDegree = Math.atan((start[0] - end[0]) / (start[1] - end[1])) + Math.PI / 2;
        xx = -4; yy = -10;
    }
    else
    {
        rotateDegree = Math.atan((start[0] - end[0]) / (start[1] - end[1])) - Math.PI / 2;
        xx = -12; yy = -5;
    }    
    ctx[condId].rotate(rotateDegree);
    ctx[condId].fillText(label, xx, yy);
    ctx[condId].rotate(-rotateDegree);
    ctx[condId].translate(- (start[0] + end[0]) * grid_size[condId] / 2 + 4, (start[1] + end[1]) * grid_size[condId] / 2 + 4);
}

var drawTrussGraph = function( condId ) {
    var isIBC = $(`#trussFlagOption-${condId}-3`)[0].checked;
    
    adjustDrawingPanel(condId, isIBC);
    drawBaseLine(condId);

    var label_index = 1;

    // draw roof plane
    for (var key in globalRoofLines[condId]) {
        drawLine(condId, globalRoofLines[condId][key][0], globalRoofLines[condId][key][1], "M"+label_index, 1);
        label_index++;
    }

    // draw floor plane
    let floorWidth = 0;
    for (var key in globalFloorLines[condId]) {
        drawLine(condId, globalFloorLines[condId][key][0], globalFloorLines[condId][key][1], "M"+label_index, 2);
        floorWidth += Math.abs(globalFloorLines[condId][key][0][0] - globalFloorLines[condId][key][1][0]);
        label_index++;
    }
    ctx[condId].beginPath();
    ctx[condId].font = '16px Arial';
    ctx[condId].textAlign = 'middle';
    ctx[condId].fillText("Attic Floor", floorWidth * grid_size[condId] / 2, 30);

    var lastDiagnol;
    // draw di­agonals lines 
    var index = 0;
    for (var key in globalDiagnoal1Lines[condId]) {
        var bAllow = !($(`#diag-1-${index+1}-${condId}`).is(":checked"));
        if (bAllow == true) {
            drawLine(condId, globalDiagnoal1Lines[condId][key][0], globalDiagnoal1Lines[condId][key][1], "M"+label_index);
        }
        label_index++;

        index++;
    }
    lastDiagnol = globalDiagnoal1Lines[condId][key];

    index = 0;
    for (var key in globalDiagnoal2Lines[condId]) {
        var bAllow = !($(`#diag-2-${index+1}-${condId}`).is(":checked"));
        if (bAllow == true) {
            var bReverse = ($(`#diag-2-reverse-${index+1}-${condId}`).is(":checked"));
            if(bReverse && globalDiagnoal2ReverseLines[condId][key])
                drawLine(condId, globalDiagnoal2ReverseLines[condId][key][0], globalDiagnoal2ReverseLines[condId][key][1], "M"+label_index);
            else
                drawLine(condId, globalDiagnoal2Lines[condId][key][0], globalDiagnoal2Lines[condId][key][1], "M"+label_index);
        }
        label_index++;
        
        index++;
    }
    if(globalDiagnoal2Lines[condId][key] && globalDiagnoal2Lines[condId][key][0] && globalDiagnoal2Lines[condId][key][1] &&
    (lastDiagnol[0][0] < globalDiagnoal2Lines[condId][key][0][0] || lastDiagnol[1][0] < globalDiagnoal2Lines[condId][key][1][0] || lastDiagnol[0][1] < globalDiagnoal2Lines[condId][key][0][1] || lastDiagnol[1][1] < globalDiagnoal2Lines[condId][key][1][1]))
        lastDiagnol = globalDiagnoal2Lines[condId][key];

    // Draw Overhang
    var angle = parseFloat($(`#txt-roof-degree-${condId}`).val());
    var angleRadian = degreeToRadian(angle);
    
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
    var uphillDist = parseFloat($(`#e-1-${condId}`).val());

    var overhang = Math.max(100, Math.floor(overhangLength * grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian )));

    ctx[condId].beginPath();
    ctx[condId].lineWidth = 2;
    ctx[condId].strokeStyle = "#0000FF";
    ctx[condId].moveTo(0, 0);
    ctx[condId].lineTo( - Math.sin(Math.PI / 2 - angleRadian) * overhangLength * grid_size[condId], Math.cos(Math.PI / 2 - angleRadian) * overhangLength * grid_size[condId]);
    ctx[condId].stroke();

    // Draw Wall
    ctx[condId].beginPath();
    ctx[condId].lineWidth = 2;
    ctx[condId].strokeStyle = "#0000FF";
    ctx[condId].moveTo(0, 0);
    ctx[condId].lineTo(0, 100);
    ctx[condId].stroke();

    ctx[condId].beginPath();
    ctx[condId].font = '16px Arial';
    ctx[condId].textAlign = 'middle';
    ctx[condId].rotate(- Math.PI / 2);
    ctx[condId].fillText("Wall", - 40, 20);
    ctx[condId].rotate(Math.PI / 2);

    if(!isIBC){
        // Draw Required Collar Tie
        if($(`#collartie-${condId}`).css('display') == 'table-row' && lastDiagnol && lastDiagnol[0] && lastDiagnol[1]){
            var newTieHeight = $(`#collartie-height-${condId}`).html();
            ctx[condId].beginPath();
            ctx[condId].lineWidth = 2;
            ctx[condId].strokeStyle = "#FF0000";
            ctx[condId].setLineDash([5, 5]);
            ctx[condId].moveTo(angleRadian != 0 ? newTieHeight * (1 / Math.tan(angleRadian))  * grid_size[condId] : 0, - newTieHeight * grid_size[condId]);
            ctx[condId].lineTo(angleRadian != 0 ? (lastDiagnol[1][0] - newTieHeight * (lastDiagnol[1][0] - lastDiagnol[0][0]) / (lastDiagnol[0][1] - lastDiagnol[1][1]) ) * grid_size[condId] : 0, - newTieHeight * grid_size[condId]);
            ctx[condId].stroke();
            ctx[condId].setLineDash([5, 0]);
            ctx[condId].fillStyle = "#FF0000";
            ctx[condId].fillText("Prop Collar Tie",  newTieHeight * (1 / Math.tan(angleRadian))  * grid_size[condId] / 2 + (lastDiagnol[1][0] - newTieHeight * (lastDiagnol[1][0] - lastDiagnol[0][0]) / (lastDiagnol[0][1] - lastDiagnol[1][1]) ) * grid_size[condId] / 2 - 50, - newTieHeight * grid_size[condId] + 20);
        }

        // Draw solar rectangles
        var startModule = overhangLength - uphillDist;
        var moduleDepth = 1.17 / 12;
        var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
        var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
        var moduleGap = parseFloat($(`#g-1-${condId}`).val()) / 12;

        var startPoint = [- Math.sin(Math.PI / 2 - angleRadian) * startModule * grid_size[condId] - grid_size[condId] / 4 * Math.sin(angleRadian), Math.cos(Math.PI / 2 - angleRadian) * startModule * grid_size[condId] - grid_size[condId] / 4];
        ctx[condId].translate(startPoint[0], startPoint[1]);
        ctx[condId].rotate(- angleRadian);
        ctx[condId].beginPath();
        ctx[condId].lineWidth = 2;
        ctx[condId].strokeStyle = "#000000";

        var totalRoofLength = 0;
        for (var key in globalRoofLines[condId]) {
            totalRoofLength += getDistance(globalRoofLines[condId][key][0], globalRoofLines[condId][key][1]);
        }
        totalRoofLength += startModule;
        var moduleCount = parseInt($(`#f-1-${condId}`).val());
        
        let moduleStartX = 0;
        var orientation = false;

        var supportStart = parseFloat($(`#e-2-${condId}`).val()) - parseFloat($(`#e-1-${condId}`).val());
        var moduleTilt = degreeToRadian(parseFloat($(`#g-2-${condId}`).val()));

        ctx[condId].fillStyle = '#000';
        for(let i = 1; i <= moduleCount; i ++)
        {
            orientation = false;
            if($(`#a-6-${condId}`).val() == "Portrait")
                orientation = true;
            if($(`#h-${i}-${condId}`)[0].checked)
                orientation = !orientation;

            let curModuleWidth = (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight));
                    
            if(moduleTilt >= 0){
                ctx[condId].translate(moduleStartX * grid_size[condId], 0);
                ctx[condId].rotate(- moduleTilt);
                ctx[condId].strokeRect(0, 0, curModuleWidth * grid_size[condId], moduleDepth * grid_size[condId]);
                // Left Support
                ctx[condId].fillRect(supportStart * grid_size[condId] - 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 / Math.cos(moduleTilt) + 1 - moduleDepth * grid_size[condId] + supportStart * Math.tan(moduleTilt) * grid_size[condId]);
                // Right Support
                ctx[condId].fillRect(curModuleWidth * grid_size[condId] - (supportStart + 1 / 12) * grid_size[condId] + 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 / Math.cos(moduleTilt) + 1 - moduleDepth * grid_size[condId] + curModuleWidth * Math.tan(moduleTilt) * grid_size[condId] - supportStart * Math.tan(moduleTilt) * grid_size[condId]);
                ctx[condId].rotate(moduleTilt);
                ctx[condId].translate(- moduleStartX * grid_size[condId], 0);
            }else{
                ctx[condId].translate(moduleStartX * grid_size[condId] + curModuleWidth * grid_size[condId], 0);
                ctx[condId].rotate(- moduleTilt);
                ctx[condId].strokeRect(0, 0, - curModuleWidth * grid_size[condId], moduleDepth * grid_size[condId]);
                // Left Support
                ctx[condId].fillRect(- curModuleWidth * grid_size[condId] + supportStart * grid_size[condId] - 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 / Math.cos(moduleTilt) + 1 - moduleDepth * grid_size[condId] - curModuleWidth * Math.tan(moduleTilt) * grid_size[condId] + supportStart * Math.tan(moduleTilt) * grid_size[condId]);
                // Right Support
                ctx[condId].fillRect(- (supportStart + 1 / 12) * grid_size[condId] + 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 / Math.cos(moduleTilt) + 1 - moduleDepth * grid_size[condId] - supportStart * Math.tan(moduleTilt) * grid_size[condId]);
                ctx[condId].rotate(moduleTilt);
                ctx[condId].translate(- moduleStartX * grid_size[condId] - curModuleWidth * grid_size[condId], 0);
            }

            moduleStartX += (moduleGap + curModuleWidth);
        }
        
        ctx[condId].rotate(angleRadian);
        ctx[condId].translate(- startPoint[0], - startPoint[1]);
    }

    var overhangX = Math.floor(overhangLength * grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * grid_size[condId] * Math.sin(angleRadian));
    ctx[condId].translate(- overhangX, overhangY);
}

var preloaded_data = [];

var availableUSState = [
  "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT",
  "NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"
];

var availablePVModules = [];
var availablePVInverters = [];
var availableStanchions = [];
var availableRailSupports = [];

var getPVModuleTypes = function() {
    var mainTypes = [];
    for (index = 0; index < availablePVModules.length; index++) {
        bFound = false;
        for (typeIndex = 0; typeIndex < mainTypes.length; typeIndex++) {
            if (mainTypes[typeIndex] == availablePVModules[index][0]) {
                bFound = true;
            }
        }
        if (bFound == false && (moduleCEC || availablePVModules[index][10] != 1))
            mainTypes.push(availablePVModules[index][0]);
    }

    mainTypes.sort(function (a, b) {
        if(typeof a != 'string') a = '' + a;
        if(typeof b != 'string') b = '' + b;
        if (a.toLowerCase() > b.toLowerCase()) return 1;
        if (b.toLowerCase() > a.toLowerCase()) return -1;
        return 0;
    })
    return mainTypes;
}

var getPVModuleSubTypes = function(mainType) {
    var subTypes = [];
    for (index = 0; index < availablePVModules.length; index++) {
        if (mainType != availablePVModules[index][0]) {
            continue;
        }

        bFound = false;
        for (typeIndex = 0; typeIndex < subTypes.length; typeIndex++) {
            if (subTypes[typeIndex] == availablePVModules[index]) {
                bFound = true;
            }
        }
        if (bFound == false && (moduleCEC || availablePVModules[index][10] != 1)){
            if(moduleSetting == 0 || (moduleSetting == 1 && !availablePVModules[index][7]) || (moduleSetting == 2 && availablePVModules[index][8] == true))
                subTypes.push(availablePVModules[index]);
        }
    }

    subTypes.sort(function (a, b) {
        if(typeof a != 'string') a = '' + a;
        if(typeof b != 'string') b = '' + b;
        if (a.toLowerCase() > b.toLowerCase()) return 1;
        if (b.toLowerCase() > a.toLowerCase()) return -1;
        return 0;
    })
    return subTypes;
}

var optionPVModule = function(mainType, subType, idx) {
    for (index = 0; index < availablePVModules.length; index++) {
        if ((availablePVModules[index][0] == mainType) 
            && (availablePVModules[index][1] == subType))
            return availablePVModules[index][idx];
    }

    return "N/A";
}

var updatePVSubmoduleField = function(mainType, subType="") {
    if (subType == "") 
    {
        selectedSubType = "";
        $('#option-module-subtype').find('option').remove();
        subTypes = getPVModuleSubTypes(mainType);
        
        if(subTypes.length > 0)
            selectedSubType = subTypes[0][1];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVModule'] != "undefined"
          && typeof preloaded_data['Equipment']['PVModule']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['PVModule']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['PVModule']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            let background = '';
            if(subTypes[index][10] == 1)
                background = '#c9dbee';
            else if(subTypes[index][8] == true)
                background = '#90EE90';
            else if(subTypes[index][7])
                background = '#FED8B1';

            if (subTypes[index][1] == selectedSubType) {
                $('#option-module-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''} selected> ${subTypes[index][1]}</option>`);
            }
            else {
                $('#option-module-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''} > ${subTypes[index][1]} </option>`);
            }
        }
        subType = selectedSubType; 
    }

    $('#option-module-option1').html(optionPVModule(mainType, subType, 2));
    
    $('#pv-module-length').val(optionPVModule(mainType, subType, 3));
    $('#pv-module-width').val(optionPVModule(mainType, subType, 4));
    $('#pv-module-custom').val(optionPVModule(mainType, subType, 7) ? true : false);
    $('#pv-module-crc32').val(optionPVModule(mainType, subType, 9));
    $('#pv-module-cec').val(optionPVModule(mainType, subType, 10));
}

var getPVInvertorTypes = function() {
    var mainTypes = [];
    for (index = 0; index < availablePVInverters.length; index++) {
        bFound = false;
        for (typeIndex = 0; typeIndex < mainTypes.length; typeIndex++) {
            if (mainTypes[typeIndex] == availablePVInverters[index][0]) {
                bFound = true;
            }
        }
        if (bFound == false)
            mainTypes.push(availablePVInverters[index][0]);
    }
    return mainTypes;
}

var getPVInvertorSubTypes = function(mainType) {
    var subTypes = [];
    for (index = 0; index < availablePVInverters.length; index++) {
        if (mainType != availablePVInverters[index][0]) {
            continue;
        }

        bFound = false;
        for (typeIndex = 0; typeIndex < subTypes.length; typeIndex++) {
            if (subTypes[typeIndex] == availablePVInverters[index]) {
                bFound = true;
            }
        }
        if (bFound == false){
            if(inverterSetting == 0 || (inverterSetting == 1 && !availablePVInverters[index][4]) || (inverterSetting == 2 && availablePVInverters[index][5] == true))
                subTypes.push(availablePVInverters[index]);
        }
    }

    return subTypes;
}

var optionPVInverter = function(mainType, subType, idx) {
    for (index = 0; index < availablePVInverters.length; index++) {
        if ((availablePVInverters[index][0] == mainType) 
            && (availablePVInverters[index][1] == subType)) {
            return availablePVInverters[index][idx];
        }
    }

    return "N/A";
}

var updatePVInvertorSubField = function(mainType, subType = "", invId = 1) {
    if (subType == "") 
    {
        selectedSubType = "";
        $(`#option-inverter${invId == 1 ? '' : invId}-subtype`).find('option').remove();
        subTypes = getPVInvertorSubTypes(mainType);

        if(subTypes.length > 0)
            selectedSubType = subTypes[0][1];
        if ( invId == 1
          && typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVInverter'] != "undefined"
          && typeof preloaded_data['Equipment']['PVInverter']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['PVInverter']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['PVInverter']['SubType'];
        }
        if ( invId == 2
          && typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVInverter_2'] != "undefined"
          && typeof preloaded_data['Equipment']['PVInverter_2']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['PVInverter_2']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['PVInverter_2']['SubType'];
        }
        if ( invId == 3
          && typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVInverter_3'] != "undefined"
          && typeof preloaded_data['Equipment']['PVInverter_3']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['PVInverter_3']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['PVInverter_3']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            let background = '';
            if(subTypes[index][5] == true)
                background = '#90EE90';
            else if(subTypes[index][4])
                background = '#FED8B1';

            if (subTypes[index][1] == selectedSubType) {
                $(`#option-inverter${invId == 1 ? '' : invId}-subtype`).append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''} selected> ${subTypes[index][1]}</option>`);
            }
            else {
                $(`#option-inverter${invId == 1 ? '' : invId}-subtype`).append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''}> ${subTypes[index][1]} </option>`);
            }
        }

        subType = selectedSubType;
    }

    $(`#option-inverter${invId == 1 ? '' : invId}-option1`).html(optionPVInverter(mainType, subType, 2));
    $(`#option-inverter${invId == 1 ? '' : invId}-option2`).html(optionPVInverter(mainType, subType, 3));   
    $(`#inverter${invId == 1 ? '' : invId}-custom`).val(optionPVInverter(mainType, subType, 4) ? true : false);
    $(`#inverter${invId == 1 ? '' : invId}-crc32`).val(optionPVInverter(mainType, subType, 6));
    $(`#inverter${invId == 1 ? '' : invId}-watts`).val(optionPVInverter(mainType, subType, 7));
}

var addPVInverter = function() {
    if($("#pv-inverter-2")[0].style.display == "none")
        $("#pv-inverter-2").css('display', 'table-row');
    else if($("#pv-inverter-3")[0].style.display == "none")
        $("#pv-inverter-3").css('display', 'table-row');
}

var removePVInverter = function(id) {
    $(`#pv-inverter-${id}`).css('display', 'none');
}

var addStrTable = function() {
    if($("#R2")[0].style.display == "none")
        $("#R2").css('display', 'table-row');
    else if($("#R3")[0].style.display == "none")
        $("#R3").css('display', 'table-row');
    else if($("#R4")[0].style.display == "none")
        $("#R4").css('display', 'table-row');
    else if($("#R5")[0].style.display == "none")
        $("#R5").css('display', 'table-row');
    else if($("#R6")[0].style.display == "none")
        $("#R6").css('display', 'table-row');
    else if($("#R7")[0].style.display == "none")
        $("#R7").css('display', 'table-row');
    else if($("#R8")[0].style.display == "none")
        $("#R8").css('display', 'table-row');
    else if($("#R9")[0].style.display == "none")
        $("#R9").css('display', 'table-row');
    else if($("#R10")[0].style.display == "none")
        $("#R10").css('display', 'table-row');
}

var removeStrTable = function(id) {
    $(`#R${id}`).css('display', 'none');
}

var addACTable = function() {
    if($("#AC2")[0].style.display == "none")
        $("#AC2").css('display', 'table-row');
    else if($("#AC3")[0].style.display == "none")
        $("#AC3").css('display', 'table-row');
    else if($("#AC4")[0].style.display == "none")
        $("#AC4").css('display', 'table-row');
    else if($("#AC5")[0].style.display == "none")
        $("#AC5").css('display', 'table-row');
    else if($("#AC6")[0].style.display == "none")
        $("#AC6").css('display', 'table-row');
    else if($("#AC7")[0].style.display == "none")
        $("#AC7").css('display', 'table-row');
    else if($("#AC8")[0].style.display == "none")
        $("#AC8").css('display', 'table-row');
    else if($("#AC9")[0].style.display == "none")
        $("#AC9").css('display', 'table-row');
    else if($("#AC10")[0].style.display == "none")
        $("#AC10").css('display', 'table-row');
}

var removeACTable = function(id) {
    $(`#AC${id}`).css('display', 'none');
}

var getStanchionTypes = function() {
    var mainTypes = [];
    for (index = 0; index < availableStanchions.length; index++) {
        bFound = false;
        for (typeIndex = 0; typeIndex < mainTypes.length; typeIndex++) {
            if (mainTypes[typeIndex] == availableStanchions[index][0]) {
                bFound = true;
            }
        }
        if (bFound == false)
            mainTypes.push(availableStanchions[index][0]);
    }
    return mainTypes;
}

var getStanchionSubTypes = function(mainType) {
    var subTypes = [];
    for (index = 0; index < availableStanchions.length; index++) {
        if (mainType != availableStanchions[index][0]) {
            continue;
        }

        bFound = false;
        for (typeIndex = 0; typeIndex < subTypes.length; typeIndex++) {
            if (subTypes[typeIndex] == availableStanchions[index]) {
                bFound = true;
            }
        }
        if (bFound == false){
            if(stanchionSetting == 0 || (stanchionSetting == 1 && !availableStanchions[index][4]) || (stanchionSetting == 2 && availableStanchions[index][5] == true))
                subTypes.push(availableStanchions[index]);
        }
    }

    return subTypes;
}

var optionStanchion = function(mainType, subType, idx) {
    for (index = 0; index < availableStanchions.length; index++) {
        if ((availableStanchions[index][0] == mainType) 
            && (availableStanchions[index][1] == subType)) {
            return availableStanchions[index][idx];
        }
    }

    return "N/A";
}

var updateStanchionSubField = function(mainType, subType = "") {

    if (subType == "") {
        selectedSubType = "";
        $('#option-stanchion-subtype').find('option').remove();
        subTypes = getStanchionSubTypes(mainType);

        if(subTypes.length > 0)
            selectedSubType = subTypes[0][1];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['Stanchion'] != "undefined"
          && typeof preloaded_data['Equipment']['Stanchion']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['Stanchion']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['Stanchion']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            let background = '';
            if(subTypes[index][5] == true)
                background = '#90EE90';
            else if(subTypes[index][4])
                background = '#FED8B1';

            if (subTypes[index][1] == selectedSubType) {
                $('#option-stanchion-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''} selected> ${subTypes[index][1]}</option>`);
            }
            else {
                $('#option-stanchion-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''}> ${subTypes[index][1]} </option>`);
            }
        }
        subType = selectedSubType;
    }


    $('#option-stanchion-option1').html(optionStanchion(mainType, subType, 2));
    $('#option-stanchion-option2').html(optionStanchion(mainType, subType, 3));
    $('#stanchion-custom').val(optionStanchion(mainType, subType, 4) ? true : false);
    $('#stanchion-crc32').val(optionStanchion(mainType, subType, 6));
}

var updateFCStanchionSubField = function(mainType, caseIdx) {
    // if (mainType != "") {
        selectedSubType = "";
        $(`#j-2-${caseIdx + 1}`).find('option').remove();
        subTypes = getStanchionSubTypes(mainType);

        if(subTypes.length > 0)
            selectedSubType = subTypes[0][1];
        if(preloaded_data && preloaded_data['LoadingCase'] && preloaded_data['LoadingCase'][caseIdx] && preloaded_data['LoadingCase'][caseIdx]['Stanchions'] && preloaded_data['LoadingCase'][caseIdx]['Stanchions']['J2']) {
            if(preloaded_data['LoadingCase'][caseIdx]['Stanchions']['J1'] == mainType)
                selectedSubType = preloaded_data['LoadingCase'][caseIdx]['Stanchions']['J2'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            if (subTypes[index][1] == selectedSubType)
                $(`#j-2-${caseIdx + 1}`).append(`<option data-value="${subTypes[index][1]}" selected> ${subTypes[index][1]}</option>`);
            else
                $(`#j-2-${caseIdx + 1}`).append(`<option data-value="${subTypes[index][1]}"> ${subTypes[index][1]} </option>`);
        }
    // } else {
    //     $(`#j-2-${caseIdx + 1}`).find('option').remove();
    //     $(`#j-2-${caseIdx + 1}`).append(`<option data-value="" selected></option>`);
    // }
}

var getRailSupportTypes = function() {
    var mainTypes = [];
    for (index = 0; index < availableRailSupports.length; index++) {
        bFound = false;
        for (typeIndex = 0; typeIndex < mainTypes.length; typeIndex++) {
            if (mainTypes[typeIndex] == availableRailSupports[index][0]) {
                bFound = true;
            }
        }
        if (bFound == false)
            mainTypes.push(availableRailSupports[index][0]);
    }
    return mainTypes;
}

var getRailSupportSubTypes = function(mainType) {
    var subTypes = [];
    for (index = 0; index < availableRailSupports.length; index++) {
        if (mainType != availableRailSupports[index][0]) {
            continue;
        }

        bFound = false;
        for (typeIndex = 0; typeIndex < subTypes.length; typeIndex++) {
            if (subTypes[typeIndex] == availableRailSupports[index]) {
                bFound = true;
            }
        }
        if (bFound == false){
            if(railSetting == 0 || (railSetting == 1 && !availableRailSupports[index][4]) || (railSetting == 2 && availableRailSupports[index][5] == true))
                subTypes.push(availableRailSupports[index]);
        }
    }

    return subTypes;
}

var optionRailSupport = function(mainType, subType, idx) {
    for (index = 0; index < availableRailSupports.length; index++) {
        if ((availableRailSupports[index][0] == mainType) 
            && (availableRailSupports[index][1] == subType)) {
            return availableRailSupports[index][idx];
        }
    }

    return "N/A";
}

var loadASCEOptions = function(state){
    for(let j = 0; j < 10; j ++){
        $(`#a-12-${j + 1}`).find('option').remove();
    }
    $.ajax({
        url:"getASCEOptions",
        type:'post',
        data:{state: state, crc32: $("#railsupport-crc32").val() },
        success: function(res){
            if(res.success && res.data && state == $('#option-state').val()){
                for(let i = 0; i < res.data.length; i ++){
                    for(let j = 0; j < 10; j ++){
                        if(preloaded_data && preloaded_data['LoadingCase'] && preloaded_data['LoadingCase'][j] && preloaded_data['LoadingCase'][j]['RoofDataInput']
                            && preloaded_data['LoadingCase'][j]['RoofDataInput']['A12'] == res.data[i])
                            $(`#a-12-${j + 1}`).append(`<option data-value="${res.data[i]}" selected>${res.data[i]}</option>`);
                        else
                            $(`#a-12-${j + 1}`).append(`<option data-value="${res.data[i]}">${res.data[i]}</option>`);
                    }
                }
            }
        }
    });
}

var updateRailSupportSubField = function(mainType, subType = "") {
    if (subType == "") {
        selectedSubType = "";
        $('#option-railsupport-subtype').find('option').remove();
        subTypes = getRailSupportSubTypes(mainType);

        if(subTypes.length > 0)
            selectedSubType = subTypes[0][1];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['RailSupportSystem'] != "undefined"
          && typeof preloaded_data['Equipment']['RailSupportSystem']['SubType'] != "undefined" ) {
            if(preloaded_data['Equipment']['RailSupportSystem']['Type'] == mainType)
                selectedSubType = preloaded_data['Equipment']['RailSupportSystem']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            let background = '';
            if(subTypes[index][5] == true)
                background = '#90EE90';
            else if(subTypes[index][4])
                background = '#FED8B1';

            if (subTypes[index][1] == selectedSubType) {
                $('#option-railsupport-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''} selected> ${subTypes[index][1]}</option>`);
            }
            else {
                $('#option-railsupport-subtype').append(`<option data-value="${subTypes[index][1]}" ${background != '' ? "style='background-color: " + background + "'" : ''}> ${subTypes[index][1]} </option>`);
            }
        }

        subType = selectedSubType;
    }

    $('#option-railsupport-option1').html(optionRailSupport(mainType, subType, 2));
    $('#option-railsupport-option2').html(optionRailSupport(mainType, subType, 3));
    $('#railsupport-custom').val(optionRailSupport(mainType, subType, 4) ? true : false);
    $('#railsupport-crc32').val(optionRailSupport(mainType, subType, 6));
    loadASCEOptions($('#option-state').val());
}

var moduleSetting = 0, inverterSetting = 0, railSetting = 0, stanchionSetting = 0, moduleCEC = 0, cecLoaded = 0;
function updateModuleSetting(setting){
    moduleSetting = setting;
    $('#option-module-type').find('option').remove();
    var tmpMainTypes = getPVModuleTypes();
    var mainTypes = [];
    tmpMainTypes.forEach(type => {
        if(moduleSetting == 0)
            mainTypes.push(type);
        else if(moduleSetting == 1){
            let checkModule = availablePVModules.filter(item => item[0] == type && !item[7])
            if(checkModule[0]) mainTypes.push(type);
        } else {
            let checkModule = availablePVModules.filter(item => item[0] == type && item[8] == true)
            if(checkModule[0]) mainTypes.push(type);
        }
    })

    // load selected from preloaded_data
    selectedMainType = mainTypes[0];

    for (index=0; index<mainTypes.length; index++) 
    {
        if (mainTypes[index] == selectedMainType) {
            $('#option-module-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
        }
        else {
            $('#option-module-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
        }
    }

    updatePVSubmoduleField($('#option-module-type').children("option:selected").val());
}

function loadCECEquipmentSection(){
    return new Promise((resolve, reject) => {
        if(cecLoaded)
            resolve(true);
        else{
            swal.fire({ title: "Please wait...", showConfirmButton: false });
            swal.showLoading();
            $.ajax({
                url:"getCECPVModules",
                type:'post',
                dataType: "json",
                success:function(res){
                    swal.close();
                    if(res && res.length > 0)
                    {
                        for(let i = 0; i < res.length; i ++){
                            availablePVModules.push([res[i]['mfr'], res[i]['model'], res[i]['rating'], res[i]['length'], res[i]['width'], res[i]['depth'], res[i]['weight'], res[i]['custom'], res[i]['favorite'], res[i]['crc32'], 1]);
                        }
                    }
                    resolve(true);

                },
                error: function(xhr, status, error) {
                    swal.close();
                    res = JSON.parse(xhr.responseText);
                    console.log(res);
                    resolve(false);
                }
            });
        }
    });
}

async function toggleModuleCEC(){
    moduleCEC = !moduleCEC;
    localStorage.setItem('moduleCEC', moduleCEC ? '1' : '0');
    if(moduleCEC && !cecLoaded){
        await loadCECEquipmentSection();
        cecLoaded = 1;
    }
    updateModuleSetting(moduleSetting);
}

function updateInverterSetting(setting, invId = 1){
    inverterSetting = setting;
    $(`#option-inverter${invId == 1 ? '' : invId}-type`).find('option').remove();
    var tmpMainTypes = getPVInvertorTypes();
    var mainTypes = [];
    tmpMainTypes.forEach(type => {
        if(inverterSetting == 0)
            mainTypes.push(type);
        else if(inverterSetting == 1){
            let checkModule = availablePVInverters.filter(item => item[0] == type && !item[4])
            if(checkModule[0]) mainTypes.push(type);
        } else {
            let checkModule = availablePVInverters.filter(item => item[0] == type && item[5] == true)
            if(checkModule[0]) mainTypes.push(type);
        }
    })

    // load selected from preloaded_data
    selectedMainType = mainTypes[0];

    for (index=0; index<mainTypes.length; index++) 
    {
        if (mainTypes[index] == selectedMainType) {
            $(`#option-inverter${invId == 1 ? '' : invId}-type`).append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
        }
        else {
            $(`#option-inverter${invId == 1 ? '' : invId}-type`).append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
        }
    }
    updatePVInvertorSubField($(`#option-inverter${invId == 1 ? '' : invId}-type`).children("option:selected").val(), "", invId);
    updateStanchionSubField($('#option-stanchion-type').children("option:selected").val());
    updateRailSupportSubField($('#option-railsupport-type').children("option:selected").val());
}

function updateStanchionSetting(setting){
    stanchionSetting = setting;
    $('#option-stanchion-type').find('option').remove();
    var tmpMainTypes = getStanchionTypes();
    var mainTypes = [];
    tmpMainTypes.forEach(type => {
        if(stanchionSetting == 0)
            mainTypes.push(type);
        else if(stanchionSetting == 1){
            let checkModule = availableStanchions.filter(item => item[0] == type && !item[4])
            if(checkModule[0]) mainTypes.push(type);
        } else {
            let checkModule = availableStanchions.filter(item => item[0] == type && item[5] == true)
            if(checkModule[0]) mainTypes.push(type);
        }
    })

    // load selected from preloaded_data
    selectedMainType = mainTypes[0];

    for (index=0; index<mainTypes.length; index++) 
    {
        if (mainTypes[index] == selectedMainType) {
            $('#option-stanchion-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
        }
        else {
            $('#option-stanchion-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
        }
    }

    updateStanchionSubField($('#option-stanchion-type').children("option:selected").val());
}

function updateRailSetting(setting){
    railSetting = setting;
    $('#option-railsupport-type').find('option').remove();
    var tmpMainTypes = getRailSupportTypes();
    var mainTypes = [];
    tmpMainTypes.forEach(type => {
        if(railSetting == 0)
            mainTypes.push(type);
        else if(railSetting == 1){
            let checkModule = availableRailSupports.filter(item => item[0] == type && !item[4])
            if(checkModule[0]) mainTypes.push(type);
        } else {
            let checkModule = availableRailSupports.filter(item => item[0] == type && item[5] == true)
            if(checkModule[0]) mainTypes.push(type);
        }
    })

    // load selected from preloaded_data
    selectedMainType = mainTypes[0];

    for (index=0; index<mainTypes.length; index++) 
    {
        if (mainTypes[index] == selectedMainType) {
            $('#option-railsupport-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
        }
        else {
            $('#option-railsupport-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
        }
    }

    updateRailSupportSubField($('#option-railsupport-type').children("option:selected").val());
}

// load US state into select component
var loadStateOptions = function() {
    return new Promise((resolve, reject) => {
        var selectedState = 'MA';
        if (typeof preloaded_data != 'undefined' && typeof preloaded_data['ProjectInfo'] !== 'undefined' && typeof preloaded_data['ProjectInfo']['State'] !== 'undefined') {
            selectedState = preloaded_data['ProjectInfo']['State'];
        }

        for (index=0; index<availableUSState.length; index++) 
        {
            if (availableUSState[index] == selectedState) 
            {
                $('#option-state').append(`<option data-value="${availableUSState[index]}" selected=""> 
                                        ${availableUSState[index]} 
                                    </option>`);
            }
            else 
            {
                $('#option-state').append(`<option data-value="${availableUSState[index]}"> 
                                        ${availableUSState[index]} 
                                    </option>`);
            }
        }

        detectCorrectTown();
        resolve(true);
    });
}

var isSubClientAllowed = function() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url:"jobSubClientAllowed",
            type:'post',
            data:{ jobId: $('#projectId').val() },
            success:function(res){
                if(res && res.success == true){
                    $("#subclient-select").css("display", "table-row");
                    $("#subproject-num").css("display", "table-row");
                    resolve(true);
                } else
                    resolve(false);
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                resolve(false);
            }
        });
    })
}

// Load Sub-Client options
var loadSubClients = function() {
    return new Promise((resolve, reject) => {
        var selectedClient = '0';
        if (typeof preloaded_data != 'undefined' && typeof preloaded_data['ProjectInfo'] !== 'undefined' && typeof preloaded_data['ProjectInfo']['SubClient'] !== 'undefined') {
            selectedClient = preloaded_data['ProjectInfo']['SubClient'];
        }

        $.ajax({
            url:"getJobSubClients",
            type:'post',
            data:{ jobId: $('#projectId').val() },
            success:function(res){
                if(res && res.success){
                    res.clients.forEach(client => {
                        if(client.id == selectedClient){
                            $('#option-sub-client').append(`<option value="${client.id}" selected=""> 
                                ${client.name} 
                            </option>`);
                        } else {
                            $('#option-sub-client').append(`<option value="${client.id}"> 
                                ${client.name} 
                            </option>`);
                        }
                    })
                }
                resolve(true);
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                resolve(false);
            }
        });
    })
}

var updateUserOption = function(userId) {
    // call ajax
    $.ajax({
        url:"getUserData",
        type:'post',
        data:{userId:userId},
        success:function(res){
            if(res.status == true) {
                document.getElementById("txt-user-name").value = res.data.username;
                document.getElementById("txt-user-email").value = res.data.email;
            } 
            else{
                swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        }
    });
}



var detectCorrectTown = function() {
    var city_name = document.getElementById("txt-city").value;
    var option_state = document.getElementById("option-state");        
    var selected = option_state.options[option_state.selectedIndex];
    var state_name = selected.getAttribute('data-value');

    // err_message = "No Town name match in MA CMR780.  Correct Town name.";
    // good_message = "Good Massachusetts town name";

    $.ajax({
        url:"checkCorrectTown",
        type:'post',
        data:{city: city_name, state: state_name},
        success:function(res){
            if(res.status == true) {
                // $('#txt-city-comment').html('&nbsp;&nbsp;' + `Good ${state_name} town name`);
                // $('#txt-city-comment').css('color', 'black');
            } 
            else{
                if(res.recommended){
                    // $('#txt-city-comment').html('&nbsp;&nbsp;' + `No Town name match in ${state_name}. Correct Town name.`);
                    // $('#txt-city-comment').css('color', '#FF0000');
                    // $('#txt-city-comment').css('font-weight', 'bold');
                    swal.fire({
                        title: "Warning",
                        html: `The ${city_name} town is not on the published ${state_name} list, and that the recommended nearest listed town is ${res.recommended}.  Would you like to use this town?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: `Yes`,
                        cancelButtonText: `No`,
                    })
                    .then(( result ) => {
                        if ( result.value )
                        {
                            $("#txt-city").val(res.recommended);
                            // $('#txt-city-comment').html('&nbsp;&nbsp;' + `Good ${state_name} town name`);
                            // $('#txt-city-comment').css('color', 'black');
                        }
                    });
                } else {
                    // $('#txt-city-comment').html("");
                    // $('#txt-city-comment').css('color', 'black');
                    // $('#txt-city-comment').css('font-weight', 'normal');
                }
            }
        },
        error: function(xhr, status, error) {
            res = JSON.parse(xhr.responseText);
            swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
        }
    });
}

var updateNumberOfConditions = function(conditions) {
    // enable fields
    for (index=1; index<=conditions; index++) {
        $("#fcTab-" + index).css("display", "block");
    }

    // disable fields
    for (index=conditions+1; index<=10; index++) {
        $("#fcTab-" + index).css("display", "none");
    }
}

var ignorable = ['a-7-', 'a-8-', 'af-8-', 'ai-8-', 'a-9-', 'af-9-', 'ai-9-', 'a-10-', 'af-10-', 'ai-10-', 'ac-7-', 'ac-8-', 'ac-9-', 'ac-10-', 'c-1-', 'c-2-', 'cf-2-', 'ci-2-', 'c-3-', 'cf-3-', 'ci-3-', 'c-4-', 'cf-4-', 'ci-4-', 'calc-algorithm-', 'collarHeights', 'd-7-', 'd-8-', 'd-9-', 'date_report', 'txt-sub-project-number', 'inverter2-', 'inverter3-'];

var isIgnorable = function(id) {
    let canIgnore = false;
    for(let i = 0; i < ignorable.length; i ++)
    {
        if(typeof id == 'string' && id.includes(ignorable[i]))
        {
            canIgnore = true;
            break;
        }
    }
    return canIgnore;
}

var isEmptyInputBox = function() {

    // check empty input text boxes
    var isEmpty = false;

    // check C1~C3 according to C2 value
    var caseCount = $("#option-number-of-conditions").val();
    for(let i = 1; i <= caseCount; i ++)
    {
        var isIBC = $(`#trussFlagOption-${i}-3`)[0].checked;
        if(isIBC) break;
        if($(`#c-2-${i}`).val() != "" && parseFloat($(`#c-2-${i}`).val()) != 0 && ($(`#c-1-${i}`).val() == ""))
        {
            isEmpty = true;
            $(`#c-1-${i}`).css('background-color', '#FFC7CE');
        }    
        if($(`#c-2-${i}`).val() != "" && parseFloat($(`#c-2-${i}`).val()) != 0 && ($(`#c-3-${i}`).val() == ""))
        {
            isEmpty = true;
            $(`#c-3-${i}`).css('background-color', '#FFC7CE');
        }
    }
    
    var empty_textboxes = $('.rfdContainer input:text:enabled').filter(function() { return this.value === ""; });
    empty_textboxes.each(function() { 
        // skip note 
        if (typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("i-1-") || isIgnorable($(this).attr('id')) )) {
            return;
        }
        // skip sweet alert
        if(typeof $(this).attr('class') == "string" && $(this).attr('class').includes('swal2-input'))
            return;

        if(typeof $(this).attr('class') == "string" && $(this).attr('class').includes('permit'))
            return;
        
        // skip feet / inch pair input
        if(typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("f-") && $('#' + $(this).attr('id').replace('f-', 'i-')).val() != ""))
            return;
        if(typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("i-") && $('#' + $(this).attr('id').replace('i-', 'f-')).val() != ""))
            return;

        $(this).css('background-color', '#FFC7CE');
        console.log($(this).attr('id'));
        isEmpty = true;
    });
    

    // check empty date boxes
    if ($('#date-of-field-visit').val() == "") {
        $('#date-of-field-visit').css('background-color', '#FFC7CE');
        console.log('date-of-field-visit');
        isEmpty = true;
    }
    if ($('#date-of-plan-set').val() == "") {
        $('#date-of-plan-set').css('background-color', '#FFC7CE');
        console.log('date-of-plan-set');
        isEmpty = true;
    }

    return isEmpty;
}

$("#txt-project-name").on('keypress', function(event){
    if(event.key == "&" || event.key == "#" || event.key == ":" || event.key == "/" || event.key == "\\")
    {
        event.preventDefault();
        return false;
    }
});

$("#txt-project-name").on('keyup', function(event){
    $(this).val($(this).val().replace("&", "").replace("#", "").replace(":", "").replace("/", "").replace("\\", ""));
});

$("#txt-project-number").on('keypress', function(event){
    if(event.key == ".")
    {
        event.preventDefault();
        return false;
    }
});

$("#txt-project-number").on('keyup', function(event){
    $(this).val($(this).val().replace(".", ""));
});

$('.domChange').on('input', function() {
    domChanged = true;
});

$('.domChange').on('select', function() {
    domChanged = true;
});

var getData = function(caseCount = 10) {
    var i = 0;
    var alldata = {};

    $('#inputform-first input:text:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    $('#inputform-first input[type=checkbox]:enabled').each(function() { 
        alldata[$(this).attr('id')] = (this.checked ? $(this).val() : "");
    });
    $('#inputform-first input[type=date]:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    $('#inputform-first select:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    $('#inputform-electric input:text:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    alldata['txt-company-name'] = $('#txt-company-name').val();
    alldata['txt-company-number'] = $('#txt-company-number').val();
    alldata['txt-user-name'] = $('#txt-user-name').val();
    alldata['txt-user-email'] = $('#txt-user-email').val();
    alldata['option-module-option1'] = $('#option-module-option1').html();
    alldata['option-module-option2'] = $('#option-module-option2').html();
    alldata['option-module-quantity'] = $('#option-module-quantity').val();
    alldata['option-inverter-option1'] = $('#option-inverter-option1').html();
    alldata['option-inverter-option2'] = $('#option-inverter-option2').html();
    alldata['option-inverter-quantity'] = $('#option-inverter-quantity').val();
    alldata['option-inverter2-option1'] = $('#option-inverter2-option1').html();
    alldata['option-inverter2-option2'] = $('#option-inverter2-option2').html();
    alldata['option-inverter2-quantity'] = $('#option-inverter2-quantity').val();
    alldata['option-inverter3-option1'] = $('#option-inverter3-option1').html();
    alldata['option-inverter3-option2'] = $('#option-inverter3-option2').html();
    alldata['option-inverter3-quantity'] = $('#option-inverter3-quantity').val();
    alldata['option-stanchion-option1'] = $('#option-stanchion-option1').html();
    alldata['option-stanchion-option2'] = $('#option-stanchion-option2').html();
    alldata['option-railsupport-option1'] = $('#option-railsupport-option1').html();
    alldata['option-railsupport-option2'] = $('#option-railsupport-option2').html();
    if($("#pv-inverter-2")[0].style.display != 'none')
        alldata['pv-inverter-2'] = 1;
    else
        alldata['pv-inverter-2'] = 0;
    if($("#pv-inverter-3")[0].style.display != 'none')
        alldata['pv-inverter-3'] = 1;
    else
        alldata['pv-inverter-3'] = 0;
    alldata['caseInputs'] = [];

    for(i = 1; i <= caseCount; i ++ )
    {
        var data = {}
        data['TrussFlag'] = $(`#trussFlagOption-${i}-2`)[0].checked;
        
        let tabType;
        if($(`#trussFlagOption-${i}-1`)[0].checked) tabType = 0;
        else if($(`#trussFlagOption-${i}-2`)[0].checked) tabType = 1;
        else if($(`#trussFlagOption-${i}-3`)[0].checked) tabType = 2;
        else if($(`#trussFlagOption-${i}-4`)[0].checked) tabType = 3;
        data['Analysis_type'] = tabType;
        
        $(`#inputform-${i} input:text:enabled`).each(function() { 
            data[$(this).attr('id')] = $(this).val();
        });
        $(`#inputform-${i} input[type="number"]`).each(function() { 
            data[$(this).attr('id')] = $(this).val();
        });
        $(`#inputform-${i} input[type=checkbox]:enabled`).each(function() { 
            data[$(this).attr('id')] = (this.checked ? $(this).val() : "");
        });
        // $("input[name='mail']:enabled".each(function() { 
        //     data[$(this).attr('id')] = $(this).val();
        // });
        $(`#inputform-${i} select:enabled`).each(function() { 
            data[$(this).attr('id')] = $(this).val();
        });

        data[`td-unknown-degree1-${i}`] = $(`#td-unknown-degree1-${i}`).html();
        data[`td-calculated-roof-plane-length-${i}`] = $(`#td-calculated-roof-plane-length-${i}`).html();
        data[`td-diff-between-measured-and-calculated-${i}`] = $(`#td-diff-between-measured-and-calculated-${i}`).html();
        data[`td-sum-of-length-entered-${i}`] = $(`#td-sum-of-length-entered-${i}`).html();
        data[`td-checksum-of-segment1-${i}`] = $(`#td-checksum-of-segment1-${i}`).html();
        data[`td-total-length-entered-${i}`] = $(`#td-total-length-entered-${i}`).html();
        data[`td-checksum-of-segment2-${i}`] = $(`#td-checksum-of-segment2-${i}`).html();

        data[`td-diag-1-1-${i}`] = $(`#td-diag-1-1-${i}`).html();
        data[`td-diag-1-2-${i}`] = $(`#td-diag-1-2-${i}`).html();
        data[`td-diag-1-3-${i}`] = $(`#td-diag-1-3-${i}`).html();
        data[`td-diag-1-4-${i}`] = $(`#td-diag-1-4-${i}`).html();
        data[`td-diag-1-5-${i}`] = $(`#td-diag-1-5-${i}`).html();
        data[`td-diag-1-6-${i}`] = $(`#td-diag-1-6-${i}`).html();

        data[`td-diag-2-1-${i}`] = $(`#td-diag-2-1-${i}`).html();
        data[`td-diag-2-2-${i}`] = $(`#td-diag-2-2-${i}`).html();
        data[`td-diag-2-3-${i}`] = $(`#td-diag-2-3-${i}`).html();
        data[`td-diag-2-4-${i}`] = $(`#td-diag-2-4-${i}`).html();
        data[`td-diag-2-5-${i}`] = $(`#td-diag-2-5-${i}`).html();
        data[`td-diag-2-6-${i}`] = $(`#td-diag-2-6-${i}`).html();

        alldata['caseInputs'].push(data);
    }

    alldata['wind-speed'] = $('#wind-speed').val();
    alldata['wind-speed-override'] = $('#wind-speed-override')[0].checked;
    alldata['ground-snow'] = $('#ground-snow').val();
    alldata['ground-snow-override'] = $('#ground-snow-override')[0].checked;
    alldata['ibc-year'] = $('#ibc-year').val();
    alldata['asce-year'] = $('#asce-year').val();
    alldata['nec-year'] = $('#nec-year').val();
    alldata['wind-exposure'] = $('#wind-exposure').val();
    alldata['override-unit'] = $('#override-unit').val();

    if($("#inputform-electric").length > 0){
        alldata['type-interconnection'] = $("#type-interconnection").val();
        alldata['bus-bar-rating'] = $("#bus-bar-rating").val();
        alldata['main-breaker-rating'] = $("#main-breaker-rating").val();
        alldata['downgraded-breaker-rating'] = $("#downgraded-breaker-rating").val();
        alldata['pv-breaker-selected'] = $("#pv-breaker-selected").val();
        alldata['StrTable'] = [];
        alldata['ACTable'] = [];
        for(i = 1; i <= 10; i ++) {
            if($(`#R${i}`)[0].style.display != "none")
                alldata['StrTable'].push({'InvNo': $(`#R${i} .Inv`).val(), 'StringNumber': $(`#R${i} .String`).val(), 'ModulesPerString': $(`#R${i} .ModStr`).val(), 'StringsPerMPPT': $(`#R${i} .StrMPPT`).val(), 'StringLength': $(`#R${i} .StrLength`).val()});
            if($(`#AC${i}`)[0].style.display != "none")
                alldata['ACTable'].push({'InvNo': $(`#AC${i} .Inv`).val(), 'WireLength': $(`#AC${i} .WireLength`).val(), 'MinWireSize': $(`#AC${i} .MinWireSize`).val(), 'Material': $(`#AC${i} .Material`).val(), 'InsulRating': $(`#AC${i} .InsulRating`).val(), 'Circuits': $(`#AC${i} .Circuits`).val()});
        }
        alldata['PV-breaker-recommended'] = $("#PV-breaker-recommended").html();
    }

    return alldata;
}

var availableAlongRoofPlane = ["1","2","3","4","5","6"];
var globalAlongRoofPlane = 4;

var availableFloorPlane = ["1","2","3","4","5","6"];
var globalFloorPlane = 3;

var globalDiagnoals1 = new Array(11); globalDiagnoals1.fill(Math.min(globalAlongRoofPlane, globalFloorPlane));
var globalDiagnoals2 = new Array(11); globalDiagnoals2.fill(Math.min(globalAlongRoofPlane - 1, globalFloorPlane));

var availableRoofDegrees = ["Top ridge height above floor plane", "Roof slope (degrees)"];
var globalRoofPoints = new Array(11); globalRoofPoints.fill(new Array());
var globalFloorPoints = new Array(11); globalFloorPoints.fill(new Array());
var globalRoofLines = new Array(11); globalRoofLines.fill({});
var globalFloorLines = new Array(11); globalFloorLines.fill({});
var globalDiagnoal1Lines = new Array(11); globalDiagnoal1Lines.fill({});
var globalDiagnoal2Lines = new Array(11); globalDiagnoal2Lines.fill({});
var globalDiagnoal2ReverseLines = new Array(11); globalDiagnoal2ReverseLines.fill({});

var globalRoofSlopeDegree = new Array(11); globalRoofSlopeDegree.fill(true); 

// -------- administrative functions ----------
var radianToDegree = function(a) {
    return a * 180 / Math.PI;
}

var degreeToRadian = function(a) {
    return a * Math.PI / 180;
}

var updateRoofSlopeAnotherField = function( condId ) {
    selectedVal = $(`#option-roof-slope-${condId}`).children("option:selected").val();
    if (selectedVal == "Roof slope (degrees)") {
        globalRoofSlopeDegree[condId] = true;
        $(`#txt-roof-slope-another-${condId}`).html("Top ridge height above floor plane");
    }
    else {
        globalRoofSlopeDegree[condId] = false;
        $(`#txt-roof-slope-another-${condId}`).html("Roof slope (degrees)");
    }

    updatePlanes(condId);
}

// calculate all joints
var updatePlanes = function( condId ) {

    globalRoofPoints[condId] = [];
    globalFloorPoints[condId] = [];

    // Update & Calculate the radian between Floor and Roof
    var AB5 = false;

    if (globalRoofSlopeDegree[condId] == false) { AB5 = true; }
    else { AB5 = false; }

    var F139 = parseFloat($(`#txt-roof-degree-${condId}`).val());
    var F161 = parseFloat($(`#txt-length-of-floor-plane-${condId}`).val());
    
    var W44, Y44, W25, W36, W39, W40;
    if (AB5 == true) {
        W44 = Math.atan(F139 / F161);
        Y44 = radianToDegree(W44);
        
        W25 = parseFloat($(`#td-sum-of-length-entered-${condId}`).html());
        W36 = parseFloat($(`#td-total-length-entered-${condId}`).html());
        var W39 = W36 / Math.cos(W44);
        var W40 = W39 - W25;

        $(`#td-unknown-degree1-${condId}`).html(Y44.toFixed(2));
        $(`#td-calculated-roof-plane-length-${condId}`).html(W39.toFixed(2));
        $(`#td-diff-between-measured-and-calculated-${condId}`).html(W40.toFixed(2));

    }
    else {
        var W25 = parseFloat($(`#td-sum-of-length-entered-${condId}`).html());
        var W36 = parseFloat($(`#td-total-length-entered-${condId}`).html());
        var W44 = degreeToRadian(F139);
        var W41 = Math.sin(W44) * W25;
        var Y44 = W41;
        var W39 = W36 / Math.cos(W44);
        var W40 = W39 - W25;

        $(`#td-unknown-degree1-${condId}`).html(Y44.toFixed(2));
        $(`#td-calculated-roof-plane-length-${condId}`).html(W39.toFixed(2));
        $(`#td-diff-between-measured-and-calculated-${condId}`).html(W40.toFixed(2));
    }
    
    var option_number_segements1 = document.getElementsByClassName(`${condId}-option-number-segments1`)[0];
    var selected1 = option_number_segements1.options[option_number_segements1.selectedIndex];
    var F148 = selected1 ? selected1.getAttribute('data-value') : 4;

    var option_number_segements2 = document.getElementsByClassName(`${condId}-option-number-segments2`)[0];
    var selected2 = option_number_segements2.options[option_number_segements2.selectedIndex];
    var F162 = selected2 ? selected2.getAttribute('data-value') : 3;

    // Calculate Points
    var V19 = 1, V20 = 2, V21 = 3, V22 = 4, V23 = 5, V24 = 6;
    var V30 = 1, V31 = 2, V32 = 3, V33 = 4, V34 = 5, V35 = 6;
    var U19 = (V19 <= F148), U20 = (V20 <= F148), U21 = (V21 <= F148),
        U22 = (V22 <= F148), U23 = (V23 <= F148), U24 = (V24 <= F148);
    var U30 = (V30 <= F162), U31 = (V31 <= F162), U32 = (V32 <= F162),
        U33 = (V33 <= F162), U34 = (V34 <= F162), U35 = (V35 <= F162);
    
    var PQ18, PQ19, PQ20, PQ21, PQ22, PQ23;
    PQ18 = [0, 0];
    globalRoofPoints[condId].push(PQ18);
    globalFloorPoints[condId].push(PQ18);

    var O19 = parseFloat($(`#txt-roof-segment1-length-${condId}`).val());
    if (U19 == true) { PQ19 = [Math.cos(W44) * O19, Math.sin(W44) * O19]; globalRoofPoints[condId].push(PQ19); }

    var O20 = O19 + parseFloat($(`#txt-roof-segment2-length-${condId}`).val());
    if (U20 == true) { PQ20 = [Math.cos(W44) * O20, Math.sin(W44) * O20]; globalRoofPoints[condId].push(PQ20); }

    var O21 = O20 + parseFloat($(`#txt-roof-segment3-length-${condId}`).val());
    if (U21 == true) { PQ21 = [Math.cos(W44) * O21, Math.sin(W44) * O21]; globalRoofPoints[condId].push(PQ21); }

    var O22 = O21 + parseFloat($(`#txt-roof-segment4-length-${condId}`).val());
    if (U22 == true) { PQ22 = [Math.cos(W44) * O22, Math.sin(W44) * O22]; globalRoofPoints[condId].push(PQ22); }

    var O23 = O22 + parseFloat($(`#txt-roof-segment5-length-${condId}`).val());
    if (U23 == true) { PQ23 = [Math.cos(W44) * O23, Math.sin(W44) * O23]; globalRoofPoints[condId].push(PQ23); }

    var O24 = O23 + parseFloat($(`#txt-roof-segment6-length-${condId}`).val());
    if (U24 == true) { PQ24 = [Math.cos(W44) * O24, Math.sin(W44) * O24]; globalRoofPoints[condId].push(PQ24); }

    var W45 = 0;
    var PQ30, PQ31, PQ32, PQ33, PQ34, PQ35;

    var O30 = parseFloat($(`#txt-floor-segment1-length-${condId}`).val());
    if (U30 == true) { PQ30 = [Math.cos(W45)*O30, 0]; globalFloorPoints[condId].push(PQ30); }

    var O31 = O30 + parseFloat($(`#txt-floor-segment2-length-${condId}`).val());
    if (U31 == true) { PQ31 = [Math.cos(W45)*O31, 0]; globalFloorPoints[condId].push(PQ31); }

    var O32 = O31 + parseFloat($(`#txt-floor-segment3-length-${condId}`).val());
    if (U32 == true) { PQ32 = [Math.cos(W45)*O32, 0]; globalFloorPoints[condId].push(PQ32); }

    var O33 = O32 + parseFloat($(`#txt-floor-segment4-length-${condId}`).val());
    if (U33 == true) { PQ33 = [Math.cos(W45)*O33, 0]; globalFloorPoints[condId].push(PQ33); }

    var O34 = O33 + parseFloat($(`#txt-floor-segment5-length-${condId}`).val());
    if (U34 == true) { PQ34 = [Math.cos(W45)*O34, 0]; globalFloorPoints[condId].push(PQ34); }

    var O35 = O34 + parseFloat($(`#txt-floor-segment6-length-${condId}`).val());
    if (U35 == true) { PQ35 = [Math.cos(W45)*O35, 0]; globalFloorPoints[condId].push(PQ35); }

    // create roof lines
    var lineIndex = 1;
    globalRoofLines[condId] = [];
    for (index = 0; index < globalRoofPoints[condId].length - 1; index++) {
        globalRoofLines[condId][lineIndex] = [globalRoofPoints[condId][index], globalRoofPoints[condId][index + 1]];
        lineIndex++;
    }

    // create floor lines
    globalFloorLines[condId] = [];
    for (index = 0; index < globalFloorPoints[condId].length - 1; index++) {
        globalFloorLines[condId][lineIndex] = [globalFloorPoints[condId][index], globalFloorPoints[condId][index + 1]];
        lineIndex++;   
    }

    // create di­agonal1 lines
    globalDiagnoal1Lines[condId] = [];
    for (index = 0; index < globalDiagnoals1[condId]; index++) {
        globalDiagnoal1Lines[condId][lineIndex] = [globalRoofPoints[condId][index + 1], globalFloorPoints[condId][index + 1]];
        lineIndex++;        
    }

    // create di­agonal2 lines
    globalDiagnoal2Lines[condId] = [];
    globalDiagnoal2ReverseLines[condId] = [];
    for (index = 0; index < globalDiagnoals2[condId]; index++) {
        globalDiagnoal2Lines[condId][lineIndex] = [globalRoofPoints[condId][index + 2], globalFloorPoints[condId][index + 1]];
        if(index + 2 < globalFloorPoints[condId].length)
            globalDiagnoal2ReverseLines[condId][lineIndex] = [globalRoofPoints[condId][index + 1], globalFloorPoints[condId][index + 2]];
        lineIndex++;
    }

    // create di­agonal2 lines
    // globalDiagnoal2ReverseLines[condId] = [];
    // for (index = 0; index < globalDiagnoals2[condId]; index++) {
    //     if(index + 2 < globalFloorPoints[condId].length)
    //     {
    //         globalDiagnoal2ReverseLines[condId][lineIndex] = [globalRoofPoints[condId][index + 1], globalFloorPoints[condId][index + 2]];
    //         lineIndex++;
    //     }
    // }

    // draw graphs
    drawTrussGraph(condId);
}

// ----- Truss segement & comments functions --------------
var updateTrussAndComments = function(condId, keepStatus) {
    var roofPlane = parseInt($(`#option-number-segments1-${condId}`).children("option:selected").val());
    var floorPlane = parseInt($(`#option-number-segments2-${condId}`).children("option:selected").val());

    for (index = 0; index < roofPlane; index++) {
        $(`#td-roof-segment${index+1}-caption-${condId}`).html("Segment " + (index + 1) + " Length (ft | in)");
        $(`#td-truss-roof-segment${index+1}-${condId}`).html((index + 1));
        $(`#td-truss-roof-segment${index+1}-${condId}`).addClass('w400-bdr').removeClass('w400-blue-bdr');
        $(`#td-truss-roof-segment${index+1}-type-${condId}`).addClass('w400-bdr').removeClass('w400-blue-bdr');

    }
    for (index = roofPlane; index < 6; index++) {
        $(`#td-roof-segment${index+1}-caption-${condId}`).html("");
        $(`#td-truss-roof-segment${index+1}-${condId}`).html("");
        $(`#td-truss-roof-segment${index+1}-${condId}`).removeClass('w400-bdr').addClass('w400-blue-bdr');
        $(`#td-truss-roof-segment${index+1}-type-${condId}`).removeClass('w400-bdr').addClass('w400-blue-bdr');
    }
    for (index = 0; index < floorPlane; index++) {
        $(`#td-floor-segment${index+1}-caption-${condId}`).html("Segment " + (index + roofPlane + 1) + " Length (ft | in)");
        $(`#td-truss-floor-segment${index+1}-${condId}`).html((index + roofPlane + 1));
        $(`#td-truss-floor-segment${index+1}-${condId}`).addClass('w400-bdr').removeClass('w400-blue-bdr');
        $(`#td-truss-floor-segment${index+1}-type-${condId}`).addClass('w400-bdr').removeClass('w400-blue-bdr');
    }
    for (index = floorPlane; index < 6; index++) {
        $(`#td-floor-segment${index+1}-caption-${condId}`).html("");
        $(`#td-truss-floor-segment${index+1}-${condId}`).html("");
        $(`#td-truss-floor-segment${index+1}-${condId}`).removeClass('w400-bdr').addClass('w400-blue-bdr');
        $(`#td-truss-floor-segment${index+1}-type-${condId}`).removeClass('w400-bdr').addClass('w400-blue-bdr');
    }

    globalDiagnoals1[condId] = Math.min(roofPlane, floorPlane);
    globalDiagnoals2[condId] = Math.min(roofPlane - 1, floorPlane);

    // Diagnoals 
    for (index = 0; index < globalDiagnoals1[condId]; index++) {
        if (keepStatus == false) {
            $(`#diag-1-${index+1}-${condId}`).prop( "checked", false );
        }
        $(`#td-diag-1-${index+1}-${condId}`).html(roofPlane + floorPlane + index+1);
        $(`#td-diag-1-${index+1}-${condId}`).removeClass('w400-blue-bdr').addClass('w400-bdr');
        $(`#td-diag-1-${index+1}-type-${condId}`).removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#td-diag-1-${index+1}-type-${condId} *`).attr('disabled', false);
    }
    for (index = globalDiagnoals1[condId]; index < 6; index++) {
        if (keepStatus == false) {
            $(`#diag-1-${index+1}-${condId}`).prop( "checked", false );
        }
        $(`#td-diag-1-${index+1}-${condId}`).html('');
        $(`#td-diag-1-${index+1}-${condId}`).addClass('w400-blue-bdr').removeClass('w400-bdr');
        $(`#td-diag-1-${index+1}-type-${condId}`).addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#td-diag-1-${index+1}-type-${condId} *`).attr('disabled', true);
    }
    for (index = 0; index < globalDiagnoals2[condId]; index++) {
        if (keepStatus == false) {
            $(`#diag-2-${index+1}-${condId}`).prop( "checked", false );
        }
        $(`#td-diag-2-${index+1}-${condId}`).html(roofPlane + floorPlane + globalDiagnoals1[condId] + index+1);
        $(`#td-diag-2-${index+1}-${condId}`).removeClass('w400-blue-bdr').addClass('w400-bdr');
        $(`#td-diag-2-${index+1}-type-${condId}`).removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#td-diag-2-${index+1}-type-${condId} *`).attr('disabled', false);
        $(`#td-diag-2-${index+1}-reverse-${condId}`).removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#td-diag-2-${index+1}-reverse-${condId} *`).attr('disabled', false);
    }
    for (index = globalDiagnoals2[condId]; index < 6; index++) {
        if (keepStatus == false) {
            $(`#diag-2-${index+1}-${condId}`).prop( "checked", false );
        }
        $(`#td-diag-2-${index+1}-${condId}`).html('');
        $(`#td-diag-2-${index+1}-${condId}`).addClass('w400-blue-bdr').removeClass('w400-bdr');
        $(`#td-diag-2-${index+1}-type-${condId}`).addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#td-diag-2-${index+1}-type-${condId} *`).attr('disabled', true);
        $(`#td-diag-2-${index+1}-reverse-${condId}`).addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#td-diag-2-${index+1}-reverse-${condId} *`).attr('disabled', true);
    }

    updateRoofSlopeAnotherField(condId);
}

// -------------  Roof Plane ------------------
var updateNumberSegment1 = function (condId, roofPlane, keepStatus = true) {
    var totalLength = 0;
    roofPlane = parseInt(roofPlane);

    for (index = 0; index < roofPlane; index++) {
        totalLength += parseFloat($(`#txt-roof-segment${index + 1}-length-${condId}`).val());

        // enable appropricate cells        
        $(`#td-roof-segment${index + 1}-length-f-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#td-roof-segment${index + 1}-length-i-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        //$(`#td-roof-segment${index + 1}-length-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#txt-roof-segment${index + 1}-length-${condId}`).css('display', 'block');
        $(`#td-roof-segment${index + 1}-length-f-${condId} *`).attr('disabled', false);
        $(`#td-roof-segment${index + 1}-length-i-${condId} *`).attr('disabled', false);
        $(`#td-roof-segment${index + 1}-length-${condId} *`).attr('disabled', false);
    }
    for (index = roofPlane; index < 6; index++) {
        // disable appropricate cells        
        $(`#td-roof-segment${index + 1}-length-f-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#td-roof-segment${index + 1}-length-i-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        //$(`#td-roof-segment${index + 1}-length-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#txt-roof-segment${index + 1}-length-${condId}`).css('display', 'none');
        $(`#td-roof-segment${index + 1}-length-f-${condId} *`).attr('disabled', true);
        $(`#td-roof-segment${index + 1}-length-i-${condId} *`).attr('disabled', true);
        $(`#td-roof-segment${index + 1}-length-${condId} *`).attr('disabled', true);
    }

    $(`#td-sum-of-length-entered-${condId}`).html(totalLength.toFixed(2));

    var checkZero = false;
    for (index = 0; index < roofPlane; index++) {
        var val = parseFloat($(`#txt-roof-segment${index + 1}-length-${condId}`).val());
        if (val.toFixed(2) == 0.00) {
            $(`#td-checksum-of-segment1-${condId}`).html("Zero value is not acceptable for each segment length");
            $(`#td-checksum-of-segment1-${condId}`).css('background-color', '#FFC7CE');
            checkZero = true;
        }
    }

    if (checkZero){
        updateTrussAndComments(condId, keepStatus);
        return;
    }

    var lengthRoofPlane = parseFloat($(`#txt-length-of-roof-plane-${condId}`).val());
    if (Math.abs(lengthRoofPlane.toFixed(2) - totalLength.toFixed(2)) <= parseFloat($('#companyOffset').val())) {
        $(`#td-checksum-of-segment1-${condId}`).html("OK");
        $(`#td-checksum-of-segment1-${condId}`).css('background-color', 'white');
    }
    else if (lengthRoofPlane.toFixed(2) < totalLength.toFixed(2)) {
        $(`#td-checksum-of-segment1-${condId}`).html("Segments add to greater than total length");
        $(`#td-checksum-of-segment1-${condId}`).css('background-color', '#FFC7CE');
    }
    else {
        $(`#td-checksum-of-segment1-${condId}`).html("Segments add to less than total length");
        $(`#td-checksum-of-segment1-${condId}`).css('background-color', '#FFC7CE');
    }

    updateTrussAndComments(condId, keepStatus);
}

// ---------------- Floor Plane --------------------------
var updateNumberSegment2 = function (condId, floorPlane, keepStatus = true) {
    var totalLength = 0;
    floorPlane = parseInt(floorPlane);

    for (index = 0; index < floorPlane; index++) {
        totalLength += parseFloat($(`#txt-floor-segment${index + 1}-length-${condId}`).val());

        // enable appropricate cells        
        $(`#td-floor-segment${index + 1}-length-f-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#td-floor-segment${index + 1}-length-i-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        //$(`#td-floor-segment${index + 1}-length-${condId}`).addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#txt-floor-segment${index + 1}-length-${condId}`).css('display', 'block');
        $(`#td-floor-segment${index + 1}-length-f-${condId} *`).attr('disabled', false);
        $(`#td-floor-segment${index + 1}-length-i-${condId} *`).attr('disabled', false);
        $(`#td-floor-segment${index + 1}-length-${condId} *`).attr('disabled', false);
    }
    for (index = floorPlane; index < 6; index++) {
        // disable appropricate cells        
        $(`#td-floor-segment${index + 1}-length-f-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#td-floor-segment${index + 1}-length-i-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        //$(`#td-floor-segment${index + 1}-length-${condId}`).removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#txt-floor-segment${index + 1}-length-${condId}`).css('display', 'none');
        $(`#td-floor-segment${index + 1}-length-f-${condId} *`).attr('disabled', true);
        $(`#td-floor-segment${index + 1}-length-i-${condId} *`).attr('disabled', true);
        $(`#td-floor-segment${index + 1}-length-${condId} *`).attr('disabled', true);
    }

    $(`#td-total-length-entered-${condId}`).html(totalLength.toFixed(2));

    var checkZero = false;
    for (index = 0; index < floorPlane; index++) {
        var val = parseFloat($(`#txt-floor-segment${index + 1}-length-${condId}`).val());
        if (val.toFixed(2) == 0.00) {
            $(`#td-checksum-of-segment2-${condId}`).html("Zero value is not acceptable for each segment length");
            $(`#td-checksum-of-segment2-${condId}`).css('background-color', '#FFC7CE');
            checkZero = true;
        }
    }

    if (checkZero){
        updateTrussAndComments(condId, keepStatus);
        return;
    }
        
    var lengthFloorPlane = parseFloat($(`#txt-length-of-floor-plane-${condId}`).val());
    if (Math.abs(lengthFloorPlane.toFixed(2) - totalLength.toFixed(2)) <= parseFloat($('#companyOffset').val())) {
        $(`#td-checksum-of-segment2-${condId}`).html("OK");
        $(`#td-checksum-of-segment2-${condId}`).css('background-color', 'white');
    }
    else if (lengthFloorPlane.toFixed(2) < totalLength.toFixed(2)) {
        $(`#td-checksum-of-segment2-${condId}`).html("Segments add to greater than total length");
        $(`#td-checksum-of-segment2-${condId}`).css('background-color', '#FFC7CE');
    }
    else {
        $(`#td-checksum-of-segment2-${condId}`).html("Segments add to less than total length");
        $(`#td-checksum-of-segment2-${condId}`).css('background-color', '#FFC7CE');
    }

    updateTrussAndComments(condId, keepStatus);
}

var updateRoofMemberType = function(condId, selectedVal) {
    for (index = 0; index<6; index++) {
        $(`#td-truss-roof-segment${index+1}-type-${condId}`).html(selectedVal);
    }
}

var updateFloorMemberType = function(condId, selectedVal) {
    for (index = 0; index<6; index++) {
        $(`#td-truss-floor-segment${index+1}-type-${condId}`).html(selectedVal);
    }
}

var stick_grid_size = [45, 45, 45, 45, 45, 45, 45, 45, 45, 45, 45];
var stick_x_axis_starting_point = new Array(11); stick_x_axis_starting_point.fill({ number: 1, suffix: '' });
var stick_y_axis_starting_point = new Array(11); stick_y_axis_starting_point.fill({ number: 1, suffix: '' });

var stick_canvas = new Array(11);
var stick_ctx = new Array(11);
var stick_canvas_width = new Array(11);
var stick_canvas_height = new Array(11);
var stick_num_lines_x = new Array(11);
var stick_num_lines_y = new Array(11);
var stick_x_axis_distance_grid_lines = new Array(11);
var stick_y_axis_distance_grid_lines = new Array(11);
var stick_show_axis = new Array(11);
var stick_right_input = new Array(11);
var stick_input_changed = new Array();


for( let i = 1; i <= 10; i ++ )
{
    stick_canvas[i] = document.getElementById(`stick-canvas-${i}`);
    stick_ctx[i] = stick_canvas[i].getContext("2d");
    stick_canvas_width[i] = stick_canvas[i].width;
    stick_canvas_height[i] = stick_canvas[i].height;
    stick_num_lines_x[i] = Math.floor(stick_canvas_height[i] / stick_grid_size[i]);
    stick_num_lines_y[i] = Math.floor(stick_canvas_width[i] / stick_grid_size[i]);
    stick_x_axis_distance_grid_lines[i] = stick_num_lines_x[i] - 1;
    stick_y_axis_distance_grid_lines[i] = 0;
    stick_show_axis[i] = false;
    stick_right_input[i] = '';
    stick_input_changed[i] = [];
    
    // Translate to the new origin. Now Y-axis of the canvas is opposite to the Y-axis of the graph. So the y-coordinate of each element will be negative of the actual
    stick_ctx[i].translate(stick_y_axis_distance_grid_lines[i] * stick_grid_size[i], stick_x_axis_distance_grid_lines[i] * stick_grid_size[i]);
}

var drawStickBaseLine = function( condId ) {
    // erase
    // ctx.clearRect( 0, grid_size, canvas_width, - canvas_height);
    stick_ctx[condId].clearRect( 0, 100, stick_canvas_width[condId] + 100, - stick_canvas_height[condId] - 100);

    var angleRadian = degreeToRadian( parseFloat($(`#a-7-${condId}`).val()) );
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
    var overhangX = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian )) : 0;
    var overhangY = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(angleRadian)) : 0;

    stick_ctx[condId].translate(overhangX, - overhangY);
    
    if( stick_show_axis[condId] )
    {
        // Draw grid lines along X-axis
        for(var i = 0; i <= stick_num_lines_x[condId]; i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            
            // If line represents X-axis draw in different color
            if(i == 0/*x_axis_distance_grid_lines*/) 
                stick_ctx[condId].strokeStyle = "#000000";
            else
                stick_ctx[condId].strokeStyle = "#e9e9e9";
            
            if(i == stick_num_lines_x[condId]) {
                stick_ctx[condId].moveTo(0, stick_grid_size[condId] * i);
                stick_ctx[condId].lineTo(stick_canvas_width[condId], stick_grid_size[condId] * i);
            }
            else {
                stick_ctx[condId].moveTo(0, -stick_grid_size[condId] * i + 0.5);
                stick_ctx[condId].lineTo(stick_canvas_width[condId], -stick_grid_size[condId] * i + 0.5);
            }
            stick_ctx[condId].stroke();
        }

        // Draw grid lines along Y-axis
        for(i = 0; i <= stick_num_lines_y[condId]; i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            
            // If line represents X-axis draw in different color
            if(i == stick_y_axis_distance_grid_lines[condId]) 
                stick_ctx[condId].strokeStyle = "#000000";
            else
                stick_ctx[condId].strokeStyle = "#e9e9e9";
            
            if(i == stick_num_lines_y[condId]) {
                stick_ctx[condId].moveTo(stick_grid_size[condId] * i, 0);
                stick_ctx[condId].lineTo(stick_grid_size[condId] * i, -stick_canvas_height[condId]);
            }
            else {
                stick_ctx[condId].moveTo(stick_grid_size[condId] * i + 0.5, 0);
                stick_ctx[condId].lineTo(stick_grid_size[condId] * i + 0.5, -stick_canvas_height[condId]);
            }
            stick_ctx[condId].stroke();
        }


        // Ticks marks along the positive X-axis
        for(i = 2; i < (stick_num_lines_y[condId] - stick_y_axis_distance_grid_lines[condId]); i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            stick_ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            stick_ctx[condId].moveTo(stick_grid_size[condId] * i + 0.5, -3);
            stick_ctx[condId].lineTo(stick_grid_size[condId] * i + 0.5, 3);
            stick_ctx[condId].stroke();

            // Text value at that point
            stick_ctx[condId].font = '9px Arial';
            stick_ctx[condId].textAlign = 'start';
            stick_ctx[condId].fillStyle = '#000000';
            stick_ctx[condId].fillText(stick_x_axis_starting_point[condId].number * i + stick_x_axis_starting_point[condId].suffix, stick_grid_size[condId] * i - 2, 15);
        }

        // Ticks marks along the negative X-axis
        for(i = 2; i < stick_y_axis_distance_grid_lines[condId]; i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            stick_ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            stick_ctx[condId].moveTo(-stick_grid_size[condId] * i + 0.5, -3);
            stick_ctx[condId].lineTo(-stick_grid_size[condId] * i + 0.5, 3);
            stick_ctx[condId].stroke();

            // Text value at that point
            stick_ctx[condId].font = '9px Arial';
            stick_ctx[condId].textAlign = 'end';
            stick_ctx[condId].fillStyle = '#000000';
            stick_ctx[condId].fillText(-stick_x_axis_starting_point[condId].number * i + stick_x_axis_starting_point[condId].suffix, -stick_grid_size[condId] * i + 3, 15);
        }

        // Ticks marks along the positive Y-axis
        // Positive Y-axis of graph is negative Y-axis of the canvas
        for(i = 2; i < (stick_num_lines_x[condId] - stick_x_axis_distance_grid_lines[condId]); i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            stick_ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            stick_ctx[condId].moveTo(-3, stick_grid_size[condId] * i + 0.5);
            stick_ctx[condId].lineTo(3, stick_grid_size[condId] * i + 0.5);
            stick_ctx[condId].stroke();

            // Text value at that point
            stick_ctx[condId].font = '9px Arial';
            stick_ctx[condId].textAlign = 'start';
            stick_ctx[condId].fillStyle = '#000000';
            stick_ctx[condId].fillText(-stick_y_axis_starting_point[condId].number * i + stick_y_axis_starting_point[condId].suffix, 8, stick_grid_size[condId] * i + 3);
        }

        // Ticks marks along the negative Y-axis
        // Negative Y-axis of graph is positive Y-axis of the canvas
        for(i = 2; i < stick_x_axis_distance_grid_lines[condId]; i += 2) {
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 1;
            stick_ctx[condId].strokeStyle = "#000000";

            // Draw a tick mark 6px long (-3 to 3)
            stick_ctx[condId].moveTo(-3, -stick_grid_size[condId] * i + 0.5);
            stick_ctx[condId].lineTo(3, -stick_grid_size[condId] * i + 0.5);
            stick_ctx[condId].stroke();

            // Text value at that point
            stick_ctx[condId].font = '9px Arial';
            stick_ctx[condId].textAlign = 'start';
            stick_ctx[condId].fillStyle = '#000000';
            stick_ctx[condId].fillText(stick_y_axis_starting_point[condId].number * i + stick_y_axis_starting_point[condId].suffix, 8, -stick_grid_size[condId] * i + 3);
        }
    }
}

var adjustStickDrawingPanel = function( condId, isIBC = false ) {
    var topYPoint = 0, topXPoint = 0;

    var angleRadian = degreeToRadian( parseFloat($(`#a-7-${condId}`).val()) );
    var roofHeight;
    //if(stick_right_input[condId] == 'height')
        roofHeight = parseFloat($(`#a-9-${condId}`).val());
    // else if(stick_right_input[condId] == 'diagnol')
    //     roofHeight = parseFloat($(`#a-8-${condId}`).val()) * Math.sin(angleRadian);
    // else if(stick_right_input[condId] == 'length')
    //     roofHeight = parseFloat($(`#a-10-${condId}`).val()) * Math.tan(angleRadian);
    
    var overhangX = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? parseFloat($(`#a-11-${condId}`).val()) * Math.sin(Math.PI / 2 - angleRadian) : 0;
    var overhangY = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? parseFloat($(`#a-11-${condId}`).val()) * Math.sin(angleRadian) : 0;

    var moduleCount = parseInt($(`#f-1-${condId}`).val());
    var moduleGap = parseFloat($(`#g-1-${condId}`).val()) / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;

    var moduleLengthSum = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? parseFloat($(`#e-1-${condId}`).val()) : parseFloat($(`#a-11-${condId}`).val());
    if(!isIBC){
        var orientation;
        for(let i = 1; i <= moduleCount; i ++)
        {
            orientation = false;
            if($(`#a-6-${condId}`).val() == "Portrait")
                orientation = true;
            if($(`#h-${i}-${condId}`)[0].checked)
                orientation = !orientation;
            
            moduleLengthSum += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
        }
        moduleLengthSum -= moduleGap;

        // Show alert when module length is longer
        if( roofHeight + overhangY < Math.sin(angleRadian) * moduleLengthSum || (angleRadian != 0 && (1.0 / Math.tan(angleRadian)) * (roofHeight + overhangY) + overhangX < Math.cos(angleRadian) * moduleLengthSum))
            $(`#stick-module-alert-${condId}`).css('display', 'block');
        else
            $(`#stick-module-alert-${condId}`).css('display', 'none');
    } else {
        moduleLengthSum = 0;
        moduleHeight = 0;
        $(`#stick-module-alert-${condId}`).css('display', 'none');
    }

    topYPoint = Math.max(roofHeight + overhangY, Math.sin(angleRadian) * moduleLengthSum + moduleHeight * Math.sin(degreeToRadian( parseFloat($(`#g-2-${condId}`).val()) )));
    topXPoint = Math.max(angleRadian != 0 ? (1.0 / Math.tan(angleRadian)) * (roofHeight + overhangY) + overhangX : 0, Math.sin(Math.PI / 2 - angleRadian) * moduleLengthSum);
 
    // console.log("topXpoint : " + topXPoint);
    // console.log("topYpoint : " + topYPoint);

    var xx = Math.floor((stick_canvas_width[condId] - 100) / topXPoint);
    var yy = Math.floor((stick_canvas_height[condId] - 100) / topYPoint);  // for height adjustment

    // if (xx > yy) { 
    //     if (grid_size > yy) { grid_size = yy; }
    // }
    // else { 
    //     if (grid_size > xx) { grid_size = xx; }
    // }

    if (xx > yy) { stick_grid_size[condId] = yy; }
    else { stick_grid_size[condId] = xx; }

    // adjust grid_size        
    stick_num_lines_x[condId] = Math.floor(stick_canvas_height[condId] / stick_grid_size[condId]);
    stick_num_lines_y[condId] = Math.floor(stick_canvas_width[condId] / stick_grid_size[condId]);
    stick_x_axis_distance_grid_lines[condId] = stick_num_lines_x[condId] - 1;
}

var drawStickGraph = function( condId ) {
    if($(`#a-7-${condId}`).val() == "") // angle should not be empty
        return;

    var isIBC = $(`#trussFlagOption-${condId}-3`)[0].checked;
    
    adjustStickDrawingPanel(condId, isIBC);
    drawStickBaseLine(condId);

    var label_index = 1;

    // Draw Overhang
    var angle = parseFloat($(`#a-7-${condId}`).val());
    var angleRadian = degreeToRadian(angle);
    
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
    var uphillDist = parseFloat($(`#e-1-${condId}`).val());

    if($(`#trussFlagOption-${condId}-4`)[0].checked == false){
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(0, 0);
        stick_ctx[condId].lineTo( - Math.sin(Math.PI / 2 - angleRadian) * overhangLength * stick_grid_size[condId], Math.cos(Math.PI / 2 - angleRadian) * overhangLength * stick_grid_size[condId]);
        stick_ctx[condId].stroke();

        // Draw Wall
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(0, 0);
        stick_ctx[condId].lineTo(0, 100);
        stick_ctx[condId].stroke();

        stick_ctx[condId].font = '16px Arial';
        stick_ctx[condId].textAlign = 'end';
        stick_ctx[condId].fillStyle = "#000000";
        stick_ctx[condId].rotate(- Math.PI / 2);
        stick_ctx[condId].fillText("Wall", -20, 20);
        stick_ctx[condId].rotate(Math.PI / 2);
    }

    // Draw Roof
    //var roofHeight;
    //if(stick_right_input[condId] == 'height')
        roofHeight = parseFloat($(`#a-9-${condId}`).val());
    //else if(stick_right_input[condId] == 'diagnol')
        //roofHeight = parseFloat($(`#a-8-${condId}`).val()) * Math.sin(angleRadian);
    //else if(stick_right_input[condId] == 'length')
        //roofHeight = parseFloat($(`#a-10-${condId}`).val()) * Math.tan(angleRadian);

    if( angleRadian != 0 ){ // Draw Roof when angle is not 0
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(0, 0);
        stick_ctx[condId].lineTo(roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId], - roofHeight * stick_grid_size[condId]);
        stick_ctx[condId].stroke();
    }
    else{
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(0, 0);
        stick_ctx[condId].lineTo(stick_canvas_width[condId], 0);
        stick_ctx[condId].stroke();
    }

    // Draw Floor
    stick_ctx[condId].beginPath();
    stick_ctx[condId].lineWidth = 2;
    stick_ctx[condId].strokeStyle = "#0000FF";
    stick_ctx[condId].moveTo(0, 0);
    stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, 0);
    stick_ctx[condId].stroke();

    stick_ctx[condId].font = '16px Arial';
    stick_ctx[condId].textAlign = 'start';
    if( angleRadian != 0 )
        stick_ctx[condId].fillText($(`#trussFlagOption-${condId}-4`)[0].checked == false ? "Attic Floor" : "Ground", roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] / 2, 30);
    else
        stick_ctx[condId].fillText($(`#trussFlagOption-${condId}-4`)[0].checked == false ? "Attic Floor" : "Ground", stick_canvas_width[condId] / 2, 30);

    // Draw dashed line
    stick_ctx[condId].beginPath();
    stick_ctx[condId].lineWidth = 2;
    stick_ctx[condId].strokeStyle = "#0000FF";
    stick_ctx[condId].setLineDash([25, 25]);
    stick_ctx[condId].moveTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, 0);
    stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - roofHeight * stick_grid_size[condId]);

    stick_ctx[condId].stroke();
    stick_ctx[condId].setLineDash([25, 0]);

    if(!isIBC){
        // Draw Knee Wall
        var kneeWallHeight = $(`#c-4-${condId}`).val() == "" ? 0 : parseFloat($(`#c-4-${condId}`).val());

        if( kneeWallHeight <= roofHeight )
        {
            $(`#c-4-warn-${condId}`).css('display', 'none');
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 2;
            stick_ctx[condId].strokeStyle = "#0000FF";
            stick_ctx[condId].moveTo(kneeWallHeight * (1 / Math.tan(angleRadian)) * stick_grid_size[condId], 0);
            stick_ctx[condId].lineTo(kneeWallHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId], - kneeWallHeight * stick_grid_size[condId]);
            stick_ctx[condId].stroke();
        }
        else
            $(`#c-4-warn-${condId}`).css('display', 'block');

        // Draw Required Collar Tie
        if($(`#collartie-${condId}`).css('display') == 'table-row'){
            var newTieHeight = $(`#collartie-height-${condId}`).html();
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 2;
            stick_ctx[condId].strokeStyle = "#FF0000";
            stick_ctx[condId].setLineDash([5, 5]);
            stick_ctx[condId].moveTo(angleRadian != 0 ? newTieHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - newTieHeight * stick_grid_size[condId]);
            stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - newTieHeight * stick_grid_size[condId]);
            stick_ctx[condId].stroke();
            stick_ctx[condId].setLineDash([5, 0]);
            stick_ctx[condId].fillStyle = "#FF0000";
            stick_ctx[condId].fillText("Prop Collar Tie",  newTieHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] / 2 + roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] / 2 - 50, - newTieHeight * stick_grid_size[condId] + 20);
        }

        // Draw Collar Tie
        var collarTieHeight = $(`#c-2-${condId}`).val() == "" ? 0 : parseFloat($(`#c-2-${condId}`).val());
        if( collarTieHeight <= roofHeight )
        {
            $(`#c-2-warn-${condId}`).css('display', 'none');
            stick_ctx[condId].beginPath();
            stick_ctx[condId].lineWidth = 2;
            stick_ctx[condId].strokeStyle = "#0000FF";
            stick_ctx[condId].moveTo(angleRadian != 0 ? collarTieHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - collarTieHeight * stick_grid_size[condId]);
            stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - collarTieHeight * stick_grid_size[condId]);
            stick_ctx[condId].stroke();
        }
        else
            $(`#c-2-warn-${condId}`).css('display', 'block');

        // Draw solar rectangles
        var startModule = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? overhangLength - uphillDist : -overhangLength;
        var moduleDepth = 1.17 / 12;
        var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
        var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
        var moduleGap = parseFloat($(`#g-1-${condId}`).val()) / 12;

        var startPoint = [- Math.sin(Math.PI / 2 - angleRadian) * startModule * stick_grid_size[condId] - stick_grid_size[condId] / 4 * Math.sin(angleRadian), Math.cos(Math.PI / 2 - angleRadian) * startModule * stick_grid_size[condId] - stick_grid_size[condId] / 4];
        stick_ctx[condId].translate(startPoint[0], startPoint[1]);
        stick_ctx[condId].rotate(- angleRadian);
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#000000";

        //totalRoofLength = parseFloat($(`#a-9-${condId}`).val()) / Math.sin(angleRadian);
        var moduleCount = parseInt($(`#f-1-${condId}`).val());

        let moduleStartX = 0;
        var orientation = false;

        var supportStart = parseFloat($(`#e-2-${condId}`).val()) - parseFloat($(`#e-1-${condId}`).val());
        var moduleTilt = degreeToRadian(parseFloat($(`#g-2-${condId}`).val()));

        stick_ctx[condId].fillStyle = "#000";
        for(let i = 1; i <= moduleCount; i ++)
        {
            orientation = false;
            if($(`#a-6-${condId}`).val() == "Portrait")
                orientation = true;
            if($(`#h-${i}-${condId}`)[0].checked)
                orientation = !orientation;
            
            stick_ctx[condId].strokeStyle = "#000000";
            
            let curModuleWidth = (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight));
            if(moduleTilt >= 0){
                stick_ctx[condId].translate(moduleStartX * stick_grid_size[condId], 0);
                stick_ctx[condId].rotate(- moduleTilt);
                stick_ctx[condId].strokeRect(0, 0, curModuleWidth * stick_grid_size[condId], moduleDepth * stick_grid_size[condId]);
                // Left Support
                stick_ctx[condId].fillRect(supportStart * stick_grid_size[condId] - 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + Math.max(1, 1 / 12 * Math.tan(moduleTilt) * stick_grid_size[condId]) + supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
                // Right Support
                stick_ctx[condId].fillRect(curModuleWidth * stick_grid_size[condId] - stick_grid_size[condId] * (1 / 12 + supportStart) + 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + curModuleWidth * Math.tan(moduleTilt) * stick_grid_size[condId] +  Math.max(1, 1 / 12 * Math.tan(moduleTilt) * stick_grid_size[condId]) - supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
                stick_ctx[condId].rotate(moduleTilt);
                stick_ctx[condId].translate(- moduleStartX * stick_grid_size[condId], 0);
            }
            else{
                stick_ctx[condId].translate(moduleStartX * stick_grid_size[condId] + curModuleWidth * stick_grid_size[condId], 0);
                stick_ctx[condId].rotate(- moduleTilt);
                stick_ctx[condId].strokeRect(0, 0, - curModuleWidth * stick_grid_size[condId], moduleDepth * stick_grid_size[condId]);
                // Left Support
                stick_ctx[condId].fillRect(- curModuleWidth * stick_grid_size[condId] + supportStart * stick_grid_size[condId] - 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + Math.max(1, - 1 / 12 * Math.tan(moduleTilt) * stick_grid_size[condId]) - curModuleWidth * Math.tan(moduleTilt) * stick_grid_size[condId] + supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
                // Right Support
                stick_ctx[condId].fillRect(- stick_grid_size[condId] * (1 / 12 + supportStart) + 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + Math.max(1, - 1 / 12 * Math.tan(moduleTilt) * stick_grid_size[condId]) - supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
                stick_ctx[condId].rotate(moduleTilt);
                stick_ctx[condId].translate(- moduleStartX * stick_grid_size[condId] - curModuleWidth * stick_grid_size[condId], 0);
            }

            moduleStartX += (moduleGap + curModuleWidth);
        }

        stick_ctx[condId].rotate(angleRadian);
        stick_ctx[condId].translate(- startPoint[0], - startPoint[1]);
    }
    
    var overhangX = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian )) : 0;
    var overhangY = $(`#trussFlagOption-${condId}-4`)[0].checked == false ? Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(angleRadian )) : 0;
    stick_ctx[condId].translate(- overhangX, overhangY);
}

window.totalCount = 0; // file uploads count
window.finishedCount = 0; // file uploads count
window.uploadError = false; // file uploads count
window.uploadList = []; // file uploads

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // initialize equipment section
    var loadEquipmentSection = function() {
        $.ajax({
            url:"getPVModules",
            type:'post',
            dataType: "json",
            success:async function(res){
                if(res.length > 0)
                {
                    for(let i = 0; i < res.length; i ++){
                        availablePVModules.push([res[i]['mfr'], res[i]['model'], res[i]['rating'], res[i]['length'], res[i]['width'], res[i]['depth'], res[i]['weight'], res[i]['custom'], res[i]['favorite'], res[i]['crc32'], 0]);
                    }
                }

                if(localStorage.getItem('moduleCEC') == '1'){
                    moduleCEC = 1;
                    $("#module-cec")[0].checked = true;
                    await loadCECEquipmentSection();
                    cecLoaded = 1;
                }

                // ------------------- First Line ---------------------
                // pv module section
                $('#option-module-type').find('option').remove();
                mainTypes = getPVModuleTypes();

                // load selected from preloaded_data
                selectedMainType = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['PVModule'] != "undefined"
                  && typeof preloaded_data['Equipment']['PVModule']['Type'] != "undefined" ) {
                    selectedMainType = preloaded_data['Equipment']['PVModule']['Type'];
                }

                for (index=0; index<mainTypes.length; index++) 
                {
                    if (mainTypes[index] == selectedMainType) {
                        $('#option-module-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    }
                    else {
                        $('#option-module-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    }
                }

                // pv submodule section
                updatePVSubmoduleField(selectedMainType);
                for(let i = 1; i <= 10; i ++)
                {
                    drawTrussGraph(i);
                    drawStickGraph(i);
                }
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                console.log(res);
            }
        });

        $.ajax({
            url:"getPVInverters",
            type:'post',
            dataType: "json",
            success:function(res){
                if(res.length > 0)
                {
                    for(let i = 0; i < res.length; i ++){
                        availablePVInverters.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2'], res[i]['custom'], res[i]['favorite'], res[i]['crc32'], res[i]['watts']]);
                    }
                }
                // ------------------- Second Line ---------------------
                // inverter module section
                $('#option-inverter-type').find('option').remove();
                $('#option-inverter2-type').find('option').remove();
                $('#option-inverter3-type').find('option').remove();
                mainTypes = getPVInvertorTypes();

                // load selected from preloaded_data
                selectedMainType = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['PVInverter'] != "undefined"
                  && typeof preloaded_data['Equipment']['PVInverter']['Type'] != "undefined" ) {
                    selectedMainType = preloaded_data['Equipment']['PVInverter']['Type'];
                }
                selectedMainType2 = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['PVInverter_2'] != "undefined"
                  && typeof preloaded_data['Equipment']['PVInverter_2']['Type'] != "undefined" && preloaded_data['Equipment']['PVInverter_2']['Type'] != '') {
                    selectedMainType2 = preloaded_data['Equipment']['PVInverter_2']['Type'];
                }
                selectedMainType3 = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['PVInverter_3'] != "undefined"
                  && typeof preloaded_data['Equipment']['PVInverter_3']['Type'] != "undefined" && preloaded_data['Equipment']['PVInverter_3']['Type'] != '') {
                    selectedMainType3 = preloaded_data['Equipment']['PVInverter_3']['Type'];
                }

                for (index=0; index<mainTypes.length; index++) 
                {
                    if (mainTypes[index] == selectedMainType) 
                        $('#option-inverter-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    else
                        $('#option-inverter-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    if (mainTypes[index] == selectedMainType2) 
                        $('#option-inverter2-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    else
                        $('#option-inverter2-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    if (mainTypes[index] == selectedMainType3) 
                        $('#option-inverter3-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    else
                        $('#option-inverter3-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);                        
                }

                // inverter submodule section
                updatePVInvertorSubField(selectedMainType);
                updatePVInvertorSubField(selectedMainType2, "", 2);
                updatePVInvertorSubField(selectedMainType3, "", 3);
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                console.log(res);
            }
        });

        $.ajax({
            url:"getStanchions",
            type:'post',
            dataType: "json",
            success:function(res){
                if(res.length > 0)
                {
                    for(let i = 0; i < res.length; i ++){
                        availableStanchions.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2'], res[i]['custom'], res[i]['favorite'], res[i]['crc32']]);
                    }
                }
                // ------------------- Third Line ---------------------
                // Stanchion module section
                $('#option-stanchion-type').find('option').remove();
                mainTypes = getStanchionTypes();

                // load selected from preloaded_data
                selectedMainType = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['Stanchion'] != "undefined"
                  && typeof preloaded_data['Equipment']['Stanchion']['Type'] != "undefined" ) {
                    selectedMainType = preloaded_data['Equipment']['Stanchion']['Type'];
                }

                for (index=0; index<mainTypes.length; index++) 
                {
                    if (mainTypes[index] == selectedMainType) {
                        $('#option-stanchion-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    }
                    else {
                        $('#option-stanchion-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    }
                }

                // Stanchion submodule section
                updateStanchionSubField(selectedMainType);

                for(let i = 0; i < 10; i ++) {
                    $(`#j-1-${i + 1}`).find('option').remove();
                    selectedMainType = $("#option-stanchion-type").val();
                    if(preloaded_data && preloaded_data['LoadingCase'] && preloaded_data['LoadingCase'][i] && preloaded_data['LoadingCase'][i]['Stanchions'] && preloaded_data['LoadingCase'][i]['Stanchions']['J1']){
                        selectedMainType = preloaded_data['LoadingCase'][i]['Stanchions']['J1'];
                        if(preloaded_data['LoadingCase'][i]['Stanchions']['J1'] != $("#option-stanchion-type").val() || preloaded_data['LoadingCase'][i]['Stanchions']['J2'] != $("#option-stanchion-subtype").val())
                            $(`#j-4-${i + 1}`).val("1");
                    }
                    for (index=0; index<mainTypes.length; index++) 
                    {
                        if (mainTypes[index] == selectedMainType) 
                            $(`#j-1-${i + 1}`).append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                        else 
                            $(`#j-1-${i + 1}`).append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    }
                    updateFCStanchionSubField(selectedMainType, i);
                }
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                console.log(res);
            }
        });

        $.ajax({
            url:"getRailsupport",
            type:'post',
            dataType: "json",
            success:function(res){
                if(res.length > 0)
                {
                    for(let i = 0; i < res.length; i ++){
                        availableRailSupports.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2'], res[i]['custom'], res[i]['favorite'], res[i]['crc32']]);
                    }
                }
                // ------------------- Fourth Line ---------------------
                // Rail Support module section
                $('#option-railsupport-type').find('option').remove();
                mainTypes = getRailSupportTypes();

                // load selected from preloaded_data
                selectedMainType = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['RailSupportSystem'] != "undefined"
                  && typeof preloaded_data['Equipment']['RailSupportSystem']['Type'] != "undefined" ) {
                    selectedMainType = preloaded_data['Equipment']['RailSupportSystem']['Type'];
                }

                for (index=0; index<mainTypes.length; index++) 
                {
                    if (mainTypes[index] == selectedMainType) {
                        $('#option-railsupport-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    }
                    else {
                        $('#option-railsupport-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    }
                }

                // Rail Support submodule section
                updateRailSupportSubField(selectedMainType);
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                console.log(res);
            }
        });
    }
    var togglePlanCheck = function (){
        if($("#togglePlanCheck")[0].checked == true && ($("#toggleAsBuilt")[0].checked == true || $("#togglePIL")[0].checked == true)){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $("#togglePlanCheck")[0].checked = false;
            return;
        }
        
        var jobId = $('#projectId').val();

        $.ajax({
            url:"togglePlanCheck",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success){
                    return;
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

    var toggleAsBuilt = function(){
        if($("#toggleAsBuilt")[0].checked == true && ($("#togglePlanCheck")[0].checked == true || $("#togglePIL")[0].checked == true)){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $("#toggleAsBuilt")[0].checked = false;
            return;
        }
        
        var jobId = $('#projectId').val();

        $.ajax({
            url:"toggleAsBuilt",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success){
                    return;
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

    var togglePIL = function(){
        if($("#togglePIL")[0].checked == true && ($("#togglePlanCheck")[0].checked == true || $("#toggleAsBuilt")[0].checked == true)){
            swal.fire({ title: "Warning", text: "Only one type of review checkbox at a time please.", icon: "warning", confirmButtonText: `OK` });
            $("#togglePIL")[0].checked = false;
            return;
        }
        
        var jobId = $('#projectId').val();

        $.ajax({
            url:"togglePIL",
            type:'post',
            data:{jobId: jobId},
            success: function(res){
                if(res.success){
                    return;
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

    $("#togglePlanCheck").change(function(){
        togglePlanCheck();
    });

    $("#toggleAsBuilt").change(function(){
        toggleAsBuilt();
    });

    $("#togglePIL").change(function(){
        togglePIL();
    });

    var loadDataCheck = function(){
        return new Promise((resolve, reject) => {
            var projectId = $('#projectId').val();
            if(projectId >= 0){
                $.ajax({
                    url:"getDataCheck",
                    type:'post',
                    data:{projectId: projectId},
                    success:function(res){
                        if(res && res.success == true && res.data){
                            $('#exposureUnit').html(res.data.exposureUnit);
                            $('#exposureContent').html('Exposure Category (' + res.data.exposureContent + ')');
                            $('#occupancyUnit').html(res.data.occupancyUnit);
                            $('#occupancyContent').html('Occupancy Category / Risk Category (' + res.data.occupancyContent + ')');
                            $('#IBC').html(res.data.IBC);
                            $('#stateCode').html(res.data.stateCode);
                            $('#ASCE').html(res.data.ASCE);
                            $('#NEC').html(res.data.NEC);
                            if(res.data.windLoadingValue < 0){
                                $('#windLoadingValue').html('SPECIAL');
                                $('#windLoadingValue').css('color', 'red');
                                $('#windLoadingContent').html('Enter correct value in Override tab');
                                $('#windLoadingContent').css('color', 'red');
                            } else {
                                $('#windLoadingValue').html(res.data.windLoadingValue);
                                $('#windLoadingValue').css('color', 'black');
                                $('#windLoadingContent').html('mph - ' + res.data.windLoadingContent);
                                $('#windLoadingContent').css('color', 'black');
                            }
                            if(res.data.snowLoadingValue < 0){
                                $('#snowLoadingValue').html('SPECIAL');
                                $('#snowLoadingValue').css('color', 'red');
                                $('#snowLoadingContent').html("Enter correct value in Override tab");
                                $('#snowLoadingContent').css('color', 'red');
                            } else {
                                $('#snowLoadingValue').html(res.data.snowLoadingValue);
                                $('#snowLoadingValue').css('color', 'black');
                                $('#snowLoadingContent').html("psf - Ground Snow Load, 'pg' (" + res.data.snowLoadingContent + ")");
                                $('#snowLoadingContent').css('color', 'black');
                            }
                            $('#DCWatts').html(res.data.DCWatts);
                            $('#InverterAmperage').html(res.data.InverterAmperage);
                            $('#OCPDRating').html(res.data.OCPDRating);
                            $('#RecommendOCPD').html(res.data.RecommendOCPD);
                            $('#MinCu').html(res.data.MinCu);
                            
                            var collarHeights = res.data.collarHeights;
                            var haveChanges = false;
                            var ibcChanges = false;
                            var showIBC = false;
                            if(collarHeights){
                                for(let i = 1; i <= $('#option-number-of-conditions').val(); i ++){
                                    if(collarHeights.length >= 5 * i)
                                    {
                                        let pieceValue = collarHeights.slice(5 * (i - 1), 5 * i);
                                        let notesId = 0;
                                        if(res.data.structural_notes && res.data.structural_notes.length >= 2 * i)
                                            notesId = parseInt(res.data.structural_notes.slice(2 * (i - 1), 2 * i));
                                        if(!($(`#trussFlagOption-${i}-3`)[0].checked) && parseFloat(pieceValue) != 0)
                                        {
                                            haveChanges = true;
                                            if(notesId == 2)
                                                $(`#collartie-height-${i}`).html('Framing Error. See note below.');
                                            else
                                                $(`#collartie-height-${i}`).html(parseFloat(pieceValue).toFixed(2));
                                            $(`#collartie-title-${i}`).html(i + ': ' + $(`#a-5-${i}`).val());
                                            $(`#collartie-${i}`).css('display', "table-row");
                                            $(`#collartie-warning-${i}`).css('display', 'block');
                                            $(`#collartie-warning-${i}`).html('Framing modification required.  Add collar tie / knee wall at ' + parseFloat(pieceValue).toFixed(2) + ' ft.');
                                        }
                                        if($(`#trussFlagOption-${i}-3`)[0].checked){
                                            showIBC = true;
                                            $(`#ibc-dc-${i}`).css('display', "table-row");
                                            $(`#ibc-dc-title-${i}`).html(i + ': ' + $(`#a-5-${i}`).val());
                                            $(`#ibc-dc-max-${i}`).html(parseInt(pieceValue));
                                            let fcModuleCnt = $(`#f-1-${i}`).val();
                                            if(parseInt(pieceValue) >= fcModuleCnt){
                                                $(`#ibc-dc-msg-${i}`).html(`OK. ${fcModuleCnt} of modules used is within allowable limits.`);
                                                $(`#ibc-dc-title-${i}`).css('color', 'black');
                                                $(`#ibc-dc-max-${i}`).css('color', 'black');
                                                $(`#ibc-dc-msg-${i}`).css('color', 'black');
                                            } else {
                                                ibcChanges = true;
                                                $(`#ibc-dc-msg-${i}`).html(`ERROR. ${fcModuleCnt} of modules used exceeds allowable by ${fcModuleCnt - parseInt(pieceValue)}.`);
                                            }
                                        }
                                    }
                                }
                            }

                            if(showIBC){
                                $('#ibc-dc-table').css('display', 'block');
                                $('#ibc-dc-header').css('display', "table-row");
                                $('#ibc-dc-headers').css('display', "table-row");
                            }
                            if(ibcChanges){
                                $('#requiredNotes').css('color', 'red');
                                $('#requiredNotes').html(' *************** Roof Framing Changes are Required *************** ');
                            } else {
                                $('#ibc-dc-table').css('display', 'none');
                                if(!haveChanges){
                                    $('#requiredNotes').css('color', 'black');
                                    $('#requiredNotes').html(' *************** No Roof Framing Changes are Required *************** ');
                                }
                            }

                            if(haveChanges){
                                $('#collartie-header').css('display', "table-row");
                                $('#collartie-headers').css('display', "table-row");
                                $('#collartie-note').css('display', "table-row");
                                $('#requiredNotes').css('color', 'red');
                                $('#requiredNotes').html(' *************** Roof Framing Changes are Required *************** ');
                                $('#collartie-dc-table').css('display', 'inline-block');
                            } else {
                                $('#collartie-dc-table').css('display', 'none');
                                if(!ibcChanges){
                                    $('#requiredNotes').css('color', 'black');
                                    $('#requiredNotes').html(' *************** No Roof Framing Changes are Required *************** ');
                                }
                            }

                            haveChanges = false;
                            var notes = res.data.notes;
                            if(typeof notes == 'string'){
                                if(notes != ''){
                                    $(`tr#structural-notes`).css('display', "table-row");
                                    $(`td#structural-notes`).css('display', 'block');
                                    $('td#structural-notes').html(notes);
                                }
                            } else {
                                if(notes && notes.length > 0){
                                    for(let i = 1; i <= notes.length; i ++){
                                        if(notes[i - 1] != ""){
                                            haveChanges = true;
                                            $(`#structural-${i}`).css('display', "table-row");
                                            $(`#structural-title-${i}`).html('FC.' + i + ': ' + $(`#a-5-${i}`).val());
                                            $(`#structural-note-${i}`).html(notes[i - 1]);
                                        }
                                    }
                                    if(haveChanges){
                                        $('#structural-header').css('display', "table-row");
                                        $('#structural-headers').css('display', "table-row");
                                    }
                                }
                            }
                            $('#site-check-table').css('display', 'table');
                            $('#code-check-table').css('display', 'table');
                            $('#environment-check-table').css('display', 'table');
                            $('#electric-check-table').css('display', 'table');
                            resolve(true);
                        } else
                            resolve(true);
                    },
                    error: function(xhr, status, error) {
                        res = JSON.parse(xhr.responseText);
                        swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                        resolve(false);
                    }
                });
            } else
                resolve(true);
        });
    }

    var loadElectric = function(){
        if($("#inputform-electric").length > 0 && preloaded_data && preloaded_data['Electrical']) {
            for(let i = 1; i <= 10; i ++) {
                if(preloaded_data['Electrical']['StrTable'][`R${i}`]) {
                    $(`#R${i}`).css('display', 'table-row');
                    $(`#R${i} .Inv`).val(preloaded_data['Electrical']['StrTable'][`R${i}`]['InvNo']);
                    $(`#R${i} .String`).val(preloaded_data['Electrical']['StrTable'][`R${i}`]['StringNumber']);
                    $(`#R${i} .ModStr`).val(preloaded_data['Electrical']['StrTable'][`R${i}`]['ModulesPerString']);
                    $(`#R${i} .StrMPPT`).val(preloaded_data['Electrical']['StrTable'][`R${i}`]['StringsPerMPPT']);
                    $(`#R${i} .StrLength`).val(preloaded_data['Electrical']['StrTable'][`R${i}`]['StringLength']);
                }

                if(preloaded_data['Electrical']['ACTable'][`AC${i}`]) {
                    $(`#AC${i}`).css('display', 'table-row');
                    $(`#AC${i} .Inv`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['InvNo']);
                    $(`#AC${i} .WireLength`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['WireLength']);
                    $(`#AC${i} .MinWireSize`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['MinWireSize']);
                    $(`#AC${i} .Material`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['Material']);
                    $(`#AC${i} .InsulRating`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['InsulRating']);
                    $(`#AC${i} .Circuits`).val(preloaded_data['Electrical']['ACTable'][`AC${i}`]['Circuits']);
                }
            }

            $("#type-interconnection").val(preloaded_data['Electrical']['Main']['InterconnectionType']);
            $("#bus-bar-rating").val(preloaded_data['Electrical']['Main']['BusBarRating']);
            $("#main-breaker-rating").val(preloaded_data['Electrical']['Main']['MainBreakerRating']);
            $("#downgraded-breaker-rating").val(preloaded_data['Electrical']['Main']['DowngradedBreakerRating']);
            $("#PV-breaker-recommended").html($('#RecommendOCPD').html());
            $("#pv-breaker-selected").val(preloaded_data['Electrical']['Main']['PVBreakerSelected']);
        }
    }

    // component hander functions
    $("#txt-project-number").change(function(){
        setProjectIdComment();
    });

    $("#txt-city").change(function(){
        detectCorrectTown();
    });

    $('#option-user-id').on('change', function() {
        $.ajax({
            url:"submitProjectManager",
            type:'post',
            data:{
                projectId: $('#projectId').val(),
                userId: $('#option-user-id').val(),
            },
            success:function(res){
                if (res.status == true) {
                    message = 'Project Manager has been changed successfully';
                    swal.fire({
                        title: "Success",
                        text: message,
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: `Ok`,
                    })
                } else {
                    // error handling
                    swal.fire({ title: "Error",
                        text: "Error happened while changing project manager. Please try again later.",
                        icon: "error",
                        confirmButtonText: `OK` });
                }
            },
            error: function(xhr, status, error) {
                swal.close();
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while changing project manager. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
        //aaaa
    });

    $('#option-state').on('change', function() {
        detectCorrectTown();
        loadASCEOptions($(this).val());
        loadPermitList($(this).val());
        loadPILList($(this).val());

        $("button.permit").hide();
        $("div.permit").hide();
        //aaaa
    });

    $("#option-project-type").on('change', function() {
        if($(this).val() == "Ground Mount")
            $(".GroundMount-Option").css("display", "block");
        else{
            for(let i = 1; i <= 10; i ++){
                if($(`#trussFlagOption-${i}-4`)[0].checked){
                    $(`#trussFlagOption-${i}-1`)[0].checked = true;
                    fcChangeType(i, 0);
                }
            }
            $(".GroundMount-Option").css("display", "none");
        }
    });
    
    $('#option-module-type').on('change', function() {
        updatePVSubmoduleField($(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++){
            drawStickGraph(i);
            drawTrussGraph(i);
        }
    });
    $('#option-module-subtype').on('change', function() {
        updatePVSubmoduleField( $('#option-module-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++){
            drawStickGraph(i);
            drawTrussGraph(i);
        }
    });
    $('#option-inverter-type').on('change', function() {
        updatePVInvertorSubField($(this).children("option:selected").val());
    });
    $('#option-inverter2-type').on('change', function() {
        updatePVInvertorSubField($(this).children("option:selected").val(), "", 2);
    });
    $('#option-inverter3-type').on('change', function() {
        updatePVInvertorSubField($(this).children("option:selected").val(), "", 3);
    });
    $('#option-inverter-subtype').on('change', function() {
        updatePVInvertorSubField( $('#option-inverter-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
    });
    $('#option-inverter2-subtype').on('change', function() {
        updatePVInvertorSubField( $('#option-inverter2-type').children("option:selected").val(), 
                                $(this).children("option:selected").val(), 2);
    });
    $('#option-inverter3-subtype').on('change', function() {
        updatePVInvertorSubField( $('#option-inverter3-type').children("option:selected").val(), 
                                $(this).children("option:selected").val(), 3);
    });
    $('#option-stanchion-type').on('change', function() {
        updateStanchionSubField($(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++){
            if($(`#j-4-${i}`).val() != "1") {
                $(`#j-1-${i}`).val($(this).children("option:selected").val());
                updateFCStanchionSubField($(this).children("option:selected").val(), i - 1);
            }
        }
    });
    $('#option-stanchion-subtype').on('change', function() {
        updateStanchionSubField( $('#option-stanchion-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++){
            if($(`#j-4-${i}`).val() != "1")
                $(`#j-2-${i}`).val($(this).children("option:selected").val());
        }
    });
    $('#option-railsupport-type').on('change', function() {
        updateRailSupportSubField($(this).children("option:selected").val());
    });
    $('#option-railsupport-subtype').on('change', function() {
        updateRailSupportSubField( $('#option-railsupport-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
    });
    $('#option-number-of-conditions').on('change', function() {
        updateNumberOfConditions(parseInt($(this).children("option:selected").val()));
    });
    $("input:text").focus(function() {
        $(this).css('background-color', 'transparent');
    });
    $("#date-of-field-visit").on('change', function() {
        if ($('#date-of-plan-set').val() == "") {
            $('#date-of-plan-set').val($('#date-of-field-visit').val());
        }
        $(this).css('background-color', 'transparent');
    });
    $("#date-of-plan-set").on('change', function() {
        if ($('#date-of-field-visit').val() == "") {
            $('#date-of-field-visit').val($('#date-of-plan-set').val());
        }
        $(this).css('background-color', 'transparent');
    });
    $('.StrLength, .WireLength').keyup(function(){
        var val = $(this).val();
        var regex = /\d*\.?\d?/g;
        $(this).val(regex.exec(val)); 
    });

    // $(".permit").on('change', function(obj) {
    //     var classes = $(obj.target).attr('class').split(' ');
    //     var className = "." + classes[1];
    //     console.log(className);
    //     $(className).each(function() {
    //         if(this == obj.target || $(this).val() != $(obj.target).val()){
    //             $(this).val($(obj.target).val());
    //             updatePermitPDF($(this).attr('data-fileid'), $(this).attr('data-filename'));
    //         }
    //     });
    //     // updateUccF100(1, "ucc_f100_cpa.pdf");
    //     // updateUccF110(2, "ucc_f110_bldg.pdf");
    //     // updateUccF120(3, "ucc_f120_elec.pdf");
    //     // updateUcc3(4, "PA ucc-3.pdf");
    //     // updateUccF140(5, "ucc_f140_fire.pdf");
    //     //updatePermitPDF($(this).attr('data-fileid'), $(this).attr('data-filename'));
    // });

    var i = 0;
    for(i = 1; i <= 10; i ++)
    {
        $(`#option-roof-slope-${i}`).on('change', function() {
            updateRoofSlopeAnotherField(window.conditionId);
        });
        $(`#txt-roof-degree-${i}`).change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            if (parseFloat($(this).val()).toFixed(2) == 0.00) {
                $(`#warning-roof-degree-${i}`).css("display", "block");
            } else {
                $(`#warning-roof-degree-${i}`).css("display", "none");
            }
            updateRoofSlopeAnotherField(window.conditionId);
        });
        $(`#option-number-segments1-${i}`).on('change', function() {
            updateNumberSegment1(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#txt-length-of-roof-plane-${i}, #txt-roof-segment1-length-${i}, #txt-roof-segment2-length-${i}, #txt-roof-segment3-length-${i}, #txt-roof-segment4-length-${i}, #txt-roof-segment5-length-${i}, #txt-roof-segment6-length-${i}`)
        .change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            updateNumberSegment1(window.conditionId, $(`#option-number-segments1-${window.conditionId}`).children("option:selected").val());
        });
        $(`#option-number-segments2-${i}`).on('change', function() {
            updateNumberSegment2(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#txt-length-of-floor-plane-${i}, #txt-floor-segment1-length-${i}, #txt-floor-segment2-length-${i}, #txt-floor-segment3-length-${i}, #txt-floor-segment4-length-${i}, #txt-floor-segment5-length-${i}, #txt-floor-segment6-length-${i}`)
        .change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            updateNumberSegment2(window.conditionId, $(`#option-number-segments2-${window.conditionId}`).children("option:selected").val());
        });
        $(`#option-roof-member-type-${i}`).on('change', function() {
            updateRoofMemberType(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#option-floor-member-type-${i}`).on('change', function() {
            updateFloorMemberType(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#truss-axis-${i}`).on('change', function() {
            show_axis[window.conditionId] = !show_axis[window.conditionId];
            drawTrussGraph(window.conditionId);
        });
        $(`#stick-axis-${i}`).on('change', function() {
            stick_show_axis[window.conditionId] = !stick_show_axis[window.conditionId];
            drawStickGraph(window.conditionId);
        });
        $(`#a-6-${i}, #g-1-${i}, #g-2-${i}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#f-1-${i}, #a-11-${i}, #e-1-${i}, #e-2-${i}`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#c-2-${i}, #c-4-${i}`).on('change', function() {
            drawStickGraph(window.conditionId);
        });
        $(`#a-7-${i}`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'angle'); return; }
            roofInputMode(window.conditionId, 'angle');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#a-8-${i}`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'diagnol'); return; }
            roofInputMode(window.conditionId, 'diagnol');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#a-9-${i}`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'height'); return; }
            roofInputMode(window.conditionId, 'height');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#a-10-${i}`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'length'); return; }
            roofInputMode(window.conditionId, 'length');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#h-1-${i}, #h-2-${i}, #h-3-${i}, #h-4-${i}, #h-5-${i}, #h-6-${i}, #h-7-${i}, #h-8-${i}, #h-9-${i}, #h-10-${i}, #h-11-${i}, #h-12-${i}`)
        .click( function() {
            drawStickGraph(window.conditionId);
            drawTrussGraph(window.conditionId);
        });
        $(`#diag-1-1-${i}, #diag-1-2-${i}, #diag-1-3-${i}, #diag-1-4-${i}, #diag-1-5-${i}, #diag-1-6-${i}, #diag-2-1-${i}, #diag-2-2-${i}, #diag-2-3-${i}, #diag-2-4-${i}, #diag-2-5-${i}, #diag-2-6-${i}, #diag-2-reverse-1-${i}, #diag-2-reverse-2-${i}, #diag-2-reverse-3-${i}, #diag-2-reverse-4-${i}, #diag-2-reverse-5-${i}, #diag-2-reverse-6-${i}`)
        .click( function(){
            drawTrussGraph(window.conditionId);
        });
        $(`#af-2-${i}, #ai-2-${i}, #af-3-${i}, #ai-3-${i}, #af-4-${i}, #ai-4-${i}, #af-8-${i}, #ai-8-${i}, #af-9-${i}, #ai-9-${i}, #af-10-${i}, #ai-10-${i}, #af-11-${i}, #ai-11-${i}, #cf-2-${i}, #ci-2-${i}, #cf-4-${i}, #ci-4-${i}, #ai-4-${i}, #ef-1-${i}, #ei-1-${i}, #ef-2-${i}, #ei-2-${i}`).on('change', function() {
            calcDecimalFeet($(this).attr('id'));
        });
        $(`#txt-length-of-roof-plane-f-${i}, #txt-length-of-roof-plane-i-${i}, #txt-roof-segment1-length-f-${i}, #txt-roof-segment1-length-i-${i}, #txt-roof-segment2-length-f-${i}, #txt-roof-segment2-length-i-${i}, #txt-roof-segment3-length-f-${i}, #txt-roof-segment3-length-i-${i}, #txt-roof-segment4-length-f-${i}, #txt-roof-segment4-length-i-${i}, #txt-roof-segment5-length-f-${i}, #txt-roof-segment5-length-i-${i}, #txt-roof-segment6-length-f-${i}, #txt-roof-segment6-length-i-${i},
           #txt-length-of-floor-plane-f-${i}, #txt-length-of-floor-plane-i-${i}, #txt-floor-segment1-length-f-${i}, #txt-floor-segment1-length-i-${i}, #txt-floor-segment2-length-f-${i}, #txt-floor-segment2-length-i-${i}, #txt-floor-segment3-length-f-${i}, #txt-floor-segment3-length-i-${i}, #txt-floor-segment4-length-f-${i}, #txt-floor-segment4-length-i-${i}, #txt-floor-segment5-length-f-${i}, #txt-floor-segment5-length-i-${i}, #txt-floor-segment6-length-f-${i}, #txt-floor-segment6-length-i-${i}`).on('change', function() {
            calcTrussDecimalFeet($(this).attr('id'));
        });
        $(`#duplicate-${i}`).click(function() {
            duplicateTab(window.conditionId);
        });
        $(`#delete-${i}`).click(function() {
            deleteTab(window.conditionId);
        });
        $(`#j-1-${i}`).on('change', function() {
            updateFCStanchionSubField($(this).children("option:selected").val(), window.conditionId - 1);
            $(`#j-4-${window.conditionId}`).val("1");
        });
        $(`#j-2-${i}`).on('change', function() {
            $(`#j-4-${window.conditionId}`).val("1");
        });
    }

    $(`#upload`).on("dragover", function(){
        $("#drop").css("background-color", "#1E3134");
    });
    $(`#upload`).on("dragleave dragenter drop", function(){
        $("#drop").css("background-color", "#2E3134");
    });
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });
    $('#upload').fileupload({
    dropZone: $('#drop'),
    add: function (e, data) {
        var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
            ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');
        tpl.find('p').text(data.files[0].name)
                    .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
        data.context = tpl.appendTo($('#upload ul'));

        tpl.find('input').knob();

        tpl.find('span').click(function(){

            if(tpl.hasClass('working')){
                jqXHR.abort();
            }

            tpl.fadeOut(function(){
                tpl.remove();
            });

        });
        $("#uploadStatus").html("Uploading...");
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        window.totalCount ++;
        if(window.finishedCount == 0)
            $("#progressBar-value").css("width", "0%");

        var jqXHR = data.submit();
    },
    progress: function(e, data){
        var progress = parseInt(data.loaded / data.total * 100, 10);

        data.context.find('input').val(progress).change();

        // if(progress == 100){
        //     data.context.removeClass('working');
        // }
    },

    fail:function(e, data){
        data.context.addClass('error');
        setTotalProgress(false);
    },

    done:function(e, data){
        if(data.result && data.result.success){
            data.context.removeClass('working');
            data.context.addClass('success');
            window.uploadList.push({filename: data.result.filename, path: data.result.path});
            setTotalProgress(true);
        }
        else{
            data.context.addClass('error');
            console.log('upload failed', data);
            setTotalProgress(false);
        }
    }

    });
    $('#drop a').click(function(){
        $(this).parent().find('input').click();
    });

    var formatFileSize = function(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

    var jobFilePush = function(uploadData){
        return new Promise((resolve, reject) => {
            $.ajax({
                url:"jobFilePush",
                type:'post',
                data: uploadData,
                success:function(res){
                    if(res.success){
                        $("#filetree").jstree('create_node', '#IN', {"text": res.filename, "id": res.id, "type": "infile", "path": res.path}, 'last');
                        resolve(true);
                    } else {
                        console.log(res);
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    res = JSON.parse(xhr.responseText);
                    message = res.message;
                    swal.fire({ title: "Error",
                            text: message == "" ? "Error happened while processing. Please try again later." : message,
                            icon: "error",
                            confirmButtonText: `OK` });
                    resolve(false);
                }
            });
        });
    }

    var setTotalProgress = async function(status){
        window.finishedCount ++;
        // if(status == false)
        //     window.uploadError = true;
        if(window.finishedCount >= window.totalCount){
            for(let i = 0; i < window.uploadList.length; i ++){
                let uploadResult = await jobFilePush(window.uploadList[i]);
                if(!uploadResult){
                    window.uploadError = true;
                }
                let progress = ((i + 1) * 100 / window.uploadList.length).toFixed(2);
                $("#progressBar-value").css("width", progress + "%");
                $("#uploadPercent").html(progress + "%");
            }

            if(window.uploadError)
                swal.fire({ title: "Warning", text: "Upload Failed.", icon: "warning", confirmButtonText: `OK` });
            else
                swal.fire({ title: "Success", text: "Upload Done.", icon: "success", confirmButtonText: `OK` });

            window.finishedCount = 0;
            window.uploadList = [];
            window.totalCount = 0;
            window.uploadError = false;
            $("#uploadStatus").html("Done");
        }
    }

    $("#filetree").jstree({
        'plugins': ["wholerow", "checkbox", "types"],
        'core': {
            "check_callback": true,
            "themes" : {
                "responsive": true
            },
        },
        "types" : {
            "folder" : { "icon" : "fa fa-folder m--font-warning" },
            "infile" : {"icon" : "fa fa-file m--font-warning" },
            "outfile" : {"icon" : "fa fa-file m--font-warning" }
        },  
    });

    $("#filetree").jstree('create_node', null, {"text":"Root", "id": "root", "type": "folder", "state": {"opened": true} }, 'last');
    $("#filetree").jstree('create_node', '#root', {"text":"IN", "id": "IN", "type": "folder"}, 'last');
    $("#filetree").jstree('create_node', '#root', {"text":"OUT", "id": "OUT", "type": "folder"}, 'last');

    $('#filetree').on('select_node.jstree', function () {
        var nodeIds = $("#filetree").jstree("get_checked", null, true);
        var inCount = 0;
        if(nodeIds.length > 0){
            nodeIds.forEach(nodeId => {
                var node = $('#filetree').jstree(true).get_node(nodeId);
                if(node.type == "infile")
                    inCount ++;
            });
        }
        if(inCount > 0) $("#deleteBtn").prop("disabled", false); else $("#deleteBtn").prop("disabled", true);
        if(inCount == 1) $("#renameBtn").prop("disabled", false); else $("#renameBtn").prop("disabled", true);
    });

    $('#filetree').on('deselect_node.jstree', function () {
        var nodeIds = $("#filetree").jstree("get_checked", null, true);
        var inCount = 0;
        if(nodeIds.length > 0){
            nodeIds.forEach(nodeId => {
                var node = $('#filetree').jstree(true).get_node(nodeId);
                if(node.type == "infile")
                    inCount ++;
            });
        }
        if(inCount > 0) $("#deleteBtn").prop("disabled", false); else $("#deleteBtn").prop("disabled", true);
        if(inCount == 1) $("#renameBtn").prop("disabled", false); else $("#renameBtn").prop("disabled", true);
    });

    var loadFileList = function() {
        var projectId = $('#projectId').val();
        if(projectId >= 0){
            $.ajax({
                url:"getFileList",
                type:'post',
                data:{projectId: projectId},
                success:function(res){
                    if(res.success && res.data){
                        if(res.directory){
                            $("#filetree").jstree('rename_node', '#root', 'Root(' + res.directory + ')');
                        }
                        if(res.data["IN"] && res.data["IN"].childs){
                            res.data["IN"].childs.forEach(child => {
                                addFileNode("IN", child, true);
                            });
                        }
                        if(res.data["OUT"] && res.data["OUT"].childs){
                            res.data["OUT"].childs.forEach(child => {
                                addFileNode("OUT", child, false);
                            });
                        }
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

    var addFileNode = function(parentId, nodeData, isIN){
        if(nodeData.type == "folder"){
            $("#filetree").jstree('create_node', '#' + parentId, {"text": nodeData.name, "id": nodeData.id, "type": "folder"}, 'last');
            if(nodeData.childs && nodeData.childs.length > 0)
                nodeData.childs.forEach(child => {
                    addFileNode(nodeData.id, child, isIN);
                });
        } else {
            $("#filetree").jstree('create_node', '#' + parentId, {"text": nodeData.name, "id": nodeData.id, "type": isIN ? "infile" : "outfile", "path": nodeData.path}, 'last');
        }
    }

    // Framing condition related function
    // (function(){

    //     var oldMP = new Array(11); oldMP.fill(1);

    //     $(`#txt-framing-conditions`).on('change', function() {
    //         updateTotalMPCount(window.conditionId, parseInt($(this).val()));
    //     });

    //     $(`#a-1-1`).focus(function () {
    //         oldMP[window.conditionId] = this.value;
    //     }).change(function() {
    //         changeCurrentMP(oldMP[window.conditionId], this.value);
    //         oldMP[window.conditionId] = this.value;
    //     });
    // })();
    //}

    var calcDecimalFeet = function(activeId) {
        if(activeId.includes('i-')){ // active input is inch
            var feetId = activeId.replace('i-', 'f-');
            var decimalFeetId = activeId.replace('i', '');
            var decimalValue = parseFloat($(`#${feetId}`).val() == "" ? 0 : $(`#${feetId}`).val()) + parseFloat($(`#${activeId}`).val() == "" ? 0 : $(`#${activeId}`).val()) / 12;
            $(`#${decimalFeetId}`).val(decimalValue.toFixed(2));
            $(`#${decimalFeetId}`).trigger('change');
        }
        else if(activeId.includes('f-')){ // active input is feet
            var inchId = activeId.replace('f-', 'i-');
            var decimalFeetId = activeId.replace('f', '');
            var decimalValue = parseFloat($(`#${activeId}`).val() == "" ? 0 : $(`#${activeId}`).val()) + parseFloat($(`#${inchId}`).val() == "" ? 0 : $(`#${inchId}`).val()) / 12;
            $(`#${decimalFeetId}`).val(decimalValue.toFixed(2));
            $(`#${decimalFeetId}`).trigger('change');
        }
    }

    var calcTrussDecimalFeet = function(activeId) {
        if(activeId.includes('-i-')){ // active input is inch
            var feetId = activeId.replace('-i-', '-f-');
            var decimalFeetId = activeId.replace('-i-', '-');
            var decimalValue = parseFloat($(`#${feetId}`).val() == "" ? 0 : $(`#${feetId}`).val()) + parseFloat($(`#${activeId}`).val() == "" ? 0 : $(`#${activeId}`).val()) / 12;
            $(`#${decimalFeetId}`).val(decimalValue.toFixed(2));
            $(`#${decimalFeetId}`).trigger('change');
        }
        else if(activeId.includes('-f-')){ // active input is feet
            var inchId = activeId.replace('-f-', '-i-');
            var decimalFeetId = activeId.replace('-f-', '-');
            var decimalValue = parseFloat($(`#${activeId}`).val() == "" ? 0 : $(`#${activeId}`).val()) + parseFloat($(`#${inchId}`).val() == "" ? 0 : $(`#${inchId}`).val()) / 12;
            $(`#${decimalFeetId}`).val(decimalValue.toFixed(2));
            $(`#${decimalFeetId}`).trigger('change');
        }
    }

    var checkWarnings = function() {
        var hasWarnings = false;
        var caseCount = $("#option-number-of-conditions").val();
        for(let i = 1; i <= caseCount; i ++){
            if($(`#trussFlagOption-${i}-2`)[0].checked){ // Truss 
                if($(`#td-checksum-of-segment1-${i}`).html() != "OK")
                    hasWarnings = true;
                if($(`#td-checksum-of-segment2-${i}`).html() != "OK")
                    hasWarnings = true;
                if($(`#truss-module-alert-${i}`)[0].style.display == "block")
                    hasWarnings = true;
            } else { // Stick
                if($(`#c-2-warn-${i}`)[0].style.display == "block")
                    hasWarnings = true;
                if($(`#c-4-warn-${i}`)[0].style.display == "block")
                    hasWarnings = true;
                if($(`#stick-module-alert-${i}`)[0].style.display == "block")
                    hasWarnings = true;
            }

            if($(`#trussFlagOption-${i}-2`)[0].checked){
                var roofDegreeVal = parseFloat($(`#txt-roof-degree-${i}`).val());
                if(roofDegreeVal.toFixed(2) == 0.00) {
                    hasWarnings = true;
                    $(`#warning-roof-degree-${i}`).css("display", "block");
                } else {
                    $(`#warning-roof-degree-${i}`).css("display", "none");
                }
            }

            if(!$(`#trussFlagOption-${i}-2`)[0].checked){
                var stickRoofDegreeVal = parseFloat($(`#a-7-${i}`).val());
                if(stickRoofDegreeVal.toFixed(2) == 0.00) {
                    hasWarnings = true;
                    $(`#warning-stick-roof-degree-${i}`).css("display", "block");
                } else {
                    $(`#warning-stick-roof-degree-${i}`).css("display", "none");
                }
            }
        }
        return hasWarnings;
    }

    var ignoreDuplicateNum = function(){
        return new Promise((resolve, reject) => {
            var numComment = $("#project-id-comment").html();
            if(numComment.includes("duplicated.")){
                swal.fire({
                    title: "Warning",
                    html: "This project number has already been used.  Please verify that you intend to use project number " + $("#txt-project-number").val() +".<br/>Hit YES to save the project, NO to go back and revise the project number.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    cancelButtonText: `No`,
                })
                .then(( result ) => {
                    if ( result.value )
                        resolve(true);
                    else
                        resolve(false);
                });
            } else
                resolve(true);
        })
    }

    var submitData = async function(e, status) {
        e.preventDefault();
        e.stopPropagation(); 

        if (isEmptyInputBox() == true) { 
            $("#submitBtns input").attr('disabled', false);
            swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
            return; 
        }

        // if(await ignoreDuplicateNum() == false){
        //     return;
        // }
        await setProjectIdComment();
        var numComment = $("#project-id-comment").html();
        if(numComment.includes("duplicated.")){
            $("#submitBtns input").attr('disabled', false);
            swal.fire({ title: "Warning", text: "This project number has already been used. Please use another one.", icon: "warning", confirmButtonText: `OK` });
            return; 
        } else if(numComment.includes("more than")){
            $("#submitBtns input").attr('disabled', false);
            swal.fire({ title: "Warning", text: "This project number is too high. Please use another one.", icon: "warning", confirmButtonText: `OK` });
            return; 
        }

        var caseCount = $("#option-number-of-conditions").val();
        var data = getData(caseCount);
        var message = '';

        if(status != 'Saved'){
            for(let i =1; i <= caseCount; i ++){
                if($(`#trussFlagOption-${i}-2`)[0].checked == false){ // Stick or IBC 5% or GroundMount
                    if(stick_right_input[i] == ''){
                        $("#submitBtns input").attr('disabled', false);
                        swal.fire({ title: "Warning", text: "Please fill A7~A10 fields on Tab " + i + ".", icon: "warning", confirmButtonText: `OK` });
                        return;
                    }
                    if(parseFloat($(`#ac-7-${i}`).val()) == 0 || parseFloat($(`#ac-8-${i}`).val()) == 0 || parseFloat($(`#ac-9-${i}`).val()) == 0 || parseFloat($(`#ac-10-${i}`).val()) == 0){
                        $("#submitBtns input").attr('disabled', false);
                        swal.fire({ title: "Warning", text: "Please fix 0 values of A7~A10 fields on Tab " + i + ".", icon: "warning", confirmButtonText: `OK` });
                        return;
                    }
                }
            }
        }

        if($("#bus-bar-rating").length > 0){
            if(parseFloat($("#main-breaker-rating").val()) > parseFloat($("#bus-bar-rating").val())) {
                $("#submitBtns input").attr('disabled', false);
                swal.fire({ title: "Warning", text: "Main Breaker Rating cannot be larger than Bus Bar Rating.", icon: "warning", confirmButtonText: `OK` });
                return;
            }
            if(parseFloat($("#downgraded-breaker-rating").val()) > parseFloat($("#bus-bar-rating").val())) {
                $("#submitBtns input").attr('disabled', false);
                swal.fire({ title: "Warning", text: "Downgraded Breaker Rating cannot be larger than Bus Bar Rating.", icon: "warning", confirmButtonText: `OK` });
                return;
            }
        }

        //for(let i = 0; i < parseInt(caseCount); i ++)
        //{
            // call ajax
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.ajax({
            url:"submitInput",
            type:'post',
            data:{data: data, status: status, caseCount: caseCount},
            success:function(res){
                swal.close();
                $("#submitBtns input").attr('disabled', false);
                if (res.status == true) {
                    $("#projectId").val(res.projectId);
                    $("#uploadJobId").val(res.projectId);
                    if(res.directory)
                        $("#filetree").jstree('rename_node', '#root', 'Root(' + res.directory + ')');
                    message = 'Succeeded to send input data. Do you want back to home page?';
                    if (status == 'Data Check') {
                        message = 'Input Data sent for review. An email will be sent to you summarizing the data input and notifying you of any problems.\nGo back to home page?';
                    }
                    swal.fire({
                        title: "Success",
                        text: message,
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonText: `Yes`,
                        cancelButtonText: `No`,
                    })
                    .then(( result ) => {
                        if ( result.value ) {
                            window.location = "home";
                        } 
                    });
                } else {
                    // error handling
                    swal.fire({ title: "Error",
                        text: res.message,
                        icon: "error",
                        confirmButtonText: `OK` });
                }
            },
            error: function(xhr, status, error) {
                swal.close();
                $("#submitBtns input").attr('disabled', false);
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
            }
        });
        
        //}
    }

    $('#rs-save').click(function(e) {
        $("#submitBtns input").attr('disabled', true);
        if( !checkWarnings() ){
            $('#projectState').val("1");
            submitData(e, 'Saved');
        } else{
            $("#submitBtns input").attr('disabled', false);
            swal.fire({ title: "Error",
                    text: "Warning - Please fix the warnings before submit.",
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
    $('#rs-datacheck').click(function(e){
        $("#submitBtns input").attr('disabled', true);
        $('#projectState').val("2");
        submitData(e, 'Data Check');
    });
    $("#rs-submit").click(function(e){
        $("#submitBtns input").attr('disabled', true);
        if (domChanged == false) {
            $("#submitBtns input").attr('disabled', false);
            swal.fire({ title: "Warning", text: "No changes on this project data except drawings. So you don't need to submit.", icon: "warning", confirmButtonText: `OK` });
            return;
        }

        var projectState = $('#projectState').val();
        if(projectState < 3){
            swal.fire({
                title: "Warning",
                html: "Warning - You should really check the data before submitting it for a final report.<br /> Continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Yes`,
                cancelButtonText: `No`,
            })
            .then(( result ) => {
                if ( result.value ) {
                    $('#projectState').val("4");
                    submitData(e, 'Submitted');
                } else
                    $("#submitBtns input").attr('disabled', false);
            });
        }
        else{
            swal.fire({
                title: "Warning",
                html: "Have you changed any iRoof data that will require recalculation of this job?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Yes`,
                cancelButtonText: `No`,
            })
            .then(( result ) => {
                if ( result.value ) {
                    $('#projectState').val("4");
                    submitData(e, 'Submitted');
                } else
                    $("#submitBtns input").attr('disabled', false);
            });
            //submitData(e, 'Submitted');
        }
    });
    $('#rs-initialize').click(function(e){
        $("#submitBtns input").attr('disabled', true);
        swal.fire({
            title: "Warning",
            html: "Warning - Your project state will be initialized.<br /> Continue?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: `Yes`,
            cancelButtonText: `No`,
        })
        .then(( result ) => {
            if ( result.value ) {
                $.ajax({
                    url:"setProjectState",
                    type:'post',
                    data:{projectId: $('#projectId').val(), state: 1},
                    success:function(res){
                        if (res.success == true) {
                            $("#rs-save").removeClass('disabled');
                            $("#rs-datacheck").removeClass('disabled');
                            $("#rs-initialize").addClass('disabled');
                            $('#projectState').val("1");
                        }
                        $("#submitBtns input").attr('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        res = JSON.parse(xhr.responseText);
                        message = res.message;
                        swal.fire({ title: "Error",
                                text: message == "" ? "Error happened while processing. Please try again later." : message,
                                icon: "error",
                                confirmButtonText: `OK` });
                        $("#submitBtns input").attr('disabled', false);
                    }
                });
            }  else
                $("#submitBtns input").attr('disabled', false);
        });
    });

    function GetCPU () {
        var parser = new UAParser();
        var result = parser.getResult();
        // this will also produce the same result (without instantiation):
        // var result = UAParser(uastring);

        console.log(result.browser);        // {name: "Chromium", version: "15.0.874.106"}
        console.log(result.device);         // {model: undefined, type: undefined, vendor: undefined}
        console.log(result.os);             // {name: "Ubuntu", version: "11.10"}
        console.log(result.os.version);     // "11.10"
        console.log(result.engine.name);    // "WebKit"
        console.log(result.cpu.architecture);   // "amd64"        
    }

    // Set the correct calc method of A-7 to A-10
    function roofInputMode(condId, changed) {
        if( stick_input_changed[condId].indexOf(changed) == -1 )
            stick_input_changed[condId].push(changed);
        if( stick_input_changed[condId].length >= 3 )
            stick_input_changed[condId].splice(0, 1);
        if( stick_input_changed[condId].length >= 2 )
        {
            if(stick_input_changed[condId].indexOf('angle') > -1 && stick_input_changed[condId].indexOf('diagnol') > -1)
                stick_right_input[condId] = 'diagnol';
            else if(stick_input_changed[condId].indexOf('angle') > -1 && stick_input_changed[condId].indexOf('height') > -1)
                stick_right_input[condId] = 'height';
            else if(stick_input_changed[condId].indexOf('angle') > -1 && stick_input_changed[condId].indexOf('length') > -1)
                stick_right_input[condId] = 'length';
            else if(stick_input_changed[condId].indexOf('diagnol') > -1 && stick_input_changed[condId].indexOf('height') > -1)
                stick_right_input[condId] = 'diagnolheight';
            else if(stick_input_changed[condId].indexOf('diagnol') > -1 && stick_input_changed[condId].indexOf('length') > -1)
                stick_right_input[condId] = 'diagnollength';
            else if(stick_input_changed[condId].indexOf('height') > -1 && stick_input_changed[condId].indexOf('length') > -1)
                stick_right_input[condId] = 'heightlength';
        }
        $(`#calc-algorithm-${condId}`).val(stick_right_input[condId]);
        console.log(stick_right_input[condId]);
    }

    // Calculate correct values
    var checkRoofInput = function(condId) {
        var angleRadian = degreeToRadian($(`#a-7-${condId}`).val());
        if(stick_right_input[condId] == 'height' && $(`#a-9-${condId}`).val() != "")
        {
            var height = parseFloat($(`#a-9-${condId}`).val());
            var rightDiagnol = (height / Math.sin(angleRadian)).toFixed(2);
            var rightLength = (height / Math.tan(angleRadian)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#ffc'); $(`#calced-7-${condId}`)[0].innerHTML = "";
            $(`#valuef-8-${condId}`).css('background', '#add8e6'); $(`#valuei-8-${condId}`).css('background', '#add8e6'); //$(`#calced-8-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-9-${condId}`).css('background', '#ffc'); $(`#valuei-9-${condId}`).css('background', '#ffc'); //$(`#calced-9-${condId}`)[0].innerHTML = "";
            $(`#valuef-10-${condId}`).css('background', '#add8e6'); $(`#valuei-10-${condId}`).css('background', '#add8e6'); //$(`#calced-10-${condId}`)[0].innerHTML = "calculated value";
            
            $(`#af-8-${condId}`).val(""); $(`#ai-8-${condId}`).val(""); $(`#a-8-${condId}`).val(rightDiagnol);
            $(`#af-10-${condId}`).val(""); $(`#ai-10-${condId}`).val(""); $(`#a-10-${condId}`).val(rightLength);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
        else if(stick_right_input[condId] == 'length' && $(`#a-10-${condId}`).val() != "")
        {
            var length = parseFloat($(`#a-10-${condId}`).val());
            var rightDiagnol = (length / Math.cos(angleRadian)).toFixed(2);
            var rightHeight = (length * Math.tan(angleRadian)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#ffc'); //$(`#calced-7-${condId}`)[0].innerHTML = "";
            $(`#valuef-8-${condId}`).css('background', '#add8e6'); $(`#valuei-8-${condId}`).css('background', '#add8e6'); //$(`#calced-8-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-9-${condId}`).css('background', '#add8e6'); $(`#valuei-9-${condId}`).css('background', '#add8e6'); //$(`#calced-9-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-10-${condId}`).css('background', '#ffc'); $(`#valuei-10-${condId}`).css('background', '#ffc'); //$(`#calced-10-${condId}`)[0].innerHTML = "";
            
            $(`#af-8-${condId}`).val(""); $(`#ai-8-${condId}`).val(""); $(`#a-8-${condId}`).val(rightDiagnol);
            $(`#af-9-${condId}`).val(""); $(`#ai-9-${condId}`).val(""); $(`#a-9-${condId}`).val(rightHeight);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
        else if(stick_right_input[condId] == 'diagnol' && $(`#a-8-${condId}`).val() != "")
        {
            var diagnol = parseFloat($(`#a-8-${condId}`).val());
            var rightHeight = (diagnol * Math.sin(angleRadian)).toFixed(2);
            var rightWidth = (diagnol * Math.cos(angleRadian)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#ffc'); //$(`#calced-7-${condId}`)[0].innerHTML = "";
            $(`#valuef-8-${condId}`).css('background', '#ffc'); $(`#valuei-8-${condId}`).css('background', '#ffc'); //$(`#calced-8-${condId}`)[0].innerHTML = "";
            $(`#valuef-9-${condId}`).css('background', '#add8e6'); $(`#valuei-9-${condId}`).css('background', '#add8e6'); //$(`#calced-9-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-10-${condId}`).css('background', '#add8e6'); $(`#valuei-10-${condId}`).css('background', '#add8e6'); //$(`#calced-10-${condId}`)[0].innerHTML = "calculated value";

            $(`#af-9-${condId}`).val(""); $(`#ai-9-${condId}`).val(""); $(`#a-9-${condId}`).val(rightHeight);
            $(`#af-10-${condId}`).val(""); $(`#ai-10-${condId}`).val(""); $(`#a-10-${condId}`).val(rightWidth);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
        else if(stick_right_input[condId] == 'diagnolheight' && $(`#a-8-${condId}`).val() != "" && $(`#a-9-${condId}`).val() != "")
        {
            var diagnol = parseFloat($(`#a-8-${condId}`).val());
            var height = parseFloat($(`#a-9-${condId}`).val());
            var rightAngle = Math.asin(height / diagnol);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightLength = (diagnol * Math.cos(rightAngle)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#add8e6'); //$(`#calced-7-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-8-${condId}`).css('background', '#ffc'); $(`#valuei-8-${condId}`).css('background', '#ffc'); //$(`#calced-8-${condId}`)[0].innerHTML = "";
            $(`#valuef-9-${condId}`).css('background', '#ffc'); $(`#valuei-9-${condId}`).css('background', '#ffc'); //$(`#calced-9-${condId}`)[0].innerHTML = "";
            $(`#valuef-10-${condId}`).css('background', '#add8e6'); $(`#valuei-10-${condId}`).css('background', '#add8e6'); //$(`#calced-10-${condId}`)[0].innerHTML = "calculated value";
            
            $(`#af-7-${condId}`).val(""); $(`#ai-7-${condId}`).val(""); $(`#a-7-${condId}`).val(rightAngleDegree);
            $(`#af-10-${condId}`).val(""); $(`#ai-10-${condId}`).val(""); $(`#a-10-${condId}`).val(rightLength);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
        else if(stick_right_input[condId] == 'diagnollength' && $(`#a-8-${condId}`).val() != "" && $(`#a-10-${condId}`).val() != "")
        {
            var diagnol = parseFloat($(`#a-8-${condId}`).val());
            var length = parseFloat($(`#a-10-${condId}`).val());
            var rightAngle = Math.acos(length / diagnol);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightHeight = (diagnol * Math.sin(rightAngle)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#add8e6'); //$(`#calced-7-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-8-${condId}`).css('background', '#ffc'); $(`#valuei-8-${condId}`).css('background', '#ffc'); //$(`#value-8-${condId}`).css('background', '#ffc'); $(`#calced-8-${condId}`)[0].innerHTML = "";
            $(`#valuef-9-${condId}`).css('background', '#add8e6'); $(`#valuei-9-${condId}`).css('background', '#add8e6'); //$(`#calced-9-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-10-${condId}`).css('background', '#ffc'); $(`#valuei-10-${condId}`).css('background', '#ffc'); //$(`#calced-10-${condId}`)[0].innerHTML = "";
            
            $(`#af-7-${condId}`).val(""); $(`#ai-7-${condId}`).val(""); $(`#a-7-${condId}`).val(rightAngleDegree);
            $(`#af-9-${condId}`).val(""); $(`#ai-9-${condId}`).val(""); $(`#a-9-${condId}`).val(rightHeight);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
        else if(stick_right_input[condId] == 'heightlength' && $(`#a-9-${condId}`).val() != "" && $(`#a-10-${condId}`).val() != "")
        {
            var height = parseFloat($(`#a-9-${condId}`).val());
            var length = parseFloat($(`#a-10-${condId}`).val());
            var rightAngle = Math.atan(height / length);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightDiagnol = (height / Math.sin(rightAngle)).toFixed(2);

            $(`#value-7-${condId}`).css('background', '#add8e6'); //$(`#calced-7-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-8-${condId}`).css('background', '#add8e6'); $(`#valuei-8-${condId}`).css('background', '#add8e6'); //$(`#calced-8-${condId}`)[0].innerHTML = "calculated value";
            $(`#valuef-9-${condId}`).css('background', '#ffc'); $(`#valuei-9-${condId}`).css('background', '#ffc'); //$(`#calced-9-${condId}`)[0].innerHTML = "";
            $(`#valuef-10-${condId}`).css('background', '#ffc'); $(`#valuei-10-${condId}`).css('background', '#ffc'); //$(`#calced-10-${condId}`)[0].innerHTML = "";

            $(`#af-7-${condId}`).val(""); $(`#ai-7-${condId}`).val(""); $(`#a-7-${condId}`).val(rightAngleDegree);
            $(`#af-8-${condId}`).val(""); $(`#ai-8-${condId}`).val(""); $(`#a-8-${condId}`).val(rightDiagnol);

            $(`#ac-7-${condId}`).val($(`#a-7-${condId}`).val());
            $(`#ac-8-${condId}`).val($(`#a-8-${condId}`).val());
            $(`#ac-9-${condId}`).val($(`#a-9-${condId}`).val());
            $(`#ac-10-${condId}`).val($(`#a-10-${condId}`).val());
        }
    }

var ignoreOverwrite = function(){
    return new Promise((resolve, reject) => {
        swal.fire({
            title: "Warning",
            text: "The contents on the target tab will be removed. Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: `Yes`,
            cancelButtonText: `No`,
        })
        .then(( result ) => {
            if ( result.value )
                resolve(true);
            else
                resolve(false);
        });
    })
}

var doDuplicate = async function (targetTabId, curTabId, checkOverwrite){
    if(checkOverwrite && await ignoreOverwrite() == false)
        return;
    $(`#trussFlagOption-${targetTabId}-1`).prop('checked', $(`#trussFlagOption-${curTabId}-1`)[0].checked);
    $(`#trussFlagOption-${targetTabId}-2`).prop('checked', $(`#trussFlagOption-${curTabId}-2`)[0].checked);
    $(`#trussFlagOption-${targetTabId}-3`).prop('checked', $(`#trussFlagOption-${curTabId}-3`)[0].checked);
    $(`#trussFlagOption-${targetTabId}-4`).prop('checked', $(`#trussFlagOption-${curTabId}-4`)[0].checked);
    
    var tabType;
    if($(`#trussFlagOption-${curTabId}-1`)[0].checked) tabType = 0;
    else if($(`#trussFlagOption-${curTabId}-2`)[0].checked) tabType = 1;
    else if($(`#trussFlagOption-${curTabId}-3`)[0].checked) tabType = 2;
    else if($(`#trussFlagOption-${curTabId}-4`)[0].checked) tabType = 3;
    fcChangeType(targetTabId, tabType);

    $(`#inputform-${curTabId} input:text:enabled, #inputform-${curTabId} select:enabled`).each(function() { 
        let elementId = $(this).attr('id');
        elementId = elementId.slice(0, elementId.length  - 2) + '-' + targetTabId;
        $(`#${elementId}`).val($(this).val());
    });
    $(`#inputform-${curTabId} input:checkbox:enabled`).each(function() { 
        let elementId = $(this).attr('id');
        elementId = elementId.slice(0, elementId.length  - 2) + '-' + targetTabId;
        $(`#${elementId}`).prop('checked', $(this)[0].checked);
    });
    if($(`#j-1-${curTabId}`).val() != ''){ // Update Stanchion in FC
        updateFCStanchionSubField($(`#j-1-${curTabId}`).val(), targetTabId - 1);
        $(`#j-2-${targetTabId}`).val($(`#j-2-${curTabId}`).val());
    }
    if(tabType != 2) // If Stick or Truss
        maxModuleNumChange(targetTabId);
    updateRoofMemberType(targetTabId, $(`#option-roof-member-type-${curTabId}`).val());
    updateNumberSegment1(targetTabId, $(`#option-number-segments1-${curTabId}`).val());
    updateFloorMemberType(targetTabId, $(`#option-floor-member-type-${curTabId}`).val());
    updateNumberSegment2(targetTabId, $(`#option-number-segments2-${curTabId}`).val());

    drawTrussGraph(targetTabId);
    drawStickGraph(targetTabId);
    stick_input_changed[targetTabId] = stick_input_changed[curTabId];
    stick_right_input[targetTabId] = stick_right_input[curTabId];
    checkRoofInput(targetTabId);
    var i, tabcontent, tablinks, tabName = 'fc-' + targetTabId;
    tabcontent = document.getElementsByClassName("rfdTabContent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    document.getElementById('fcTab-' + targetTabId).className += " active";

    if( tabName == "tab_first" )
        document.getElementById('subPageTitle').innerHTML = 'Site and Equipment Data Input';
    else if( tabName == "tab_override" )
        document.getElementById('subPageTitle').innerHTML = 'Custom Program Data Overrides';
    else 
    {
        window.conditionId = parseInt(tabName.slice(3));
        document.getElementById('subPageTitle').innerHTML = 'Framing Data Input';
    }

    console.log('Tab No:', window.conditionId); 
}

var duplicateTab = function(curTabId) {
    var caseCount = parseInt($("#option-number-of-conditions").val());
    if(caseCount >= 10){
        swal.fire({ title: "Warning", text: "You are reached to FC cases limit.", icon: "warning", confirmButtonText: `OK` });
    } else {
        swal.fire({ title: "Input the target FC Tab Number", input: 'text', confirmButtonText: `OK`, showCancelButton: true }).then((result => {
            if(result && result.value && result.value != curTabId){
                if(parseInt(result.value) < 1 || parseInt(result.value) > 10)
                    swal.fire({ title: "Warning", text: "Please input correct target number(1~10).", icon: "warning", confirmButtonText: `OK` });
                else{
                    let targetTabId;
                    if(result.value > caseCount){
                        targetTabId = caseCount + 1;
                        $("#option-number-of-conditions").val(caseCount + 1);
                        updateNumberOfConditions(caseCount + 1);
                        doDuplicate(targetTabId, curTabId, false);
                    }
                    else{
                        targetTabId = parseInt(result.value);
                        doDuplicate(targetTabId, curTabId, true);
                    }
                }
            }
        }))
    }
}

var deleteTab = function(curTabId) {
    var caseCount = parseInt($("#option-number-of-conditions").val());
    if(caseCount == 1){
        swal.fire({ title: "Warning", text: "You are reached to minimum FC cases limit.", icon: "warning", confirmButtonText: `OK` });
    } else {
        swal.fire({title: "Warning", text: "Are you sure to delete this FC Tab?", icon: "warning", showCancelButton: true, confirmButtonText: `Yes`, cancelButtonText: `No`})
        .then(( result ) => {
            if ( result.value ){
                let targetTabId;
                for(targetTabId = curTabId; targetTabId < caseCount; targetTabId ++){
                    sourceTabId = targetTabId + 1;
                    $(`#trussFlagOption-${targetTabId}-1`).prop('checked', $(`#trussFlagOption-${sourceTabId}-1`)[0].checked);
                    $(`#trussFlagOption-${targetTabId}-2`).prop('checked', $(`#trussFlagOption-${sourceTabId}-2`)[0].checked);
                    $(`#trussFlagOption-${targetTabId}-3`).prop('checked', $(`#trussFlagOption-${sourceTabId}-3`)[0].checked);
                    $(`#trussFlagOption-${targetTabId}-4`).prop('checked', $(`#trussFlagOption-${sourceTabId}-4`)[0].checked);

                    var tabType;
                    if($(`#trussFlagOption-${sourceTabId}-1`)[0].checked) tabType = 0;
                    else if($(`#trussFlagOption-${sourceTabId}-2`)[0].checked) tabType = 1;
                    else if($(`#trussFlagOption-${sourceTabId}-3`)[0].checked) tabType = 2;
                    else if($(`#trussFlagOption-${sourceTabId}-4`)[0].checked) tabType = 3;
                    fcChangeType(targetTabId, tabType);
                    
                    $(`#inputform-${sourceTabId} input:text:enabled, #inputform-${sourceTabId} select:enabled`).each(function() { 
                        let elementId = $(this).attr('id');
                        elementId = elementId.slice(0, elementId.length  - 2) + '-' + targetTabId;
                        $(`#${elementId}`).val($(this).val());
                    });
                    $(`#inputform-${sourceTabId} input:checkbox:enabled`).each(function() { 
                        let elementId = $(this).attr('id');
                        elementId = elementId.slice(0, elementId.length  - 2) + '-' + targetTabId;
                        $(`#${elementId}`).prop('checked', $(this)[0].checked);
                    });
                    if(tabType != 2) // If Stick or Truss
                        maxModuleNumChange(targetTabId);
                    updateRoofMemberType(targetTabId, $(`#option-roof-member-type-${sourceTabId}`).val());
                    updateNumberSegment1(targetTabId, $(`#option-number-segments1-${sourceTabId}`).val());
                    updateFloorMemberType(targetTabId, $(`#option-floor-member-type-${sourceTabId}`).val());
                    updateNumberSegment2(targetTabId, $(`#option-number-segments2-${sourceTabId}`).val());

                    drawTrussGraph(targetTabId);
                    drawStickGraph(targetTabId);
                    stick_input_changed[targetTabId] = stick_input_changed[sourceTabId];
                    stick_right_input[targetTabId] = stick_right_input[sourceTabId];
                    checkRoofInput(targetTabId);
                } 

                targetTabId = (caseCount != curTabId ? curTabId : curTabId - 1);
                var i, tabcontent, tablinks, tabName = 'fc-' + targetTabId;
                tabcontent = document.getElementsByClassName("rfdTabContent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                //console.log(caseCount, curTabId, 'fcTab-' + caseCount == curTabId ? curTabId : curTabId - 1);
                document.getElementById('fcTab-' + targetTabId).className += " active";
                
                window.conditionId = parseInt(tabName.slice(3));
                document.getElementById('subPageTitle').innerHTML = 'Framing Data Input';

                $("#option-number-of-conditions").val(caseCount - 1);
                updateNumberOfConditions(caseCount - 1);
                
                console.log('Tab No:', window.conditionId);  
            }
        });
    }
}

var loadPreloadedData = function() {
    return new Promise((resolve, reject) => {
        var projectId = $('#projectId').val();
        if(projectId >= 0){
            $.ajax({
                url:"getProjectJson",
                type:'post',
                data:{projectId: projectId},
                success:function(res){
                    if(res && res.success == true) {
                        preloaded_data = JSON.parse(res.data);
                        console.log(preloaded_data);
                        try{
                            $('#txt-project-number').val(preloaded_data['ProjectInfo']['Number']);
                            $('#txt-project-name').val(preloaded_data['ProjectInfo']['Name']);
                            $('#txt-street-address').val(preloaded_data['ProjectInfo']['Street']);
                            
                            $('#txt-city').val(preloaded_data['ProjectInfo']['City']);
                            $('#option-state').val(preloaded_data['ProjectInfo']['State']);
                            //detectCorrectTownForMA();
                            $('#txt-zip').val(preloaded_data['ProjectInfo']['Zip']);
                            if(preloaded_data['ProjectInfo']['Type']){
                                if(preloaded_data['ProjectInfo']['Type'] == 'Ground Mount'){
                                    $("#option-project-type").val('Ground Mount');
                                    $(".GroundMount-Option").css("display", "block");
                                }
                                else
                                    $("#option-project-type").val('Roof Mount');
                            }
                            if(preloaded_data['ProjectInfo']['SubClient'])
                                $('#option-sub-client').val(preloaded_data['ProjectInfo']['SubClient']);
                            if(preloaded_data['ProjectInfo']['SubProjectNo'])
                                $("#txt-sub-project-number").val(preloaded_data['ProjectInfo']['SubProjectNo']);

                            $('#txt-name-of-field-personnel').val(preloaded_data['Personnel']['Name']);
                            $('#date-of-field-visit').val(preloaded_data['Personnel']['DateOfFieldVisit']);
                            $('#date-of-plan-set').val(preloaded_data['Personnel']['DateOfPlanSet']);

                            $('#txt-building-age').val(preloaded_data['BuildingAge']);

                            $('#option-module-quantity').val(preloaded_data['Equipment']['PVModule']['Quantity']);
                            $('#option-inverter-quantity').val(preloaded_data['Equipment']['PVInverter']['Quantity']);
                            if(preloaded_data['Equipment'] && preloaded_data['Equipment']['PVInverter_2'] && preloaded_data['Equipment']['PVInverter_2']['Type'] != ''){
                                $("#pv-inverter-2").css('display', 'table-row');
                                $('#option-inverter2-quantity').val(preloaded_data['Equipment']['PVInverter_2']['Quantity']);
                            }
                            if(preloaded_data['Equipment'] && preloaded_data['Equipment']['PVInverter_3'] && preloaded_data['Equipment']['PVInverter_3']['Type'] != ''){
                                $("#pv-inverter-3").css('display', 'table-row');
                                $('#option-inverter3-quantity').val(preloaded_data['Equipment']['PVInverter_3']['Quantity']);
                            }

                            $("#option-number-of-conditions").val(preloaded_data['NumberLoadingConditions']);
                            updateNumberOfConditions(parseInt(preloaded_data['NumberLoadingConditions']));

                            for(let i = 0; i < preloaded_data['LoadingCase'].length; i ++)
                            {
                                let caseData = preloaded_data['LoadingCase'][i];
                                $(`#trussFlagOption-${i + 1}-1`).prop('checked', caseData['Analysis_type'] != 2 ? !caseData['TrussFlag'] : false);
                                $(`#trussFlagOption-${i + 1}-2`).prop('checked', caseData['Analysis_type'] != 2 ? caseData['TrussFlag'] : false);
                                $(`#trussFlagOption-${i + 1}-3`).prop('checked', (caseData['Analysis_type'] == 2));
                                $(`#trussFlagOption-${i + 1}-4`).prop('checked', (caseData['Analysis_type'] == 3));

                                var tabType;
                                if($(`#trussFlagOption-${i + 1}-1`)[0].checked) tabType = 0;
                                else if($(`#trussFlagOption-${i + 1}-2`)[0].checked) tabType = 1;
                                else if($(`#trussFlagOption-${i + 1}-3`)[0].checked) tabType = 2;
                                else if($(`#trussFlagOption-${i + 1}-4`)[0].checked) tabType = 3;
                                fcChangeType(i + 1, tabType);

                                if(!caseData['RoofDataInput']['A2_feet'] && !caseData['RoofDataInput']['A2_inches'])
                                {
                                    $(`#af-2-${i + 1}`).val(caseData['RoofDataInput']['A2']);
                                    $(`#ai-2-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-2-${i + 1}`).val(caseData['RoofDataInput']['A2_feet']);
                                    $(`#ai-2-${i + 1}`).val(caseData['RoofDataInput']['A2_inches']);
                                }
                                $(`#a-2-${i + 1}`).val(caseData['RoofDataInput']['A2']);
                                if(!caseData['RoofDataInput']['A3_feet'] && !caseData['RoofDataInput']['A3_inches'])
                                {
                                    $(`#af-3-${i + 1}`).val(caseData['RoofDataInput']['A3']);
                                    $(`#ai-3-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-3-${i + 1}`).val(caseData['RoofDataInput']['A3_feet']);
                                    $(`#ai-3-${i + 1}`).val(caseData['RoofDataInput']['A3_inches']);
                                }
                                $(`#a-3-${i + 1}`).val(caseData['RoofDataInput']['A3']);
                                if(!caseData['RoofDataInput']['A4_feet'] && !caseData['RoofDataInput']['A4_inches'])
                                {
                                    $(`#af-4-${i + 1}`).val(caseData['RoofDataInput']['A4']);
                                    $(`#ai-4-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-4-${i + 1}`).val(caseData['RoofDataInput']['A4_feet']);
                                    $(`#ai-4-${i + 1}`).val(caseData['RoofDataInput']['A4_inches']);
                                }
                                $(`#a-4-${i + 1}`).val(caseData['RoofDataInput']['A4']);
                                $(`#a-5-${i + 1}`).val(caseData['RoofDataInput']['A5']);
                                $(`#a-6-${i + 1}`).val(caseData['RoofDataInput']['A6']);
                                $(`#a-7-${i + 1}`).val(caseData['RoofDataInput']['A7']);
                                if(!caseData['RoofDataInput']['A8_feet'] && !caseData['RoofDataInput']['A8_inches'])
                                {
                                    $(`#af-8-${i + 1}`).val(caseData['RoofDataInput']['A8']);
                                    $(`#ai-8-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-8-${i + 1}`).val(caseData['RoofDataInput']['A8_feet']);
                                    $(`#ai-8-${i + 1}`).val(caseData['RoofDataInput']['A8_inches']);
                                }
                                $(`#a-8-${i + 1}`).val(caseData['RoofDataInput']['A8']);
                                if(!caseData['RoofDataInput']['A9_feet'] && !caseData['RoofDataInput']['A9_inches'])
                                {
                                    $(`#af-9-${i + 1}`).val(caseData['RoofDataInput']['A9']);
                                    $(`#ai-9-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-9-${i + 1}`).val(caseData['RoofDataInput']['A9_feet']);
                                    $(`#ai-9-${i + 1}`).val(caseData['RoofDataInput']['A9_inches']);
                                }
                                $(`#a-9-${i + 1}`).val(caseData['RoofDataInput']['A9']);
                                if(!caseData['RoofDataInput']['A10_feet'] && !caseData['RoofDataInput']['A10_inches'])
                                {
                                    $(`#af-10-${i + 1}`).val(caseData['RoofDataInput']['A10']);
                                    $(`#ai-10-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#af-10-${i + 1}`).val(caseData['RoofDataInput']['A10_feet']);
                                    $(`#ai-10-${i + 1}`).val(caseData['RoofDataInput']['A10_inches']);
                                }
                                $(`#a-10-${i + 1}`).val(caseData['RoofDataInput']['A10']);

                                switch (caseData['RoofDataInput']['A_calc_algorithm']){
                                    case 'diagnol':
                                        stick_input_changed[i + 1] = ['angle', 'diagnol']; stick_right_input[i + 1] = 'diagnol'; break;
                                    case 'length':
                                        stick_input_changed[i + 1] = ['angle', 'length']; stick_right_input[i + 1] = 'length'; break;
                                    case 'height':
                                        stick_input_changed[i + 1] = ['angle', 'height']; stick_right_input[i + 1] = 'height'; break;
                                    case 'diagnolheight':
                                        stick_input_changed[i + 1] = ['diagnol', 'height']; stick_right_input[i + 1] = 'diagnolheight'; break;
                                    case 'diagnollength':
                                        stick_input_changed[i + 1] = ['diagnol', 'length']; stick_right_input[i + 1] = 'diagnollength'; break;
                                    case 'heightlength':
                                        stick_input_changed[i + 1] = ['height', 'length']; stick_right_input[i + 1] = 'heightlength'; break;
                                }
                                $(`#calc-algorithm-${i + 1}`).val(caseData['RoofDataInput']['A_calc_algorithm']);

                                $(`#a-11-${i + 1}`).val(caseData['RoofDataInput']['A11']);
                                $(`#a-12-${i + 1}`).val(caseData['RoofDataInput']['A12']);

                                $(`#b-1-${i + 1}`).val(caseData['RafterDataInput']['B1']);
                                $(`#b-2-${i + 1}`).val(caseData['RafterDataInput']['B2']);
                                $(`#b-3-${i + 1}`).val(caseData['RafterDataInput']['B3']);
                                $(`#b-4-${i + 1}`).val(caseData['RafterDataInput']['B4']);
                                $(`#b-5-${i + 1}`).val(caseData['RafterDataInput']['B5']);

                                $(`#c-1-${i + 1}`).val(caseData['CollarTieInformation']['C1']);
                                if(!caseData['CollarTieInformation']['C2_feet'] && !caseData['CollarTieInformation']['C2_inches'])
                                {
                                    $(`#cf-2-${i + 1}`).val(caseData['CollarTieInformation']['C2']);
                                    $(`#ci-2-${i + 1}`).val("0.00");    
                                }
                                else{
                                    $(`#cf-2-${i + 1}`).val(caseData['CollarTieInformation']['C2_feet']);
                                    $(`#ci-2-${i + 1}`).val(caseData['CollarTieInformation']['C2_inches']);
                                }
                                $(`#c-2-${i + 1}`).val(caseData['CollarTieInformation']['C2']);
                                $(`#c-3-${i + 1}`).val(caseData['CollarTieInformation']['C3']);
                                if(!caseData['CollarTieInformation']['C4_feet'] && !caseData['CollarTieInformation']['C4_inches'])
                                {
                                    $(`#cf-4-${i + 1}`).val(caseData['CollarTieInformation']['C4']);
                                    $(`#ci-4-${i + 1}`).val("0.00");
                                }
                                else{
                                    $(`#cf-4-${i + 1}`).val(caseData['CollarTieInformation']['C4_feet']);
                                    $(`#ci-4-${i + 1}`).val(caseData['CollarTieInformation']['C4_inches']);
                                }
                                $(`#c-4-${i + 1}`).val(caseData['CollarTieInformation']['C4']);

                                $(`#d-0-${i + 1}`).val(caseData['RoofDeckSurface']['D0']);
                                $(`#d-1-${i + 1}`).val(caseData['RoofDeckSurface']['D1']);
                                $(`#d-2-${i + 1}`).val(caseData['RoofDeckSurface']['D2']);
                                $(`#d-3-${i + 1}`).val(caseData['RoofDeckSurface']['D3']);
                                $(`#d-4-${i + 1}`).val(caseData['RoofDeckSurface']['D4']);
                                $(`#d-5-${i + 1}`).val(caseData['RoofDeckSurface']['D5']);
                                $(`#d-6-${i + 1}`).val(caseData['RoofDeckSurface']['D6']);
                                $(`#d-7-${i + 1}`).val(caseData['RoofDeckSurface']['D7']);
                                $(`#d-8-${i + 1}`).val(caseData['RoofDeckSurface']['D8']);
                                $(`#d-9-${i + 1}`).val(caseData['RoofDeckSurface']['D9']);
                                if(caseData['Stanchions'] && caseData['Stanchions']['J3'] > 0)
                                    $(`#j-3-${i + 1}`).val(caseData['Stanchions']['J3']);

                                if(!caseData['Location']['E1_feet'] && !caseData['Location']['E1_inches'])
                                {
                                    $(`#ef-1-${i + 1}`).val(caseData['Location']['E1']);
                                    $(`#ei-1-${i + 1}`).val("0.00");
                                }
                                else{
                                    $(`#ef-1-${i + 1}`).val(caseData['Location']['E1_feet']);
                                    $(`#ei-1-${i + 1}`).val(caseData['Location']['E1_inches']);
                                }
                                $(`#e-1-${i + 1}`).val(caseData['Location']['E1']);
                                if(!caseData['Location']['E2_feet'] && !caseData['Location']['E2_inches'])
                                {
                                    $(`#ef-2-${i + 1}`).val(caseData['Location']['E2']);
                                    $(`#ei-2-${i + 1}`).val("0.00");
                                }
                                else{
                                    $(`#ef-2-${i + 1}`).val(caseData['Location']['E2_feet']);
                                    $(`#ei-2-${i + 1}`).val(caseData['Location']['E2_inches']);
                                }
                                $(`#e-2-${i + 1}`).val(caseData['Location']['E2']);

                                $(`#f-1-${i + 1}`).val(caseData['NumberOfModules']['F1']);
                                if(tabType != 2) // If Stick or Truss
                                    maxModuleNumChange(i + 1);
                                $(`#g-1-${i + 1}`).val(caseData['NSGap']['G1']);
                                if(caseData['NSGap']['G2']) $(`#g-2-${i + 1}`).val(caseData['NSGap']['G2']);
                                if(caseData['NSGap']['G3']) $(`#g-3-${i + 1}`).val(caseData['NSGap']['G3']);
                                if(caseData['NSGap']['G4']) $(`#g-4-${i + 1}`).val(caseData['NSGap']['G4']);
                                
                                $(`#h-1-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H1']);
                                $(`#h-2-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H2']);
                                $(`#h-3-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H3']);
                                $(`#h-4-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H4']);
                                $(`#h-5-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H5']);
                                $(`#h-6-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H6']);
                                $(`#h-7-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H7']);
                                $(`#h-8-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H8']);
                                $(`#h-9-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H9']);
                                $(`#h-10-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H10']);
                                $(`#h-11-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H11']);
                                $(`#h-12-${i + 1}`).prop('checked', caseData['RotateModuleOrientation']['H12']);

                                $(`#i-1-${i + 1}`).val(caseData['Notes']['I1']);

                                let trussData = caseData['TrussDataInput'];
                                $(`#option-roof-slope-${i + 1}`).val(trussData['RoofSlope']['Type']);
                                $(`#txt-roof-degree-${i + 1}`).val(trussData['RoofSlope']['Degree']);
                                $(`#td-unknown-degree1-${i + 1}`).val(trussData['RoofSlope']['UnknownDegree']);
                                $(`#td-calculated-roof-plane-length-${i + 1}`).val(trussData['RoofSlope']['CalculatedRoofPlaneLength']);
                                $(`#td-diff-between-measured-and-calculated-${i + 1}`).val(trussData['RoofSlope']['td-diff-between-measured-and-calculated']);
                                
                                $(`#option-roof-member-type-${i + 1}`).val(trussData['RoofPlane']['MemberType']);
                                updateRoofMemberType(i + 1, trussData['RoofPlane']['MemberType']);
                                if(!trussData['RoofPlane']['Length_feet'] && !trussData['RoofPlane']['Length_inches'])
                                {
                                    $(`#txt-length-of-roof-plane-f-${i + 1}`).val(trussData['RoofPlane']['Length']);
                                    $(`#txt-length-of-roof-plane-i-${i + 1}`).val("0.00");
                                }
                                else{
                                    $(`#txt-length-of-roof-plane-f-${i + 1}`).val(trussData['RoofPlane']['Length_feet']);
                                    $(`#txt-length-of-roof-plane-i-${i + 1}`).val(trussData['RoofPlane']['Length_inches']);
                                }
                                $(`#txt-length-of-roof-plane-${i + 1}`).val(trussData['RoofPlane']['Length']);
                                $(`#option-number-segments1-${i + 1}`).val(trussData['RoofPlane']['NumberOfSegments']);
                                $(`#option-number-segments1-${i + 1} > option`).each(function() { 
                                    if ($(this).attr('data-value') == trussData['RoofPlane']['NumberOfSegments']) {
                                        $(this).attr('selected', true);
                                    }
                                    else {
                                        $(this).attr('selected', false);
                                    }         
                                });
                                $(`#td-sum-of-length-entered-${i + 1}`).val(trussData['RoofPlane']['SumOfLengthsEntered']);
                                $(`#td-checksum-of-segment1-${i + 1}`).val(trussData['RoofPlane']['ChecksumOfChordLength']);
                                if(trussData['RoofPlane']['LengthOfSegment1']){
                                    if(!trussData['RoofPlane']['LengthOfSegment1_feet'] && !trussData['RoofPlane']['LengthOfSegment1_inches'])
                                    {
                                        $(`#txt-roof-segment1-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment1']);
                                        $(`#txt-roof-segment1-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment1-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment1_feet']);
                                        $(`#txt-roof-segment1-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment1_inches']);
                                    }
                                    $(`#txt-roof-segment1-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment1']);
                                } 
                                if(trussData['RoofPlane']['LengthOfSegment2']){
                                    if(!trussData['RoofPlane']['LengthOfSegment2_feet'] && !trussData['RoofPlane']['LengthOfSegment2_inches'])
                                    {
                                        $(`#txt-roof-segment2-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment2']);
                                        $(`#txt-roof-segment2-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment2-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment2_feet']);
                                        $(`#txt-roof-segment2-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment2_inches']);
                                    }
                                    $(`#txt-roof-segment2-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment2']);
                                } 
                                if(trussData['RoofPlane']['LengthOfSegment3']){
                                    if(!trussData['RoofPlane']['LengthOfSegment3_feet'] && !trussData['RoofPlane']['LengthOfSegment3_inches'])
                                    {
                                        $(`#txt-roof-segment3-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment3']);
                                        $(`#txt-roof-segment3-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment3-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment3_feet']);
                                        $(`#txt-roof-segment3-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment3_inches']);
                                    }
                                    $(`#txt-roof-segment3-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment3']);
                                } 
                                if(trussData['RoofPlane']['LengthOfSegment4']){
                                    if(!trussData['RoofPlane']['LengthOfSegment4_feet'] && !trussData['RoofPlane']['LengthOfSegment4_inches'])
                                    {
                                        $(`#txt-roof-segment4-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment4']);
                                        $(`#txt-roof-segment4-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment4-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment4_feet']);
                                        $(`#txt-roof-segment4-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment4_inches']);
                                    }
                                    $(`#txt-roof-segment4-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment4']);
                                } 
                                if(trussData['RoofPlane']['LengthOfSegment5']){
                                    if(!trussData['RoofPlane']['LengthOfSegment5_feet'] && !trussData['RoofPlane']['LengthOfSegment5_inches'])
                                    {
                                        $(`#txt-roof-segment5-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment5']);
                                        $(`#txt-roof-segment5-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment5-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment5_feet']);
                                        $(`#txt-roof-segment5-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment5_inches']);
                                    }
                                    $(`#txt-roof-segment5-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment5']);
                                } 
                                if(trussData['RoofPlane']['LengthOfSegment6']){
                                    if(!trussData['RoofPlane']['LengthOfSegment6_feet'] && !trussData['RoofPlane']['LengthOfSegment6_inches'])
                                    {
                                        $(`#txt-roof-segment6-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment6']);
                                        $(`#txt-roof-segment6-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-roof-segment6-length-f-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment6_feet']);
                                        $(`#txt-roof-segment6-length-i-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment6_inches']);
                                    }
                                    $(`#txt-roof-segment6-length-${i + 1}`).val(trussData['RoofPlane']['LengthOfSegment6']);
                                } 
                                updateNumberSegment1(i + 1, parseInt(trussData['RoofPlane']['NumberOfSegments']));

                                $(`#option-floor-member-type-${i + 1}`).val(trussData['FloorPlane']['MemberType']);
                                updateFloorMemberType(i + 1, trussData['FloorPlane']['MemberType']);
                                if(!trussData['FloorPlane']['Length_feet'] && !trussData['FloorPlane']['Length_inches'])
                                {
                                    $(`#txt-length-of-floor-plane-f-${i + 1}`).val(trussData['FloorPlane']['Length']);
                                    $(`#txt-length-of-floor-plane-i-${i + 1}`).val("0.00");
                                }
                                else{
                                    $(`#txt-length-of-floor-plane-f-${i + 1}`).val(trussData['FloorPlane']['Length_feet']);
                                    $(`#txt-length-of-floor-plane-i-${i + 1}`).val(trussData['FloorPlane']['Length_inches']);
                                }
                                $(`#txt-length-of-floor-plane-${i + 1}`).val(trussData['FloorPlane']['Length']);
                                $(`#option-number-segments2-${i + 1}`).val(trussData['FloorPlane']['NumberOfSegments']);
                                $(`#option-number-segments2-${i + 1} > option`).each(function() { 
                                    if ($(this).attr('data-value') == trussData['FloorPlane']['NumberOfSegments'])
                                        $(this).attr('selected', true);
                                    else
                                        $(this).attr('selected', false);      
                                });
                                $(`#td-total-length-entered-${i + 1}`).val(trussData['FloorPlane']['SumOfLengthsEntered']);
                                $(`#td-checksum-of-segment2-${i + 1}`).val(trussData['FloorPlane']['ChecksumOfChordLength']);
                                if(trussData['FloorPlane']['LengthOfSegment1']){
                                    if(!trussData['FloorPlane']['LengthOfSegment1_feet'] && !trussData['FloorPlane']['LengthOfSegment1_inches'])
                                    {
                                        $(`#txt-floor-segment1-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment1']);
                                        $(`#txt-floor-segment1-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment1-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment1_feet']);
                                        $(`#txt-floor-segment1-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment1_inches']);
                                    }
                                    $(`#txt-floor-segment1-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment1']);
                                } 
                                if(trussData['FloorPlane']['LengthOfSegment2']){
                                    if(!trussData['FloorPlane']['LengthOfSegment2_feet'] && !trussData['FloorPlane']['LengthOfSegment2_inches'])
                                    {
                                        $(`#txt-floor-segment2-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment2']);
                                        $(`#txt-floor-segment2-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment2-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment2_feet']);
                                        $(`#txt-floor-segment2-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment2_inches']);
                                    }
                                    $(`#txt-floor-segment2-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment2']);
                                } 
                                if(trussData['FloorPlane']['LengthOfSegment3']){
                                    if(!trussData['FloorPlane']['LengthOfSegment3_feet'] && !trussData['FloorPlane']['LengthOfSegment3_inches'])
                                    {
                                        $(`#txt-floor-segment3-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment3']);
                                        $(`#txt-floor-segment3-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment3-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment3_feet']);
                                        $(`#txt-floor-segment3-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment3_inches']);
                                    }
                                    $(`#txt-floor-segment3-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment3']);
                                } 
                                if(trussData['FloorPlane']['LengthOfSegment4']){
                                    if(!trussData['FloorPlane']['LengthOfSegment4_feet'] && !trussData['FloorPlane']['LengthOfSegment4_inches'])
                                    {
                                        $(`#txt-floor-segment4-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment4']);
                                        $(`#txt-floor-segment4-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment4-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment4_feet']);
                                        $(`#txt-floor-segment4-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment4_inches']);
                                    }
                                    $(`#txt-floor-segment4-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment4']);
                                } 
                                if(trussData['FloorPlane']['LengthOfSegment5']){
                                    if(!trussData['FloorPlane']['LengthOfSegment5_feet'] && !trussData['FloorPlane']['LengthOfSegment5_inches'])
                                    {
                                        $(`#txt-floor-segment5-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment5']);
                                        $(`#txt-floor-segment5-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment5-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment5_feet']);
                                        $(`#txt-floor-segment5-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment5_inches']);
                                    }
                                    $(`#txt-floor-segment5-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment5']);
                                } 
                                if(trussData['FloorPlane']['LengthOfSegment6']){
                                    if(!trussData['FloorPlane']['LengthOfSegment6_feet'] && !trussData['FloorPlane']['LengthOfSegment6_inches'])
                                    {
                                        $(`#txt-floor-segment6-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment6']);
                                        $(`#txt-floor-segment6-length-i-${i + 1}`).val("0.00");
                                    }
                                    else{
                                        $(`#txt-floor-segment6-length-f-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment6_feet']);
                                        $(`#txt-floor-segment6-length-i-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment6_inches']);
                                    }
                                    $(`#txt-floor-segment6-length-${i + 1}`).val(trussData['FloorPlane']['LengthOfSegment6']);
                                } 
                                updateNumberSegment2(i + 1, parseInt(trussData['FloorPlane']['NumberOfSegments']));

                                for(let j = 0; j < caseData['Diagonal1'].length; j ++){
                                    $(`#diag-1-${j + 1}-${i + 1}`).prop('checked', !caseData['Diagonal1'][j]['include']);
                                    $(`#option-diagonals-mem1-${j + 1}-type-${i + 1}`).val(caseData['Diagonal1'][j]['memType']);
                                    $(`#td-diag-1-${j + 1}-${i + 1}`).val(caseData['Diagonal1'][j]['memId']);
                                }

                                for(let j = 0; j < caseData['Diagonal2'].length; j ++){
                                    $(`#diag-2-${j + 1}-${i + 1}`).prop('checked', !caseData['Diagonal2'][j]['include']);
                                    $(`#diag-2-reverse-${j + 1}-${i + 1}`).prop('checked', caseData['Diagonal2'][j]['reverse']);
                                    $(`#option-diagonals-mem2-${j + 1}-type-${i + 1}`).val(caseData['Diagonal2'][j]['memType']);
                                    $(`#td-diag-2-${j + 1}-${i + 1}`).val(caseData['Diagonal2'][j]['memId']);
                                }
                            }
                            loadASCEOptions(preloaded_data['ProjectInfo']['State']);
                            loadPermitList(preloaded_data['ProjectInfo']['State']);
                            loadPILList(preloaded_data['ProjectInfo']['State']);

                            $(`#wind-speed`).val(preloaded_data['Wind']);
                            $(`#wind-speed-override`).prop('checked', preloaded_data['WindCheckbox']);
                            $(`#ground-snow`).val(preloaded_data['Snow']);
                            $(`#ground-snow-override`).prop('checked', preloaded_data['SnowCheckbox']);
                            $(`#ibc-year`).val(preloaded_data['IBC']);
                            $(`#asce-year`).val(preloaded_data['ASCE']);
                            $(`#nec-year`).val(preloaded_data['NEC']);
                            $(`#wind-exposure`).val(preloaded_data['WindExposure']);
                            $(`#override-unit`).val(preloaded_data['Units']);

                            resolve(true);

                            // for(let i = 1; i <= 10; i ++)
                            // {
                            //     checkRoofInput(i);
                            //     drawTrussGraph(i);
                            //     drawStickGraph(i);
                            // }
                        }
                        catch(e){
                            resolve(false);
                            console.log(e);
                        }
                    } 
                    else{
                        swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    res = JSON.parse(xhr.responseText);
                    swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                    resolve(false);
                }
            });
        }
        else{
            loadASCEOptions("MA");
            loadPermitList("MA");
            loadPILList("MA");
            resolve(true);
        }
    });
}

    var loadPreloadedPermitData = function() {
        return new Promise((resolve, reject) => {
            var projectId = $('#projectId').val();
            if(projectId >= 0){
                $.ajax({
                    url:"getProjectPermitJson",
                    type:'post',
                    data:{projectId: projectId, state: $('#option-state').val() },
                    success:function(res){
                        console.log(res);
                        if(res && res.success == true) {
                            for (var i=0; i<res.data.length; i++) {
                                var permit_data = JSON.parse(res.data[i].data);
                                try{ 
                                    // if (res.data[i].filename == "ucc_f100_cpa.pdf") {
                                    //     updateUccForm(preloaded_data, res.data[i].filename);
                                    //     openPermitTab(1, res.data[i].filename,'Form Const');
                                    // }
                                    // if (res.data[i].filename == "ucc_f110_bldg.pdf") {
                                    //     updateUccForm(preloaded_data, res.data[i].filename);
                                    //     openPermitTab(2, res.data[i].filename,'Form Bldg');
                                    // }
                                    // if (res.data[i].filename == "ucc_f120_elec.pdf") {
                                    //     updateUccForm(preloaded_data, res.data[i].filename);
                                    //     openPermitTab(3, res.data[i].filename,'Form Elec');
                                    // }
                                    // if (res.data[i].filename == "PA ucc-3.pdf") {
                                    //     updateUccForm(preloaded_data, res.data[i].filename);
                                    //     openPermitTab(4, res.data[i].filename,'Form UCC Bldg');
                                    // }
                                    // if (res.data[i].filename == "ucc_f140_fire.pdf") {
                                    //     updateUccForm(preloaded_data, res.data[i].filename);
                                    //     openPermitTab(5, res.data[i].filename,'Form Fire');
                                    // }
                                    if(res.fileinfos[i].id)
                                        openPermitTab(res.fileinfos[i].id, res.data[i].filename, res.fileinfos[i].tabname, permit_data, false, res.fileinfos[i].formtype);
                                }
                                catch(e){
                                    resolve(false);
                                    console.log(e);
                                }    
                            }
                            resolve(true);
                        } 
                        else{
                            swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                            resolve(false);
                        }
                        resolve(true);
                    },
                    error: function(xhr, status, error) {
                        res = JSON.parse(xhr.responseText);
                        swal.fire({ title: "Error", text: res.message, icon: "error", confirmButtonText: `OK` });
                        resolve(false);
                    }
                });
            }
            resolve(true);
        });
    }

    var applyUserSetting = function(){
        $.ajax({
            url:"getUserSetting",
            type:'post',
            success: function(res){
                if(res.success){
                    $(".h13 input").css('font-size', res.inputFontSize + 'pt');
                    $(".h13 td").css('font-size', res.inputFontSize + 'pt');
                    $(".h13").css('height', res.inputCellHeight + 'pt');
                    $(".h13 input").css('font-family', res.inputFontFamily);
                    $(".h13 td").css('font-family', res.inputFontFamily);
                }
            }
        });
    }

    var loadPermitList = function(state){
        $.ajax({
            url:"getPermitList",
            type:'post',
            data:{state: state},
            success: function(res){
                if(res.success && res.data && res.data.length > 0 && state == $('#option-state').val()){
                    $("#permitContent").html("");
                    for(let i = 0; i < res.data.length; i ++){
                        let html ='<div class="row mb-3" style="align-items: center;">' + 
                            "<img class='mr-3 pdfIcon' src='public/img/pdf.png'></img>" + 
                            '<a class="link-fx font-size-base" style="cursor:pointer;" onclick="openPermitTab(\'' + res.data[i].id + '\', \'' + res.data[i].filename + '\', \'' + res.data[i].tabname + '\', null, true, 1)">' + res.data[i].description + '</a>' + 
                        '</div>';
                        $("#permitContent").append(html);
                    }
                } else {
                    $("#permitContent").html('<div id="defaultPermitContent">No state forms available at this time</div>');
                }
            }
        });
    }

    var loadPILList = function(state){
        $.ajax({
            url:"getPILList",
            type:'post',
            data:{state: state},
            success: function(res){
                if(res.success && res.data && res.data.length > 0 && state == $('#option-state').val()){
                    $("#pilContent").html("");
                    for(let i = 0; i < res.data.length; i ++){
                        let html ='<div class="row mb-3" style="align-items: center;">' + 
                            "<img class='mr-3 pdfIcon' src='public/img/pdf.png'></img>" + 
                            '<a class="link-fx font-size-base" style="cursor:pointer;" onclick="openPermitTab(\'' + res.data[i].id + '\', \'' + res.data[i].filename + '\', \'' + res.data[i].tabname + '\', null, true, 2)">' + res.data[i].description + '</a>' + 
                        '</div>';
                        $("#pilContent").append(html);
                    }
                } else {
                    $("#pilContent").html('<div id="defaultPILContent">No state forms available at this time</div>');
                }
            }
        });
    }

    var setProjectIdComment = function(){
        return new Promise((resolve, reject) => {
            $.ajax({
                url:"getProjectNumComment",
                type:'post',
                data:{projectNumber: $("#txt-project-number").val(), projectId: $('#projectId').val()},
                success: function(res){
                    if(res.success){
                        var comment = '&nbsp;&nbsp;';
                        if(res.duplicated)
                        {
                            comment += $("#txt-project-number").val() + ' is duplicated. ';
                            $("#project-id-comment").css('color', '#FF0000');
                        } else if(res.biggerthanmax){
                            comment += ($("#txt-project-number").val() + " is " + ($("#txt-project-number").val() - res.maxId) + " more than the last job number used. Consider using a job number of " + (parseInt(res.maxId) + 1) + ". ");
                            $("#project-id-comment").css('color', '#FF0000');
                        }
                        else
                            $("#project-id-comment").css('color', '#000000');
                        if(res.maxId)
                            comment += ('Highest Project Number Used: ' + res.maxId);
                        $("#project-id-comment").html(comment);
                        resolve(true);
                    }
                },
                error: function(xhr, status, error) {
                    resolve(false);
                }
            });
        });
    }

    // initialize function
    var initializeSpreadSheet = async function() {
        applyUserSetting();
        await loadPreloadedData();
        await loadStateOptions();
        if(await isSubClientAllowed())
            await loadSubClients();
        await loadDataCheck();
        loadElectric();
        await loadPreloadedPermitData();
        loadEquipmentSection();
        await setProjectIdComment();

        var i;
        for(i = 1; i <= 10; i ++)
        {
            drawTrussGraph(i);
            drawStickGraph(i);
            //stick_ctx[i].translate(-100, 100);

            keepStatus = true;
            if (preloaded_data.size == 0)
                keepStatus = false;

            updateNumberSegment1(i, $(`#option-number-segments1-${i}`).children("option:selected").val(), keepStatus);
            updateNumberSegment2(i, $(`#option-number-segments2-${i}`).children("option:selected").val(), keepStatus);

            checkRoofInput(i);
            // GetCPU();
        }

        loadFileList();
    }



initializeSpreadSheet();
});

function delFile(){
    var selectedIds = $("#filetree").jstree("get_checked", null, true);
    if(selectedIds.length > 0){
        let toast = Swal.mixin({
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success m-1',
                cancelButton: 'btn btn-danger m-1',
                input: 'form-control'
            }
        });
        toast.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover file(s)!',
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-danger m-1',
                cancelButton: 'btn btn-secondary m-1'
            },
            confirmButtonText: 'Yes, delete!',
            html: false,
            preConfirm: e => {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve();
                    }, 50);
                });
            }
        }).then(result => {
            if (result.value) {
                selectedIds.forEach(nodeId => {
                    var node = $('#filetree').jstree(true).get_node(nodeId);
                    if(node.type == "infile"){
                        $.ajax({
                            url:"delDropboxFile",
                            type:'post',
                            data:{filename: node.text, projectId: $('#projectId').val()},
                            success:function(res){
                                if (res.success) {
                                    $("#filetree").jstree("delete_node", '#'+nodeId);
                                } else
                                    $("#filetree").jstree("uncheck_node", '#'+nodeId);
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
                    } else 
                        $("#filetree").jstree("uncheck_node", '#'+nodeId);
                });
            } else if (result.dismiss === 'cancel') {
                toast.fire('Cancelled', 'File is safe :)', 'success');
            }
        });
    }
}

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

// const download = async (url, name) => {

async function download(url, name) {
	const a = document.createElement('a');
	a.download = name;
	a.href = url;
	a.style.display = 'none';
	document.body.append(a);
	a.click();

	// Chrome requires the timeout
	await delay(100);
	a.remove();
};

function downloadFile(){
    var selectedIds = $("#filetree").jstree("get_checked", null, true);
    let files = [];
    for(let i = 0; i < selectedIds.length; i ++){
        var node = $('#filetree').jstree(true).get_node(selectedIds[i]);
        if((node.type == "infile" || node.type == "outfile") && node.original && node.original.path){
            files.push(node.original.path);
        }
    }
    if(files.length > 0){
        swal.fire({ title: "Please wait...", showConfirmButton: false });
        swal.showLoading();
        $.ajax({
            url:"getDownloadLink",
            type:'post',
            data:{files: files, projectId: $('#projectId').val()},
            success:function(res){
                swal.close();
                if(res.success){
                    download(res.link, res.name);
                } else{
                    swal.fire({ title: "Failed!", text: res.message, icon: "error", confirmButtonText: `OK` });
                }
            },
            error: function(xhr, status, error) {
                swal.close();
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
            }
        });
    } else {
        swal.fire({ title: "Warning", text: 'Please select files', icon: "warning", confirmButtonText: `OK` });
    }
}

var nodeNames = {};

function editFile(){
    var selectedIds = $("#filetree").jstree("get_checked", null, true);
    if(selectedIds.length > 0){
        var filetree = $("#filetree").jstree(true);
        selectedIds.forEach(nodeId => {
            var node = $('#filetree').jstree(true).get_node(nodeId);
            nodeNames[node.id] = node.text;
            if(node.type == "infile")
                filetree.edit(node, null, (data, status) => {
                    if(nodeNames[data.id] != data.text){
                        $.ajax({
                            url:"renameFile",
                            type:'post',
                            data:{filename: nodeNames[data.id], newname: data.text, projectId: $('#projectId').val()},
                            success: function(res){
                                if(!res.success){
                                    swal.fire({ title: "Failed!", text: res.message, icon: "error", confirmButtonText: `OK` });
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
                });
        })
    }
}

// function updateUccForm(data, filename){
//     if (filename == "ucc_f100_cpa.pdf") {
//         $('#ucc_f100 input:text:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data[property]);
//         });
//         $('#ucc_f100 select:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data.property);
//         });
//     }

//     if (filename == "ucc_f110_bldg.pdf") {
//         $('#ucc_f110 input:text:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data.property);
//         });
//         $('#ucc_f110 select:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data.property);
//         });
//     }

//     if (filename == "ucc_f120_elec.pdf") {
//         $('#ucc_f120 input:text:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data.property);
//         });
//         $('#ucc_f120 select:enabled').each(function() { 
//             var property = $(this).attr('id');
//         $(this).val(data.property);
//         });
//     }

//     if (filename == "PA ucc-3.pdf") {
//         $('#pa_ucc3 input:text:enabled').each(function() {
//             var property = $(this).attr('id'); 
//             $(this).val(data.property);
//         });
//         $('#pa_ucc3 select:enabled').each(function() { 
//             var property = $(this).attr('id');
//             $(this).val(data.property);
//         });
//     }
// }

var submitPdfData = async function(id, filename, data, status) {
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    var message = '';
    // swal.fire({ title: "Please wait...", showConfirmButton: false });
    // swal.showLoading();

    var jsonBlob = new Blob([data], {
        type: "application/pdf"
    });
    //var url = window.URL.createObjectURL(jsonBlob);
    //download(url, filename);

    var pdfDoc = await PDFLib.PDFDocument.load(data);
    var form = pdfDoc.getForm();
    form.flatten();
    var flattened = await pdfDoc.save();
    var flattenedBlob = new Blob([flattened], {
        type: "application/pdf"
    });

    var fd = new FormData();
    fd.append("id", id);
    fd.append("projectId", $('#projectId').val());
    fd.append("status", status);
    fd.append("filename", filename);
    fd.append("data", jsonBlob);
    fd.append("flattened", flattenedBlob);

    $.ajax({
        url:"submitPDF",
        data:fd,
        processData: false,
        contentType: false,
        type:'POST',
        
        success:function(res){
            swal.close();
            if (res.status == true) {
                if(res.addtotree == true)
                    addFileNode("OUT", res.info, false);

                swal.fire({
                    title: "Success",
                    text: res.message,
                    icon: "success",
                    confirmButtonText: `OK`
                });
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
            message = res.message;
            swal.fire({ title: "Error",
                    text: message == "" ? "Error happened while processing. Please try again later." : message,
                    icon: "error",
                    confirmButtonText: `OK` });
        }
    });
    
    //}
}

function buildPermitFields(id, filename){
    return new Promise((resolve, reject) => {
        $.ajax({
            url:"getPermitFields",
            data:{filename: filename, state: $('#option-state').val()},
            type:'POST',
            success:function(res){
                if (res.status == true && res.fields && res.fields.length > 0) {
                    $("#tab_permit_" + id).append(
                        '<div class="row container" style="max-width:100%">' + 
                            '<div class="col-3">' + 
                                '<div class="row">' + 
                                    '<div class="col-12">' + 
                                        '<h5 class="mt-2 ml-2">' + res.description + '</h5>' + 
                                        "<h6 class='mt-2 ml-2'>After you input the data below then click Update PDF button, don't input to the pdf directly.</h6>" + 
                                    '</div>' + 
                                    '<div class="col-12">' + 
                                        '<div class="permitCtrlBtns mt-2">' + 
                                            '<button class="mr-4 btn btn-danger" onclick="updatePermitPDF(' + id + ', \'' + filename + '\')">' + 
                                                '<i class="fa fa-file-pdf mr-1" aria-hidden="true"></i>Update PDF' + 
                                            '</button>' + 
                                            '<button class="mr-2 btn btn-info" onclick="savePermit(' + id + ', \'' + filename + '\')">' + 
                                                '<i class="far fa-save mr-1"></i>Save' + 
                                            '</button>' + 
                                        '</div>' + 
                                    '</div>' + 
                                '</div>' + 
                                '<div class="row">' + 
                                    '<div class="col-12">' + 
                                        '<table id="permit-info-table-' + id + '" cellspacing="0" cellpadding="0" style="border-spacing:0;" >' + 
                                            '<tbody>' + 
                                                '<tr class="h13">' + 
                                                    '<td><div style="overflow:hidden"></td>' + 
                                                    '<td><div style="overflow:hidden"></td>' + 
                                                '</tr>' + 
                                            '</tbody>' + 
                                        '</table>' + 
                                    '</div>' + 
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-9">' + 
                                '<iframe id="permitViewer_' + id + '" src="" type="application/pdf" class="pdfViewer" disabled></iframe>' + 
                            '</div>' + 
                        '</div>'
                    );

                    $.ajax({
                        url:"getCompanyInfo",
                        type:'post',
                        data:{state: $('#option-state').val()},
                        success: function(response){
                            if(response.success){
                                var companyInfo = response.company;
                                var permitInfo = response.permit;

                                res.fields.sort((a, b) => {
                                    if(!a || !a.sortIndex) return 1;
                                    if(!b || !b.sortIndex) return -1;
                                    if(parseInt(a.sortIndex) < parseInt(b.sortIndex)) return -1;
                                    if(parseInt(a.sortIndex) > parseInt(b.sortIndex)) return 1;
                                    return 0;
                                });
                                res.fields.forEach((field, index) => {
                                    if(field.pdfcheck == 1){
                                        let defaultvalue = '';
                                        if(field.dbinfo == 'job_projectname')
                                            defaultvalue = $("#txt-project-name").val();
                                        else if(field.dbinfo == 'job_address')
                                            defaultvalue = $("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#option-state").val() + ", " + $("#txt-zip").val();
                                        else if(field.dbinfo == 'street_address')
                                            defaultvalue = $("#txt-street-address").val();
                                        else if(field.dbinfo == 'site_city')
                                            defaultvalue = $("#txt-city").val();
                                        else if(field.dbinfo == 'state_code')
                                            defaultvalue = $("#option-state").val();
                                        else if(field.dbinfo == 'zip_code')
                                            defaultvalue = $("#txt-zip").val();
                                        else if(field.dbinfo == 'project_manager'){
                                            if($("#option-user-id").length > 0)
                                                defaultvalue = $("#option-user-id").children("option:selected").text();
                                        }
                                        else if(field.dbinfo == 'company_name')
                                            defaultvalue = companyInfo ? companyInfo.company_name : '';
                                        else if(field.dbinfo == 'company_telno')
                                            defaultvalue = companyInfo ? companyInfo.company_telno : '';
                                        else if(field.dbinfo == 'company_address')
                                            defaultvalue = companyInfo ? companyInfo.company_address : '';
                                        else if(field.dbinfo == 'company_name_address')
                                            defaultvalue = companyInfo ? companyInfo.company_name + ', ' + companyInfo.company_address + ', ' + companyInfo.city + ', ' + companyInfo.state + ' ' + companyInfo.zip : '';
                                        else if(field.dbinfo == 'contact_person')
                                            defaultvalue = permitInfo ? permitInfo.contact_person : '';
                                        else if(field.dbinfo == 'contact_phone')
                                            defaultvalue = permitInfo ? permitInfo.contact_phone : '';
                                        else if(field.dbinfo == 'FAX')
                                            defaultvalue = permitInfo ? permitInfo.FAX : '';
                                        else if(field.dbinfo == 'construction_email')
                                            defaultvalue = permitInfo ? permitInfo.construction_email : '';
                                        else if(field.dbinfo == 'registration')
                                            defaultvalue = permitInfo ? permitInfo.registration : '';
                                        else if(field.dbinfo == 'exp_date')
                                            defaultvalue = permitInfo ? permitInfo.exp_date : '';
                                        else if(field.dbinfo == 'EIN')
                                            defaultvalue = permitInfo ? permitInfo.EIN : '';
                                        else if(field.dbinfo == 'FAX')
                                            defaultvalue = permitInfo ? permitInfo.FAX : '';
                                        else if(field.dbinfo == 'architect_engineer')
                                            defaultvalue = 'Richard Pantel, P.E.';
                                        else if(field.dbinfo == 'architect_address')
                                            defaultvalue = '35091 Paxson Road, Round Hill, VA 20141';
                                        else if(field.dbinfo == 'architect_email')
                                            defaultvalue = 'rpantel@princeton-engineering.com';
                                        else if(field.dbinfo == 'architect_tel')
                                            defaultvalue = '908-507-5500';
                                        else if(field.dbinfo == 'architect_fax')
                                            defaultvalue = '877-455-5641';
                                        else if(field.dbinfo == 'architect_license')
                                            defaultvalue = 'PE039453R';
                                        else if(field.dbinfo == 'elec_desc_of_work'){
                                            defaultvalue = "Installation of " + $('#option-project-type').val() + " " + (parseFloat($("#inverter-watts").val()) * $("#option-inverter-quantity").val() / 1000) + " kW PV solar system";
                                        } else if(field.dbinfo == 'inverter_qty')
                                            defaultvalue = $("#option-inverter-quantity").val();
                                        else if(field.dbinfo == 'inverter_watts')
                                            defaultvalue = $("#inverter-watts").val() / 1000;
                                        else if(field.dbinfo == 'inverter_model_mfr')
                                            defaultvalue = $("#option-inverter-type").val() + " / " + $("#option-inverter-subtype").val();
                                        else if(field.dbinfo == 'date_today'){
                                            var today = new Date();
                                            var dd = String(today.getDate()).padStart(2, '0');
                                            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                            var yyyy = today.getFullYear();

                                            defaultvalue = mm + '/' + dd + '/' + yyyy;
                                        } else if(field.dbinfo == 'date_report')
                                            defaultvalue = $("#date_report").val();
                                        else if(field.dbinfo == 'pe_project_id')
                                            defaultvalue = companyInfo ? ( companyInfo.company_number + '.' + $("#txt-project-number").val() + ( $("#subproject-num").css('display') != 'none' ? '.' + $("#txt-sub-project-number").val() : '' ) ) : '';
                                        else if(field.defaultvalue)
                                            defaultvalue = field.defaultvalue;
                                        
                                        let html = '';
                                        if(field.section && field.section != ''){
                                            html += '<tr class="h13">';
                                            html += ('<td colspan="2" class="text-left"><b>' + field.section + '</b></td>');
                                            html += '</tr>';
                                        }
                                        html += ('<tr class="h13" ' + (field.htmlcheck == 0 ? 'style="display: none;"' : '' ) + '>');
                                        if(field.type == 0){
                                            html += ('<td class="iw400-right-bdr">' + field.label + '</td>');
                                            html += ('<td class="w400-yellow-bdr"><input type="text" class="permit txt-' + field.htmlfield + '" id="' + field.htmlfield + '" tabindex="' + index + '" value="' + defaultvalue + '" data-pdffield="' + field.pdffield + '" data-idx="' + field.idx + '" data-fileid="' + id + '" data-filename="' + filename + '"></input></td>');
                                        } else if(field.type == 1){
                                            html += ('<td class="iw400-right-bdr">' + field.label + '</td>');
                                            html += '<td class="w400-green-bdr">';
                                            let options = field.options ? field.options.split(",") : [];
                                            var fieldset_el = document.createElement('fieldset');
                                            options.forEach(function(ostr, i) {
                                                var label = document.createElement('label');
                                                var radio = document.createElement('input');
                                                radio.setAttribute('type', 'radio');
                                                radio.setAttribute('value', ostr);
                                                radio.setAttribute('name', field.htmlfield + "_" + field.idx);
                                                radio.setAttribute('data-idx', i);
                                                radio.setAttribute('data-fileid', id);
                                                radio.setAttribute('data-filename', filename);
                                                radio.setAttribute('id', field.htmlfield + '_' + i);
                                                radio.setAttribute('data-pdffield', field.pdffield);
                                                radio.setAttribute('class', 'permit radio-' + field.htmlfield);
                                                if(defaultvalue == ostr){
                                                    radio.setAttribute('checked', true);
                                                }
                                                label.appendChild(radio);
                                                label.appendChild(document.createTextNode(ostr));
                                                fieldset_el.appendChild(label);
                                            });
                                            html += fieldset_el.outerHTML;
                                            html += '</td>';
                                        } else if(field.type == 2){
                                            html += ('<td class="iw400-right-bdr">' + field.label + '</td>');
                                            html += '<td class="w400-green-bdr">';
                                            var input = document.createElement('input');
                                            input.setAttribute('data-idx', field.idx);
                                            input.setAttribute('data-pdffield', field.pdffield);
                                            input.setAttribute('data-fileid', id);
                                            input.setAttribute('data-filename', filename);
                                            input.setAttribute('type', 'checkbox');
                                            input.setAttribute('class', 'permit checkbox-' + field.htmlfield);
                                            input.setAttribute('id', field.htmlfield);
                                            if(defaultvalue == 'on') input.setAttribute('checked', true);
                                            html += input.outerHTML;
                                            html += '</td>';
                                        } else if(field.type == 3){
                                            html += ('<td class="iw400-right-bdr">' + field.label + '</td>');
                                            html += '<td class="w400-green-bdr">';
                                            let options = field.options ? field.options.split(",") : [];
                                            var input = document.createElement('select');
                                            input.setAttribute('data-idx', field.idx);
                                            input.setAttribute('data-pdffield', field.pdffield);
                                            input.setAttribute('data-fileid', id);
                                            input.setAttribute('data-filename', filename);
                                            input.setAttribute('id', field.htmlfield);
                                            options.forEach(function(ostr) {
                                                if(defaultvalue == ostr)
                                                    input.setAttribute('selected', true);
                                                var option_el = document.createElement('option');
                                                option_el.appendChild(document.createTextNode(ostr));
                                                option_el.setAttribute('value', ostr);
                                                input.appendChild(option_el);
                                            });
                                            input.setAttribute('class', 'permit select-' + field.htmlfield);
                                            html += input.outerHTML;
                                            html += '</td>';
                                        } else if(field.type == 4){
                                            html += ('<td class="iw400-right-bdr">' + field.label + '</td>');
                                            html += '<td class="w400-yellow-bdr">';
                                            var textarea = document.createElement('textarea');
                                            textarea.setAttribute('data-idx', field.idx);
                                            textarea.setAttribute('data-pdffield', field.pdffield);
                                            textarea.setAttribute('data-fileid', id);
                                            textarea.setAttribute('data-filename', filename);
                                            textarea.setAttribute('class', 'permit txt-' + field.htmlfield);
                                            textarea.setAttribute('id', field.htmlfield);
                                            textarea.appendChild(document.createTextNode(defaultvalue));
                                            html += textarea.outerHTML;
                                            html += '</td>';
                                        }
                                        html += '</tr>';
                                        
                                        $("#permit-info-table-" + id + " tbody").append(html);
                                    }
                                });
                                resolve(true);
                            } else
                                resolve(false);
                        },
                        error: function(xhr, status, error) {
                            resolve(false);
                        }
                    });
                    
                } else 
                    resolve(false);
            },
            error: function(xhr, status, error) {
                res = JSON.parse(xhr.responseText);
                message = res.message;
                swal.fire({ title: "Error",
                        text: message == "" ? "Error happened while processing. Please try again later." : message,
                        icon: "error",
                        confirmButtonText: `OK` });
                resolve(false);
            }
        });
    });
}

async function openPermitTab(id, filename, tabname, permitData = null, openTab = false, formType = 1){
    if($("#permitTab_" + id).length)
        $("#permitTab_" + id).remove();
    if($("#tab_permit_" + id).length)
        $("#tab_permit_" + id).remove();
    
    if(formType == 1){
        $("#permitTab").after('<button class="tablinks permit" onclick="openRfdTab(event, \'tab_permit_' + id + '\')" id="permitTab_' + id + '">' + tabname + '</button>');
        $("#tab_permit").after('<div id="tab_permit_' + id + '" class="rfdTabContent permit" style="position:relative;"></div>');
    }
    else {
        $("#pilTab").after('<button class="tablinks permit" onclick="openRfdTab(event, \'tab_permit_' + id + '\')" id="permitTab_' + id + '">' + tabname + '</button>');
        $("#tab_PIL").after('<div id="tab_permit_' + id + '" class="rfdTabContent permit" style="position:relative;"></div>');
    }
    
    swal.fire({ title: "Please wait...", showConfirmButton: false });
    swal.showLoading();
    await buildPermitFields(id, filename);

    $(`#tab_permit_${id} .permit`).each(function(){
        var classes = $(this).attr('class').split(' ');
        var className = "." + classes[1];
        
        $(className).each(function() {
            if($(this).attr('data-fileid') != id){
                $(`#tab_permit_${id} ${className}`).val($(this).val());
            }
        });
    })

    $(`#tab_permit_${id} .permit`).on('change', function(obj) {
        var classes = $(obj.target).attr('class').split(' ');
        var className = "." + classes[1];
        $(className).each(function() {
            if(this == obj.target)
                updatePermitPDF($(this).attr('data-fileid'), $(this).attr('data-filename'));
            else if($(this).val() != $(obj.target).val()){
                $(this).val($(obj.target).val());
                updatePermitPDF($(this).attr('data-fileid'), $(this).attr('data-filename'));
            }
        });
    });

    if(permitData){
        $('#permit-info-table-' + id + ' input:enabled').each(function() { 
            var property = $(this).attr('id');
            $(this).val(permitData[property]);
        });
        $('#permit-info-table-' + id + ' select:enabled').each(function() { 
            var property = $(this).attr('id');
            $(this).val(permitData[property]);
        });
        $('#permit-info-table-' + id + ' textarea:enabled').each(function() { 
            var property = $(this).attr('id');
            $(this).val(permitData[property]);
        });
    }

    updatePermitPDF(id, filename);
    // if (filename == "ucc_f100_cpa.pdf") {
    //     $("#tab_permit_" + id).append($("#ucc_f100"));
    //     document.getElementById("ucc_f100").style.display = "block";
    //     updateUccF100(1, "ucc_f100_cpa.pdf");
    // }

    // if (filename == "ucc_f110_bldg.pdf") {
    //     $("#tab_permit_" + id).append($("#ucc_f110"));
    //     document.getElementById("ucc_f110").style.display = "block";
    //     updateUccF110(2, "ucc_f110_bldg.pdf");
    // }

    // if (filename == "ucc_f120_elec.pdf") {
    //     $("#tab_permit_" + id).append($("#ucc_f120"));
    //     document.getElementById("ucc_f120").style.display = "block";
    //     updateUccF120(3, "ucc_f120_elec.pdf");
    // }

    // if (filename == "PA ucc-3.pdf") {
    //     $("#tab_permit_" + id).append($("#pa_ucc3"));
    //     document.getElementById("pa_ucc3").style.display = "block";
    //     updateUcc3(4, "PA ucc-3.pdf");
    // }

    // if (filename == "ucc_f140_fire.pdf") {
    //     $("#tab_permit_" + id).append($("#ucc_f140"));
    //     document.getElementById("ucc_f140").style.display = "block";
    //     updateUccF140(5, "ucc_f140_fire.pdf");
    // }
    
    if(openTab){
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("rfdTabContent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById("tab_permit_" + id).style.display = "block";
        document.getElementById("permitTab_" + id).className += " active";
    }
    swal.close();
}

function updatePermitPDF(id, filename){
    if(!id || !filename)
        return;
    var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
    var script = $("<script>");

    fetch(pdfsrc)
    .then(function(response) {
        return response.arrayBuffer()
    })
    .then(function(buf) {
        var list_form = document.querySelector('#permit-info-table-' + id);
        var fields = {};
        list_form.querySelectorAll('input,select,textarea').forEach(function(input) {
            if ((input.getAttribute('type') === 'radio') && !input.checked) {
                return;
            }

            var key = input.getAttribute('data-pdffield');
            if (!fields[key]) {
                fields[key] = [];
            }
            var index = parseInt(input.getAttribute('data-idx'), 10);
            var value = (input.getAttribute('type') === 'checkbox') ? input.checked : input.value;
            
            fields[key][index] = value;
        });

        var filled_pdf; // Uint8Array
        try {
            filled_pdf = pdfform().transform(buf, fields);
        } catch (e) {
            console.log(e);
            return;
        }

        $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([filled_pdf], {
            type: "application/pdf"
        })) + "#toolbar=0");
    }, function(err) {
        console.log(err);
    });
}

// function updateUccF100(id, filename) {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     $.ajax({
//         url:"getCompanyInfo",
//         type:'post',
//         data:{state: $('#option-state').val()},
//         success: function(res){
//             if(res.success){
//                 var companyInfo = res.company;
//                 var permitInfo = res.permit;
//                 var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
//                 var script = $("<script>");

//                 fetch(pdfsrc)
//                 .then(function(response) {
//                     return response.arrayBuffer()
//                 })
//                 .then(function(data) {
//                     var buildingCost = 0;
//                     var elecCost = 0;
//                     var wetlands = ['', ''], ownerShipFee = ['', ''];

//                     if ($("#txt-building-cost").val()!="") buildingCost = parseFloat($("#txt-building-cost").val());
//                     if ($("#txt-elec-cost").val()!="") elecCost = parseFloat($("#txt-elec-cost").val());

//                     if ($("#owner-ship-fee").val() == 1) ownerShipFee[0] = 'X';
//                     else ownerShipFee[1] = 'X';

//                     if ($("#character-wetlands").val() == 1) wetlands[0] = 'X';
//                     else wetlands[1] = 'X';

//                     var totalCost = parseFloat(buildingCost + elecCost);

//                     var fields = {
//                         'Block': [$("#txt-block").val()],
//                         'Lot': [$("#txt-lot").val()],
//                         'Qualifier':[$("#txt-qualifier").val()],
//                         'Permit No':[$("#txt-permit-no").val()],
//                         'Address Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Proposed Work Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],

//                         'Owner in Fee': [$("#txt-project-name").val()],
//                         'Owner Address': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Owner Telephone':[$("#txt-owner-tel").val()],
//                         'Owner eMail':[$("#txt-owner-email").val()],

//                         'Public':[ownerShipFee[0]],
//                         'Private':[ownerShipFee[1]],

//                         'Princ Contractor': [companyInfo.company_name],
//                         'Contractor Phone': [companyInfo.company_telno],
//                         'Contractor Address 1': [companyInfo.company_address],

//                         'Number Stories':[$("#txt-character-stories").val()],
//                         'Height':[$("#txt-character-height").val()],
//                         'Area Lgst Fl':[$("#txt-character-area").val()],
//                         'New Bldg Area':[$("#txt-character-newarea").val()],
//                         'Volume':[$("#txt-character-volume").val()],
//                         'Max Live Load':[$("#txt-character-maxlive").val()],
//                         'Max Occupancy':[$("#txt-character-maxoccupancy").val()],
//                         'Indus Bldg -IBC':[$("#txt-character-approved").val()],
//                         'Indus Bldg -HUD':[$("#txt-character-hud").val()],
//                         'Land Area Disturbed':[$("#txt-character-disturbed").val()],
//                         'Flood Haz Zone':[$("#txt-character-flood").val()],
//                         'Base Flood Elev':[$("#txt-character-base").val()],
//                         'Wetlands -Yes':[wetlands[0]],
//                         'Wetlands -No':[wetlands[1]],
                        
//                         'Architect-Engineer': ['Richard Pantel, P.E.'],
//                         'Architect Address': ['35091 Paxson Road, Round Hill, VA 20141'],
//                         'Architect eMail': ['rpantel@princeton-engineering.com'],
//                         'Architect Tel': ['908-507-5500'],
//                         'Architect Fax': ['877-455-5641'],

//                         'Alteration': [1],
//                         'Check Box16': [1],
//                         'Check Box29': [1],
//                         'Bldg Est Cost':[$("#txt-building-cost").val()],
//                         'Elec Est Cost':[$("#txt-elec-cost").val()],
//                         'Total Est Cost':["$" + totalCost],

//                         'Res Use Descrip':[$("#txt-state-specific").val()],
//                         'Res Use Proposed':[$("#txt-use-group").val()],

//                         'Agent Name': [companyInfo.company_name],
//                         'Agent Address': [companyInfo.company_address],
//                         'Agent Tel': [companyInfo.company_telno],
//                     };

//                     if(permitInfo){
//                         fields['Responsible Person'] = [permitInfo.contact_person],
//                         fields['Resp Pers Tel'] = [permitInfo.contact_phone],
//                         fields['Resp Pers Fax'] = [permitInfo.FAX],

//                         fields['Contractor eMail'] = [permitInfo.construction_email];
//                         fields['Contractor License'] = [permitInfo.registration];
//                         fields['License Expire'] = [permitInfo.exp_date];
//                         fields['FEID'] = [permitInfo.EIN];
//                         fields['Contractor Fax'] = [permitInfo.FAX];
//                     }
//                     var out_buf = pdfform().transform(data, fields);

//                     $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([out_buf], {
//                         type: "application/pdf"
//                     })) + "#toolbar=0");
//                 }, function(err) {
//                     console.log(err);
//                 });
//             }
//         }
//     });
// }

// function updateUccF110(id, filename) {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     $.ajax({
//         url:"getCompanyInfo",
//         type:'post',
//         data:{state: $('#option-state').val()},
//         success: function(res){
//             if(res.success){
//                 var companyInfo = res.company;
//                 var permitInfo = res.permit;
//                 var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
//                 var script = $("<script>");

//                 fetch(pdfsrc)
//                 .then(function(response) {
//                     return response.arrayBuffer()
//                 })
//                 .then(function(data) {
//                     var buildingCost = 0;
//                     var elecCost = 0;
                    
//                     if ($("#txt-building-cost").val()!="") buildingCost = parseFloat($("#txt-building-cost").val());
//                     if ($("#txt-rehabil-cost").val()!="") elecCost = parseFloat($("#txt-rehabil-cost").val());

//                     var totalCost = parseFloat(buildingCost + elecCost);

//                     var fields = {
//                         'Block': [$("#txt-block").val()],
//                         'Lot': [$("#txt-lot").val()],
//                         'Qualifier':[$("#txt-qualifier").val()],
//                         'Work Site1': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Address Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Proposed Work Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],

//                         'Owner in Fee': [$("#txt-project-name").val()],
//                         'Owner Address': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Owner Tel':[$("#txt-owner-tel").val()],
//                         'Owner eMail':[$("#txt-owner-email").val()],
                        
//                         'Contractor Name': [companyInfo.company_name],
//                         'Contractor Tel': [companyInfo.company_telno],
//                         'Contractor Address': [companyInfo.company_address],

//                         'Use Grp -Pres':[$("#txt-use-group-present").val()],
//                         'Use Grp -Prop':[$("#txt-use-group-proposed").val()],
//                         'No Stories':[$("#txt-character-stories").val()],
//                         'Height':[$("#txt-character-height").val()],
//                         'Area -Lgst Fl':[$("#txt-character-area").val()],
//                         'Area -New Bldg':[$("#txt-character-newarea").val()],
//                         'Volume':[$("#txt-character-volume").val()],
//                         'Max Live Load':[$("#txt-character-maxlive").val()],
//                         'Max Occupancy':[$("#txt-character-maxoccupancy").val()],

//                         'Constr Class -Pres':[$("#txt-const-group-present").val()],
//                         'Constr Class -Prop':[$("#txt-const-group-proposed").val()],
//                         'Indus Bldg -State': [$("#txt-character-approved").val()],
//                         'Indus Bld -HUD':[$("#txt-character-hud").val()],
//                         'Est Val -New Bldg':[$("#txt-building-cost").val()],
//                         'Est Val -Rehab':[$("#txt-rehabil-cost").val()],
//                         'Est Val Total':["$" + totalCost],
//                         'Contractor Print Name':[$("#txt-print-name").val()],
//                         'Description of Work' : [$("#txt-site-data").val()],

//                         'Other': [1],
//                         'Other Descrip': ['Solar System'],
//                     };

//                     if(permitInfo){
//                         fields['Responsible Person'] = [permitInfo.contact_person],
//                         fields['Resp Pers Tel'] = [permitInfo.contact_phone],
//                         fields['Resp Pers Fax'] = [permitInfo.FAX],

//                         fields['Contractor eMail'] = [permitInfo.construction_email];
//                         fields['License or Bldr Reg'] = [permitInfo.registration];
//                         fields['Expiration'] = [permitInfo.exp_date];
//                         fields['FEID'] = [permitInfo.EIN];
//                         fields['Contractor Fax'] = [permitInfo.FAX];
//                     }
//                     var out_buf = pdfform().transform(data, fields);

//                     $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([out_buf], {
//                         type: "application/pdf"
//                     })) + "#toolbar=0");
//                 }, function(err) {
//                     console.log(err);
//                 });
//             }
//         }
//     });
// }

// function updateUccF120(id, filename) {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     $.ajax({
//         url:"getCompanyInfo",
//         type:'post',
//         data:{state: $('#option-state').val()},
//         success: function(res){
//             if(res.success){
//                 var companyInfo = res.company;
//                 var permitInfo = res.permit;
//                 var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
//                 var script = $("<script>");

//                 fetch(pdfsrc)
//                 .then(function(response) {
//                     return response.arrayBuffer()
//                 })
//                 .then(function(data) {
//                     var buildingCost = 0;
//                     var elecCost = 0;
                    
//                     if ($("#txt-bldg-cost").val()!="") buildingCost = parseFloat($("#txt-bldg-cost").val());
//                     if ($("#txt-rehabil-cost").val()!="") elecCost = parseFloat($("#txt-rehabil-cost").val());

//                     var totalCost = parseFloat(buildingCost + elecCost);

//                     var fields = {
//                         'Block': [$("#txt-block").val()],
//                         'Lot': [$("#txt-lot").val()],
//                         'Qualifier':[$("#txt-qualifier").val()],
//                         'Address Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Owner Address 2': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],

//                         'Owner': [$("#txt-project-name").val()],
//                         'Owner Address': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Owner Tel':[$("#txt-owner-tel").val()],
//                         'Owner eMail':[$("#txt-owner-email").val()],

//                         'Contractor': [companyInfo.company_name],

//                         'Contractor Tel': [companyInfo.company_telno],
//                         'Contractor Address 1': [companyInfo.company_address],

//                         'Res Use Present':[$("#txt-use-group-present").val()],
//                         'Res Use Proposed':[$("#txt-use-group-proposed").val()],
//                         'Occupied':[$("#txt-building-occupied").val()],
//                         'utilityco':[$("#txt-utility-co").val()],
//                         "Est'd Cost of Wk":[$("#txt-elec-cost").val()],
//                         'Total': [0],

//                         'Qty -Trans-Gen':[$("#txt-kw-qty").val()],
//                         'Sz -Trans-Gen':[$("#txt-kw-size").val()],
//                         'Qty -Service':[$("#txt-amp-qty").val()],
//                         'Sz -Service':[$("#txt-amp-size").val()],
//                         'Qty -SubPanels':[$("#txt-subpanels-qty").val()],
//                         'Sz -Subpanels':[$("#txt-subpanels-size").val()],
//                         'Qty -Motor Cntrl Cntr':[$("#txt-motor-qty").val()],
//                         'Sz -Motor Cntrl Cntr':[$("#txt-motor-size").val()],
//                         'Qty -Elec Sign':[$("#txt-light-qty").val()],
//                         'Sz -Elec Sign':[$("#txt-light-size").val()],

//                         'Qty -Other3':[$("#txt-solar-panel-qty").val()],
//                         'Sz -Other3':[$("#txt-solar-panel-size").val()],
//                         'Other3 Descrip': ["Solar Panels"],
//                     };

//                     if(permitInfo){
//                         fields['Responsible Person'] = [permitInfo.contact_person],
//                         fields['Resp Pers Tel'] = [permitInfo.contact_phone],
//                         fields['Resp Pers Fax'] = [permitInfo.FAX],
//                         fields['Print Name'] = [permitInfo.contact_person],
//                         fields['Contractor eMail'] = [permitInfo.construction_email];
//                         fields['Contractor License'] = [permitInfo.registration];
//                         fields['License Expire'] = [permitInfo.exp_date];
//                         fields['FEID'] = [permitInfo.EIN];
//                         fields['Contractor Fax'] = [permitInfo.FAX];
//                     }
//                     var out_buf = pdfform().transform(data, fields);

//                     $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([out_buf], {
//                         type: "application/pdf"
//                     })) + "#toolbar=0");
//                 }, function(err) {
//                     console.log(err);
//                 });
//             }
//         }
//     });
// }

// function updateUcc3(id, filename) {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     $.ajax({
//         url:"getCompanyInfo",
//         type:'post',
//         data:{state: $('#option-state').val()},
//         success: function(res){
//             if(res.success){
//                 var companyInfo = res.company;
//                 var permitInfo = res.permit;
//                 var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
//                 var script = $("<script>");

//                 fetch(pdfsrc)
//                 .then(function(response) {
//                     return response.arrayBuffer()
//                 })
//                 .then(function(data) {
//                     var fee1 = 0, fee2 = 0, fee3=0, fee4 = 0, totalFee = 0;
//                     if ($("#txt-fee-1").val()!="") fee1 = parseFloat($("#txt-fee-1").val());
//                     if ($("#txt-fee-2").val()!="") fee2 = parseFloat($("#txt-fee-2").val());
//                     if ($("#txt-fee-3").val()!="") fee3 = parseFloat($("#txt-fee-3").val());
//                     if ($("#txt-fee-4").val()!="") fee4 = parseFloat($("#txt-fee-4").val());
//                     totalFee = parseFloat(fee1 + fee2 + fee3 + fee4);

//                     var fields = {
//                         'Site Facility name':[companyInfo.company_name],
//                         'Site Building and or Tenant Name':[$("#txt-project-name").val()],
//                         'Site Street Number and Name': [$("#txt-street-address").val()],
//                         'Site City': [$("#txt-city").val()],
//                         'Site State': [$('#option-state').val()],
//                         'Site ZIP Code': [$("#txt-zip").val()],
//                         'Site Political Subdivision': [$("#txt-political-subdivision").val()],
//                         'Site County': [$("#txt-county").val()],

//                         'App Alteration':['Yes'],
//                         'ManDocs01':['Yes'],
//                         'ManDocs02': ['Yes'],
//                         'ManDocs03': ['Yes'],

//                         'CB01N':['Yes'],
//                         'CB03Y':['Yes'],
//                         'CB04N':['Yes'],
//                         'CB05N':['Yes'],
//                         'CB06N':['Yes'],
//                         'CB07N':['Yes'],
                        
//                         'Number of stories above grade':[$("#txt-character-stories").val()],
//                         'total floor area':[$("#txt-character-area").val()],
//                         'Floor area new construction sq ft':[$("#txt-character-newarea").val()],
//                         'Floor area of addition sq ft':[$("#txt-character-volume").val()],
//                         'Floor area renovated sq ft':[$("#txt-character-renovated").val()],
//                         'Estimated cost of construction':[$("#txt-building-cost").val()],
                        
//                         'Design Professional Name': ['Richard Pantel, P.E.'],
//                         'Design Professional Address': ['35091 Paxson Road, Round Hill, VA 20141'],
//                         'Design Professional License#': ['PE039453R'],
//                         'Design Professional Email': ['rpantel@princeton-engineering.com'],
//                         'Design Professional Phone': ['908-507-5500'],
//                         'Design Professional FAX - include area code': ['877-455-5641'],

//                         'Owner Name': [$("#txt-project-name").val()],
//                         'Owner Street Address': [$("#txt-street-address").val()],
//                         'Owner City': [$("#txt-city").val()],
//                         'Owner State': [$('#option-state').val()],
//                         'Owner ZIP Code': [$("#txt-zip").val()],
//                         'Owner Phone - include area code':[$("#txt-owner-tel").val()],

//                         'List total sq ft of floor area': [$("#txt-total-floor-area").val()],
//                         'cost 1':[$("#txt-fee-1").val()],
//                         'cost 2':[$("#txt-fee-2").val()],
//                         'cost 6':[$("#txt-fee-3").val()],
//                         'cost  7':[$("#txt-fee-4").val()],
//                         'Total Fees':[$("#txt-fee-5").val()],

//                         'Applicant City': [$("#txt-city").val()],
//                         'Contractor Tel': [companyInfo.company_telno],
//                         'Applicant State':[$('#option-state').val()],
//                         'Applicant ZIP Code':[$("#txt-zip").val()],
//                         'Applicant Street Address': [companyInfo.company_address],
//                     };

//                     if(permitInfo){
//                         fields['Applicant Name'] = [permitInfo.contact_person],
//                         fields['Applicant Phone - include area code'] = [permitInfo.contact_phone],
//                         fields['Resp Pers Fax'] = [permitInfo.FAX],
//                         fields['Applicant Email'] = [permitInfo.construction_email];
//                     }
//                     var out_buf = pdfform().transform(data, fields);

//                     $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([out_buf], {
//                         type: "application/pdf"
//                     })) + "#toolbar=0");
//                 }, function(err) {
//                     console.log(err);
//                 });
//             }
//         }
//     });
// }

// function updateUccF140(id, filename) {
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     $.ajax({
//         url:"getCompanyInfo",
//         type:'post',
//         data:{state: $('#option-state').val()},
//         success: function(res){
//             if(res.success){
//                 var companyInfo = res.company;
//                 var permitInfo = res.permit;
//                 var pdfsrc = $("#assetPdfLink").val() + '/' + filename;
//                 var script = $("<script>");

//                 fetch(pdfsrc)
//                 .then(function(response) {
//                     return response.arrayBuffer()
//                 })
//                 .then(function(data) {
//                     var fireCost = 0;
//                     if ($("#txt-fire-protection-cost").val()!="") fireCost = parseFloat($("#txt-fire-protection-cost").val());

//                     var fields = {
//                         'Block': [$("#txt-block").val()],
//                         'Lot': [$("#txt-lot").val()],
//                         'Qualifier':[$("#txt-qualifier").val()],
//                         'Proposed Work Site': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],

//                         'Owner in Fee': [$("#txt-project-name").val()],
//                         'Owner Address': [$("#txt-street-address").val() + ", " +  $("#txt-city").val() + ", " + $("#txt-zip").val()],
//                         'Owner Telephone':[$("#txt-owner-tel").val()],
//                         'Owner eMail':[$("#txt-owner-email").val()],

//                         'Contractor': [companyInfo.company_name],
//                         'Contractor Phone': [companyInfo.company_telno],
//                         'Contractor Address 1': [companyInfo.company_address],
//                         'HIC Reg or Exempt': [$("#txt-contractor-registration-no").val()],
//                         'Device Total': ["0"],
//                         'Total Est Cost':[fireCost],
//                         'Cert Contr': ['Yes'],
//                     };

//                     if(permitInfo){
//                         fields['Contractor eMail'] = [permitInfo.construction_email];
//                         fields['Contractor License'] = [permitInfo.registration];
//                         fields['License Expire'] = [permitInfo.exp_date];
//                         fields['FEID'] = [permitInfo.EIN];
//                         fields['Contractor Fax'] = [permitInfo.FAX];

//                         fields['Print Name'] = [permitInfo.contact_person];
//                     }
//                     var out_buf = pdfform().transform(data, fields);

//                     $("#permitViewer_" + id).attr("src", URL.createObjectURL(new Blob([out_buf], {
//                         type: "application/pdf"
//                     })) + "#toolbar=0");
//                 }, function(err) {
//                     console.log(err);
//                 });
//             }
//         }
//     });
// }

function savePermit(id, filename){
    var pdfsrc = $("#permitViewer_" + id).attr('src');
    fetch(pdfsrc)
    .then(function(response) {
        return response.arrayBuffer()
    })
    .then(async function(data) {
        submitPermitJson(id, filename);
        submitPdfData(id, filename, data, 0);
    });
}

var getPermitData = function(id, filename) {
    var alldata = {};

    $('#inputform-first input:text:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });

    $('#permit-info-table-' + id + ' input:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    $('#permit-info-table-' + id + ' select:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    $('#permit-info-table-' + id + ' textarea:enabled').each(function() { 
        alldata[$(this).attr('id')] = $(this).val();
    });
    // if (filename == "ucc_f100_cpa.pdf") {
    //     $('#ucc_f100 input:text:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    //     $('#ucc_f100 select:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    // }

    // if (filename == "ucc_f110_bldg.pdf") {
    //     $('#ucc_f110 input:text:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    //     $('#ucc_f110 select:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    // }

    // if (filename == "ucc_f120_elec.pdf") {
    //     $('#ucc_f120 input:text:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    //     $('#ucc_f120 select:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    // }
    // if (filename == "ucc_f140_fire.pdf") {
    //     $('#ucc_f140 input:text:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    //     $('#ucc_f140 select:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    // }

    // if (filename == "PA ucc-3.pdf") {
    //     $('#pa_ucc3 input:text:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    //     $('#pa_ucc3 select:enabled').each(function() { 
    //         alldata[$(this).attr('id')] = $(this).val();
    //     });
    // }
    return alldata;
}

function submitPermitJson(id, filename) {
    var data = getPermitData(id, filename);
    //swal.fire({ title: "Please wait...", showConfirmButton: false });
    //swal.showLoading();
    $.ajax({
        url:"submitPermitInput",
        type:'post',
        data:{data: data, filename: filename},
        success:function(res){
            //swal.close();
            if (res.status == true) {
                // swal.fire({
                //     title: "Success",
                //     text: res.message,
                //     icon: "success",
                //     showCancelButton: true,
                // })
                // .then(( result ) => {
                // });
            } else {
                // error handling
                swal.fire({ title: "Error",
                    text: res.message,
                    icon: "error",
                    confirmButtonText: `OK` });
            }
        },
        error: function(xhr, status, error) {
            //swal.close();
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