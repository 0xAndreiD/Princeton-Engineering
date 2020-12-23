<script>
window.conditionId = 1;

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

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();

function fcChangeType( conditionId, type ){
    if( type == 1 )
    {
        $(`#inputform-${conditionId} #label-A-1`).attr('rowspan', 7);
        $(`#inputform-${conditionId} #label-B-1`)[0].style.display = "none";
        $(`#inputform-${conditionId} #title-B-3`)[0].style.display = "table-cell";
        var elements = $(`#inputform-${conditionId} .class-truss-hide`);
        for(let i = 0; i < elements.length; i ++)
        {
            elements[i].style.display = 'none';
        }
        $(`#inputform-${conditionId} #label-A-9`)[0].innerHTML = 'Rise from Truss Plate to Top Ridge';
        $(`#inputform-${conditionId} #label-A-10`)[0].innerHTML = 'Horiz Len from Outside of Truss Plate to Ridge';
        $(`#inputform-${conditionId} #label-A-11`)[0].innerHTML = 'Diagonal Overhang Length past Truss Plate';
        $(`#inputform-${conditionId} #label-B-3`)[0].innerHTML = 'Truss Spacing - Center to Center';
        $(`#inputform-${conditionId} #label-B-4`)[0].innerHTML = 'Truss Material';
        $(`#inputform-${conditionId} #label-F-1`)[0].innerHTML = 'Maximum # Modules along Truss';
        document.getElementById(`trussInput-${conditionId}`).style.display = "block";
    }    
    else
    {
        $(`#inputform-${conditionId} #label-A-1`).attr('rowspan', 11);
        $(`#inputform-${conditionId} #label-B-1`)[0].style.display = "table-cell";
        $(`#inputform-${conditionId} #title-B-3`)[0].style.display = "none";
        var elements = $(`#inputform-${conditionId} .class-truss-hide`);
        for(let i = 0; i < elements.length; i ++)
        {
            elements[i].style.display = 'table-row';
        }
        $(`#inputform-${conditionId} #label-A-9`)[0].innerHTML = 'Rise from Rafter Plate to Top Ridge';
        $(`#inputform-${conditionId} #label-A-10`)[0].innerHTML = 'Horiz Len from Outside of Rafter Plate to Ridge';
        $(`#inputform-${conditionId} #label-A-11`)[0].innerHTML = 'Diagonal Overhang Length past Rafter Plate';
        $(`#inputform-${conditionId} #label-B-3`)[0].innerHTML = 'Joist Spacing - Center to Center';
        $(`#inputform-${conditionId} #label-B-4`)[0].innerHTML = 'Rafter Material';
        $(`#inputform-${conditionId} #label-F-1`)[0].innerHTML = 'Maximum # Modules along Rafter';
        document.getElementById(`trussInput-${conditionId}`).style.display = "none";
    }
}

function maxModuleNumChange( conditionId ){
    var moduleNum = $(`#inputform-${conditionId} #f-1-1`).val();
    let i;
    $(`#inputform-${conditionId} #Module-Left-Text`).attr('rowspan', Math.min(moduleNum, 12));
    for(i = 1; i <= 12 && i <= moduleNum; i ++)
    {
        $(`#inputform-${conditionId} #Module-${i}`)[0].style.display = "table-row";
    }
    for(; i <= 12; i ++)
    {
        $(`#inputform-${conditionId} #Module-${i}`)[0].style.display = "none";
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

    var angleRadian = degreeToRadian( parseFloat($(`#inputform-${condId} #txt-roof-degree`).val()) );
    var overhangLength = parseFloat($(`#inputform-${condId} #a-11-1`).val());
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

var adjustDrawingPanel = function( condId ) {
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

    var angle = parseFloat($(`#inputform-${condId} #txt-roof-degree`).val());
    var angleRadian = degreeToRadian(angle);
    var overhangX = parseFloat($(`#inputform-${condId} #a-11-1`).val()) * Math.sin(Math.PI / 2 - angleRadian);
    var overhangY = parseFloat($(`#inputform-${condId} #a-11-1`).val()) * Math.sin(angleRadian );

    topXPoint += overhangX;
    topYPoint += overhangY;
    
    var moduleDepth = 1.17 / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
    var moduleGap = parseFloat($(`#inputform-${condId} #g-1-1`).val()) / 12;

    var moduleCount = parseInt($(`#inputform-${condId} #f-1-1`).val());
    
    var moduleLengthSum = parseFloat($(`#inputform-${condId} #e-1-1`).val());
    var orientation = false;
    for(let i = 1; i <= moduleCount; i ++)
    {
        orientation = false;
        if($(`#inputform-${condId} #a-6-1`).val() == "Portrait")
            orientation = true;
        if($(`#inputform-${condId} #h-${i}-1`)[0].checked)
            orientation = !orientation;

        moduleLengthSum += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
    }

    // Show alert when module length is longer
    if(topXPoint < moduleLengthSum * Math.cos(angleRadian) || topYPoint < moduleLengthSum * Math.sin(angleRadian))
        $(`#inputform-${condId} #truss-module-alert`).css('display', 'block');
    else
        $(`#inputform-${condId} #truss-module-alert`).css('display', 'none');

    topXPoint = Math.max(topXPoint, moduleLengthSum * Math.cos(angleRadian));
    topYPoint = Math.max(topYPoint, moduleLengthSum * Math.sin(angleRadian));
    
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
    adjustDrawingPanel(condId);
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

    // draw di­agonals lines 
    var index = 0;
    for (var key in globalDiagnoal1Lines[condId]) {
        var bAllow = !($(`#inputform-${condId} #diag-1-` + (index+1)).is(":checked"));
        if (bAllow == true) {
            drawLine(condId, globalDiagnoal1Lines[condId][key][0], globalDiagnoal1Lines[condId][key][1], "M"+label_index);
        }
        label_index++;

        index++;
    }

    index = 0;
    for (var key in globalDiagnoal2Lines[condId]) {
        var bAllow = !($(`#inputform-${condId} #diag-2-` + (index+1)).is(":checked"));
        if (bAllow == true) {
            var bReverse = ($(`#inputform-${condId} #diag-2-reverse-` + (index+1)).is(":checked"));
            if(bReverse && globalDiagnoal2ReverseLines[condId][key])
                drawLine(condId, globalDiagnoal2ReverseLines[condId][key][0], globalDiagnoal2ReverseLines[condId][key][1], "M"+label_index);
            else
                drawLine(condId, globalDiagnoal2Lines[condId][key][0], globalDiagnoal2Lines[condId][key][1], "M"+label_index);
        }
        label_index++;
        
        index++;
    }

    // Draw Overhang
    var angle = parseFloat($(`#inputform-${condId} #txt-roof-degree`).val());
    var angleRadian = degreeToRadian(angle);
    
    var overhangLength = parseFloat($(`#inputform-${condId} #a-11-1`).val());
    var uphillDist = parseFloat($(`#inputform-${condId} #e-1-1`).val());

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

    // Draw solar rectangles
    var startModule = overhangLength - uphillDist;
    var moduleDepth = 1.17 / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
    var moduleGap = parseFloat($(`#inputform-${condId} #g-1-1`).val()) / 12;

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
    var moduleCount = parseInt($(`#inputform-${condId} #f-1-1`).val());
    
    let moduleStartX = 0;
    var orientation = false;

    var supportStart = parseFloat($(`#inputform-${condId} #e-2-1`).val()) - parseFloat($(`#inputform-${condId} #e-1-1`).val());

    ctx[condId].fillStyle = '#000';
    for(let i = 1; i <= moduleCount; i ++)
    {
        orientation = false;
        if($(`#inputform-${condId} #a-6-1`).val() == "Portrait")
            orientation = true;
        if($(`#inputform-${condId} #h-${i}-1`)[0].checked)
            orientation = !orientation;
        
        ctx[condId].strokeRect(moduleStartX * grid_size[condId], 0, (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)) * grid_size[condId], moduleDepth * grid_size[condId]);
        // Left Support
        ctx[condId].fillRect(moduleStartX * grid_size[condId] + supportStart * grid_size[condId] - 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 + 1 - moduleDepth * grid_size[condId]);
        // Right Support
        ctx[condId].fillRect(moduleStartX * grid_size[condId] + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)) * grid_size[condId] - (supportStart + 1 / 12) * grid_size[condId] + 1, moduleDepth * grid_size[condId], grid_size[condId] / 12, grid_size[condId] / 4 + 1 - moduleDepth * grid_size[condId]);

        moduleStartX += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
    }
    
    ctx[condId].rotate(angleRadian);
    ctx[condId].translate(- startPoint[0], - startPoint[1]);

    var overhangX = Math.floor(overhangLength * grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * grid_size[condId] * Math.sin(angleRadian));
    ctx[condId].translate(- overhangX, overhangY);
}

var preloaded_data = [];
var loadPreloadedData = function() {
    var projectId = $('#projectId').val();
    if(projectId >= 0){
        $.ajax({
            url:"getProjectJson",
            type:'post',
            data:{projectId: projectId},
            success:function(res){
                if(res.success == true) {
                    preloaded_data = JSON.parse(res.data);
                    console.log(preloaded_data);
                    try{
                        $('#txt-project-number').val(preloaded_data['ProjectInfo']['Number']);
                        $('#txt-project-name').val(preloaded_data['ProjectInfo']['Name']);
                        $('#txt-street-address').val(preloaded_data['ProjectInfo']['Street']);
                        $('#txt-city').val(preloaded_data['ProjectInfo']['City']);
                        $('#option-state').val(preloaded_data['ProjectInfo']['State']);
                        detectCorrectTownForMA();
                        $('#txt-zip').val(preloaded_data['ProjectInfo']['Zip']);

                        $('#txt-name-of-field-personnel').val(preloaded_data['Personnel']['Name']);
                        $('#date-of-field-visit').val(preloaded_data['Personnel']['DateOfFieldVisit']);
                        $('#date-of-plan-set').val(preloaded_data['Personnel']['DateOfPlanSet']);

                        $('#txt-building-age').val(preloaded_data['BuildingAge']);

                        $('#option-module-quantity').val(preloaded_data['Equipment']['PVModule']['Quantity']);
                        $('#option-inverter-quantity').val(preloaded_data['Equipment']['PVInverter']['Quantity']);

                        $("#option-number-of-conditions").val(preloaded_data['NumberLoadingConditions']);
                        updateNumberOfConditions(parseInt(preloaded_data['NumberLoadingConditions']));

                        for(let i = 0; i < preloaded_data['LoadingCase'].length; i ++)
                        {
                            let caseData = preloaded_data['LoadingCase'][i];
                            $(`#trussFlagOption-${i + 1}-1`).prop('checked', !caseData['TrussFlag']);
                            $(`#trussFlagOption-${i + 1}-2`).prop('checked', caseData['TrussFlag']);
                            fcChangeType(i + 1, caseData['TrussFlag']);
                            $(`#inputform-${i + 1} #a-2-1`).val(caseData['RoofDataInput']['A2']);
                            $(`#inputform-${i + 1} #a-3-1`).val(caseData['RoofDataInput']['A3']);
                            $(`#inputform-${i + 1} #a-4-1`).val(caseData['RoofDataInput']['A4']);
                            $(`#inputform-${i + 1} #a-5-1`).val(caseData['RoofDataInput']['A5']);
                            $(`#inputform-${i + 1} #a-6-1`).val(caseData['RoofDataInput']['A6']);
                            $(`#inputform-${i + 1} #a-7-1`).val(caseData['RoofDataInput']['A7']);
                            $(`#inputform-${i + 1} #a-8-1`).val(caseData['RoofDataInput']['A8']);
                            $(`#inputform-${i + 1} #a-9-1`).val(caseData['RoofDataInput']['A9']);
                            $(`#inputform-${i + 1} #a-10-1`).val(caseData['RoofDataInput']['A10']);
                            $(`#inputform-${i + 1} #a-11-1`).val(caseData['RoofDataInput']['A11']);

                            $(`#inputform-${i + 1} #b-1-1`).val(caseData['RafterDataInput']['B1']);
                            $(`#inputform-${i + 1} #b-2-1`).val(caseData['RafterDataInput']['B2']);
                            $(`#inputform-${i + 1} #b-3-1`).val(caseData['RafterDataInput']['B3']);
                            $(`#inputform-${i + 1} #b-4-1`).val(caseData['RafterDataInput']['B4']);

                            $(`#inputform-${i + 1} #c-1-1`).val(caseData['CollarTieInformation']['C1']);
                            $(`#inputform-${i + 1} #c-2-1`).val(caseData['CollarTieInformation']['C2']);
                            $(`#inputform-${i + 1} #c-3-1`).val(caseData['CollarTieInformation']['C3']);
                            $(`#inputform-${i + 1} #c-4-1`).val(caseData['CollarTieInformation']['C4']);

                            $(`#inputform-${i + 1} #d-1-1`).val(caseData['RoofDeckSurface']['D1']);
                            $(`#inputform-${i + 1} #d-2-1`).val(caseData['RoofDeckSurface']['D2']);
                            $(`#inputform-${i + 1} #d-3-1`).val(caseData['RoofDeckSurface']['D3']);

                            $(`#inputform-${i + 1} #e-1-1`).val(caseData['Location']['E1']);
                            $(`#inputform-${i + 1} #e-2-1`).val(caseData['Location']['E2']);
                            $(`#inputform-${i + 1} #e-3-1`).val(caseData['Location']['E3']);

                            $(`#inputform-${i + 1} #f-1-1`).val(caseData['NumberOfModules']['F1']);
                            maxModuleNumChange(i + 1);
                            $(`#inputform-${i + 1} #g-1-1`).val(caseData['NSGap']['G1']);
                            
                            $(`#inputform-${i + 1} #h-1-1`).prop('checked', caseData['RotateModuleOrientation']['H1']);
                            $(`#inputform-${i + 1} #h-2-1`).prop('checked', caseData['RotateModuleOrientation']['H2']);
                            $(`#inputform-${i + 1} #h-3-1`).prop('checked', caseData['RotateModuleOrientation']['H3']);
                            $(`#inputform-${i + 1} #h-4-1`).prop('checked', caseData['RotateModuleOrientation']['H4']);
                            $(`#inputform-${i + 1} #h-5-1`).prop('checked', caseData['RotateModuleOrientation']['H5']);
                            $(`#inputform-${i + 1} #h-6-1`).prop('checked', caseData['RotateModuleOrientation']['H6']);
                            $(`#inputform-${i + 1} #h-7-1`).prop('checked', caseData['RotateModuleOrientation']['H7']);
                            $(`#inputform-${i + 1} #h-8-1`).prop('checked', caseData['RotateModuleOrientation']['H8']);
                            $(`#inputform-${i + 1} #h-9-1`).prop('checked', caseData['RotateModuleOrientation']['H9']);
                            $(`#inputform-${i + 1} #h-10-1`).prop('checked', caseData['RotateModuleOrientation']['H10']);
                            $(`#inputform-${i + 1} #h-11-1`).prop('checked', caseData['RotateModuleOrientation']['H11']);
                            $(`#inputform-${i + 1} #h-12-1`).prop('checked', caseData['RotateModuleOrientation']['H12']);

                            $(`#inputform-${i + 1} #i-1-1`).val(caseData['Notes']['I1']);

                            let trussData = caseData['TrussDataInput'];
                            $(`#inputform-${i + 1} #option-roof-slope`).val(trussData['RoofSlope']['Type']);
                            $(`#inputform-${i + 1} #txt-roof-degree`).val(trussData['RoofSlope']['Degree']);
                            $(`#inputform-${i + 1} #td-unknown-degree1`).val(trussData['RoofSlope']['UnknownDegree']);
                            $(`#inputform-${i + 1} #td-calculated-roof-plane-length`).val(trussData['RoofSlope']['CalculatedRoofPlaneLength']);
                            $(`#inputform-${i + 1} #td-diff-between-measured-and-calculated`).val(trussData['RoofSlope']['td-diff-between-measured-and-calculated']);

                            $(`#inputform-${i + 1} #option-roof-member-type`).val(trussData['RoofPlane']['MemberType']);
                            updateRoofMemberType(i + 1, trussData['RoofPlane']['MemberType']);
                            $(`#inputform-${i + 1} #txt-length-of-roof-plane`).val(trussData['RoofPlane']['Length']);
                            $(`#inputform-${i + 1} #option-number-segments1`).val(trussData['RoofPlane']['NumberOfSegments']);
                            $(`#inputform-${i + 1} #option-number-segments1 > option`).each(function() { 
                                if ($(this).attr('data-value') == trussData['RoofPlane']['NumberOfSegments']) {
                                    $(this).attr('selected', true);
                                }
                                else {
                                    $(this).attr('selected', false);
                                }         
                            });
                            updateNumberSegment1(i + 1, parseInt(trussData['RoofPlane']['NumberOfSegments']));
                            $(`#inputform-${i + 1} #td-sum-of-length-entered`).val(trussData['RoofPlane']['SumOfLengthsEntered']);
                            $(`#inputform-${i + 1} #td-checksum-of-segment1`).val(trussData['RoofPlane']['ChecksumOfChordLength']);
                            if(trussData['RoofPlane']['LengthOfSegment1']) $(`#inputform-${i + 1} #txt-roof-segment1-length`).val(trussData['RoofPlane']['LengthOfSegment1']);
                            if(trussData['RoofPlane']['LengthOfSegment2']) $(`#inputform-${i + 1} #txt-roof-segment2-length`).val(trussData['RoofPlane']['LengthOfSegment2']);
                            if(trussData['RoofPlane']['LengthOfSegment3']) $(`#inputform-${i + 1} #txt-roof-segment2-length`).val(trussData['RoofPlane']['LengthOfSegment3']);
                            if(trussData['RoofPlane']['LengthOfSegment4']) $(`#inputform-${i + 1} #txt-roof-segment2-length`).val(trussData['RoofPlane']['LengthOfSegment4']);
                            if(trussData['RoofPlane']['LengthOfSegment5']) $(`#inputform-${i + 1} #txt-roof-segment2-length`).val(trussData['RoofPlane']['LengthOfSegment5']);
                            if(trussData['RoofPlane']['LengthOfSegment6']) $(`#inputform-${i + 1} #txt-roof-segment2-length`).val(trussData['RoofPlane']['LengthOfSegment6']);

                            $(`#inputform-${i + 1} #option-floor-member-type`).val(trussData['FloorPlane']['MemberType']);
                            updateFloorMemberType(i + 1, trussData['FloorPlane']['MemberType']);
                            $(`#inputform-${i + 1} #txt-length-of-floor-plane`).val(trussData['FloorPlane']['Length']);
                            $(`#inputform-${i + 1} #option-number-segments2`).val(trussData['FloorPlane']['NumberOfSegments']);
                            $(`#inputform-${i + 1} #option-number-segments2 > option`).each(function() { 
                                if ($(this).attr('data-value') == trussData['FloorPlane']['NumberOfSegments'])
                                    $(this).attr('selected', true);
                                else
                                    $(this).attr('selected', false);      
                            });
                            updateNumberSegment2(i + 1, parseInt(trussData['FloorPlane']['NumberOfSegments']));
                            $(`#inputform-${i + 1} #td-total-length-entered`).val(trussData['FloorPlane']['SumOfLengthsEntered']);
                            $(`#inputform-${i + 1} #td-checksum-of-segment2`).val(trussData['FloorPlane']['ChecksumOfChordLength']);
                            if(trussData['FloorPlane']['LengthOfSegment1']) $(`#inputform-${i + 1} #txt-floor-segment1-length`).val(trussData['FloorPlane']['LengthOfSegment1']);
                            if(trussData['FloorPlane']['LengthOfSegment2']) $(`#inputform-${i + 1} #txt-floor-segment2-length`).val(trussData['FloorPlane']['LengthOfSegment2']);
                            if(trussData['FloorPlane']['LengthOfSegment3']) $(`#inputform-${i + 1} #txt-floor-segment2-length`).val(trussData['FloorPlane']['LengthOfSegment3']);
                            if(trussData['FloorPlane']['LengthOfSegment4']) $(`#inputform-${i + 1} #txt-floor-segment2-length`).val(trussData['FloorPlane']['LengthOfSegment4']);
                            if(trussData['FloorPlane']['LengthOfSegment5']) $(`#inputform-${i + 1} #txt-floor-segment2-length`).val(trussData['FloorPlane']['LengthOfSegment5']);
                            if(trussData['FloorPlane']['LengthOfSegment6']) $(`#inputform-${i + 1} #txt-floor-segment2-length`).val(trussData['FloorPlane']['LengthOfSegment6']);

                            for(let j = 0; j < caseData['Diagonal1'].length; j ++){
                                $(`#inputform-${i + 1} #diag-1-${j + 1}`).prop('checked', !caseData['Diagonal1'][j]['include']);
                                $(`#inputform-${i + 1} #option-diagonals-mem1-${j + 1}-type`).val(caseData['Diagonal1'][j]['memType']);
                                $(`#inputform-${i + 1} #td-diag-1-${j + 1}`).val(caseData['Diagonal1'][j]['memId']);
                            }

                            for(let j = 0; j < caseData['Diagonal2'].length; j ++){
                                $(`#inputform-${i + 1} #diag-2-${j + 1}`).prop('checked', !caseData['Diagonal2'][j]['include']);
                                $(`#inputform-${i + 1} #diag-2-reverse-${j + 1}`).prop('checked', caseData['Diagonal2'][j]['reverse']);
                                $(`#inputform-${i + 1} #option-diagonals-mem2-${j + 1}-type`).val(caseData['Diagonal2'][j]['memType']);
                                $(`#inputform-${i + 1} #td-diag-2-${j + 1}`).val(caseData['Diagonal2'][j]['memId']);
                            }
                        }

                        $(`#wind-speed`).val(preloaded_data['Wind']);
                        $(`#wind-speed-override`).prop('checked', preloaded_data['WindCheckbox']);
                        $(`#ground-snow`).val(preloaded_data['Snow']);
                        $(`#ground-snow-override`).prop('checked', preloaded_data['SnowCheckbox']);

                        $(`#override-unit`).val(preloaded_data['Units']);

                        for(let i = 1; i <= 10; i ++)
                        {
                            drawTrussGraph(i);
                            drawStickGraph(i);
                        }
                    }
                    catch(e){
                        console.log(e);
                    }
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

    // $('input:text:enabled').each(function() { 
    //     if (typeof preloaded_data[$(this).attr('id')] !== 'undefined')
    //         $(this).val(preloaded_data[$(this).attr('id')]);
    // });
    // $('input[type=checkbox]:enabled').each(function() { 
    //     if (typeof preloaded_data[$(this).attr('id')] !== 'undefined') {
    //         console.log($(this).attr('id') + " : " + preloaded_data[$(this).attr('id')]);
    //         if (preloaded_data[$(this).attr('id')] == 'on') {
    //             $(this).prop('checked', true);
    //         }
    //         else {
    //             $(this).prop('checked', false);
    //         }         
    //     }
    // });
    // // $("input[name='mail']:enabled".each(function() { 
    // //     data[$(this).attr('id')] = $(this).val();
    // // });
    // $('input[type=date]:enabled').each(function() { 
    //     if (typeof preloaded_data[$(this).attr('id')] !== 'undefined') {
    //         $(this).val(preloaded_data[$(this).attr('id')]);
    //     }
    // });
    // $('select:enabled').each(function() { 
    //     if (typeof preloaded_data[$(this).attr('id')] !== 'undefined') {
    //         selectedValue = preloaded_data[$(this).attr('id')];
    //         $(this).find('option').each(function() {
    //             console.log($(this).val() + " : " + selectedValue);
    //             if ($(this).val() == selectedValue) {
    //                 $(this).prop('selected', true);
    //             }
    //         })

    //         // $(this).val(preloaded_data[$(this).attr('id')]);
    //     }
    // });

    // // $('#option-module-option1').html(preloaded_data['option-module-option1']);
    // // $('#option-module-option2').html(preloaded_data['option-module-option2']);
    // // $('#option-inverter-option1').html(preloaded_data['option-inverter-option1']);
    // // $('#option-inverter-option2').html(preloaded_data['option-inverter-option2']);
    // // $('#option-stanchion-option1').html(preloaded_data['option-stanchion-option1']);
    // // $('#option-stanchion-option2').html(preloaded_data['option-stanchion-option2']);
    // // $('#option-railsupport-option1').html(preloaded_data['option-railsupport-option1']);
    // // $('#option-railsupport-option2').html(preloaded_data['option-railsupport-option2']);

    // $('#td-unknown-degree1').html(preloaded_data['td-unknown-degree1']);
    // $('#td-calculated-roof-plane-length').html(preloaded_data['td-calculated-roof-plane-length']);
    // $('#td-diff-between-measured-and-calculated').html(preloaded_data['td-diff-between-measured-and-calculated']);
    // $('#td-sum-of-length-entered').html(preloaded_data['td-sum-of-length-entered']);
    // $('#td-checksum-of-segment1').html(preloaded_data['td-checksum-of-segment1']);
    // $('#td-total-length-entered').html(preloaded_data['td-total-length-entered']);
    // $('#td-checksum-of-segment2').html(preloaded_data['td-checksum-of-segment2']);

    // $('#td-diag-1-1').html(preloaded_data['td-diag-1-1']);
    // $('#td-diag-1-2').html(preloaded_data['td-diag-1-2']);
    // $('#td-diag-1-3').html(preloaded_data['td-diag-1-3']);
    // $('#td-diag-1-4').html(preloaded_data['td-diag-1-4']);
    // $('#td-diag-1-5').html(preloaded_data['td-diag-1-5']);
    // $('#td-diag-1-6').html(preloaded_data['td-diag-1-6']);

    // $('#td-diag-2-1').html(preloaded_data['td-diag-2-1']);
    // $('#td-diag-2-2').html(preloaded_data['td-diag-2-2']);
    // $('#td-diag-2-3').html(preloaded_data['td-diag-2-3']);
    // $('#td-diag-2-4').html(preloaded_data['td-diag-2-4']);
    // $('#td-diag-2-5').html(preloaded_data['td-diag-2-5']);
    // $('#td-diag-2-6').html(preloaded_data['td-diag-2-6']);
}

var availableUSState = [
  "AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT",
  "NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"
];

var availableMATowns = [
    "Abington", "Acton", "Acushnet", "Adams", "Agawam", "Alford", "Amesbury", "Amherst", "Andover", "Aquinnah", "Arlington", 
    "Ashburnham", "Ashby", "Ashfield", "Ashland", "Athol", "Attleboro", "Auburn", "Avon", "Ayer", 
    "Barnstable", "Barre", "Becket", "Bedford", "Belchertown", "Bellingham", "Belmont", "Berkley", "Berlin", "Bernardston", 
    "Beverly", "Billerica", "Blackstone", "Blandford", "Bolton", "Boston", "Bourne", "Boxborough", "Boxford", "Boylston", 
    "Braintree", "Brewster", "Bridgewater", "Brimfield", "Brockton", "Brookfield", "Brookline", "Buckland", "Burlington", 
    "Cambridge", "Canton", "Carlisle", "Carver", "Charlemont", "Charlton", "Chatham", "Chelmsford", "Chelsea", "Cheshire", 
    "Chester", "Chesterfield", "Chicopee", "Chilmark", "Clarksburg", "Clinton", "Cohasset", "Colrain", "Concord", "Conway", 
    "Cummington", "Dalton", "Danvers", "Dartmouth", "Dedham", "Deerfield", "Dennis", "Dighton", "Douglas", "Dover", "Dracut", 
    "Dudley", "Dunstable", "Duxbury", "E. Bridgewater", "E. Brookfield", "E. Longmeadow", "Eastham", "Easthampton", "Easton", 
    "Edgartown", "Egremont", "Erving", "Essex", "Everett", "Fairhaven", "Fall River", "Falmouth", "Fitchburg", "Florida", 
    "Foxborough", "Framingham", "Franklin", "Freetown", "Gardner", "Gay Head", "Georgetown", "Gill", "Gloucester", 
    "Goshen", "Grafton", "Gosnold", "Granby", "Granville", "GreatBarrington", "Greenfield", "Groton", "Groveland", 
    "Hadley", "Halifax", "Hamilton", "Hampden", "Hancock", "Hanover", "Hanson", "Hardwick", "Harvard", "Harwich", 
    "Hatfield", "Haverhill", "Hawley", "Heath", "Hingham", "Hinsdale", "Holbrook", "Holden", "Holland", "Holliston", 
    "Holyoke", "Hopedale", "Hopkinton", "Hubbardston", "Hudson", "Hull", "Huntington", "Ipswich", "Kingston", 
    "Lakeville", "Lancaster", "Lanesborough", "Lawrence", "Lee", "Leicester", "Lenox", "Leominster", "Leverett", 
    "Lexington", "Leyden", "Lincoln", "Littleton", "Longmeadow", "Lowell", "Ludlow", "Lunenburg", "Lynn", 
    "Lynnfield", "Malden", "Manchester", "Mansfield", "Marblehead", "Marion", "Marlborough", "Marshfield", "Mashpee", 
    "Mattapoisett", "Maynard", "Medfield", "Medford", "Medway", "Melrose", "Mendon", "Merrimac", "Methuen", 
    "Middleborough", "Middlefield", "Middleton", "Milford", "Millbury", "Millis", "Millville", "Milton", "Monroe", 
    "Monson", "Montague", "Monterey", "Montgomery", "Mount Washington", "Nahant", "Nantucket", "Natick", "Needham", 
    "New Ashford", "New Bedford", "New Braintree", "New Marlborough", "New Salem", "Newbury", "Newburyport", 
    "Newton", "Norfolk", "North Adams", "North Andover", "North Attleborough", "North Brookfield", "North Reading", 
    "Northampton", "Northborough", "Northbridge", "Northfield", "Norton", "Norwell", "Norwood", "OakBluffs", 
    "Oakham", "Orange", "Orleans", "Otis", "Oxford", "Palmer", "Paxton", "Peabody", "Pelham", "Pembroke", "Pepperell", 
    "Peru", "Petersham", "Phillipston", "Pittsfield", "Plainfield", "Plainville", "Plymouth", "Plympton", "Princeton", 
    "Provincetown", "Quincy", "Randolph", "Raynham", "Reading", "Rehoboth", "Revere", "Richmond", "Rochester", "Rockland", 
    "Rockport", "Rowe", "Rowley", "Royalston", "Russell", "Rutland", "Salem", "Salisbury", "Sandisfield", "Sandwich", 
    "Saugus", "Savoy", "Scituate", "Seekonk", "Sharon", "Sheffield", "Shelburne", "Sherborn", "Shirley", "Shrewsbury", 
    "Shutesbury", "Somerset", "Somerville", "SouthHadley", "Southampton", "Southborough", "Southbridge", 
    "Southwick", "Spencer", "Springfield", "Sterling", "Stockbridge", "Stoneham", "Stoughton", "Stow", "Sturbridge", 
    "Sudbury", "Sunderland", "Sutton", "Swampscott", "Swansea", "Taunton", "Templeton", "Tewksbury", "Tisbury", 
    "Tolland", "Topsfield", "Townsend", "Truro", "Tyngsborough", "Tyringham", "Upton", "Uxbridge", "Wakefield", 
    "Wales", "Walpole", "Waltham", "Ware", "Wareham", "Warren", "Warwick", "Washington", "Watertown", "Wayland", 
    "Webster", "Wellesley", "Wellfleet", "Wendell", "Wenham", "W. Boylston", "W. Bridgewater", "W. Brookfield", 
    "W. Newbury", "W. Springfield", "W. Stockbridge", "W. Tisbury", "Westborough", "Westfield", "Westford", 
    "Westhampton", "Westminster", "Weston", "Westport", "Westwood", "Weymouth", "Whately", "Whitman", 
    "Wilbraham", "Willamsburg", "Williamstown", "Wilmington", "Winchendon", "Winchester", "Windsor", "Winthrop", 
    "Woburn", "Worcester", "Worthington", "Wrentham", "Yarmouth"
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
        if (bFound == false)
            mainTypes.push(availablePVModules[index][0]);
    }
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
            if (subTypes[typeIndex] == availablePVModules[index][1]) {
                bFound = true;
            }
        }
        if (bFound == false)
            subTypes.push(availablePVModules[index][1]);
    }

    return subTypes;
}

var optionPVModule = function(mainType, subType, idx) {
    for (index = 0; index < availablePVModules.length; index++) {
        if ((availablePVModules[index][0] == mainType) 
            && (availablePVModules[index][1] == subType)) {
            return availablePVModules[index][idx];
        }
    }

    return "N/A";
}

var updatePVSubmoduleField = function(mainType, subType="") {

    if (subType == "") 
    {
        $('#option-module-subtype').find('option').remove();
        subTypes = getPVModuleSubTypes(mainType);
        
        selectedSubType = subTypes[0];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVModule'] != "undefined"
          && typeof preloaded_data['Equipment']['PVModule']['SubType'] != "undefined" ) {
            selectedSubType = preloaded_data['Equipment']['PVModule']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            if (subTypes[index] == selectedSubType) {
                $('#option-module-subtype').append(`<option data-value="${subTypes[index]}" selected> ${subTypes[index]}</option>`);
            }
            else {
                $('#option-module-subtype').append(`<option data-value="${subTypes[index]}"> ${subTypes[index]} </option>`);
            }
        }
        subType = selectedSubType; 
    }

    $('#option-module-option1').html(optionPVModule(mainType, subType, 2));
    
    $('#pv-module-length').val(optionPVModule(mainType, subType, 3));
    $('#pv-module-width').val(optionPVModule(mainType, subType, 4));
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
            if (subTypes[typeIndex] == availablePVInverters[index][1]) {
                bFound = true;
            }
        }
        if (bFound == false)
            subTypes.push(availablePVInverters[index][1]);
    }

    return subTypes;
}

var option1PVInverter = function(mainType, subType) {
    for (index = 0; index < availablePVInverters.length; index++) {
        if ((availablePVInverters[index][0] == mainType) 
            && (availablePVInverters[index][1] == subType)) {
            return availablePVInverters[index][2];
        }
    }

    return "N/A";
}

var option2PVInverter = function(mainType, subType) {
    for (index = 0; index < availablePVInverters.length; index++) {
        if ((availablePVInverters[index][0] == mainType) 
            && (availablePVInverters[index][1] == subType)) {
            return availablePVInverters[index][3];
        }
    }

    return "";
}

var updatePVInvertorSubField = function(mainType, subType = "") {
    if (subType == "") 
    {
        $('#option-inverter-subtype').find('option').remove();
        subTypes = getPVInvertorSubTypes(mainType);

        selectedSubType = subTypes[0];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['PVInverter'] != "undefined"
          && typeof preloaded_data['Equipment']['PVInverter']['SubType'] != "undefined" ) {
            selectedSubType = preloaded_data['Equipment']['PVInverter']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            if (subTypes[index] == selectedSubType) {
                $('#option-inverter-subtype').append(`<option data-value="${subTypes[index]}" selected> ${subTypes[index]}</option>`);
            }
            else {
                $('#option-inverter-subtype').append(`<option data-value="${subTypes[index]}"> ${subTypes[index]} </option>`);
            }
        }

        subType = subTypes[0];
    }

    $('#option-inverter-option1').html(option1PVInverter(mainType, subType));
    $('#option-inverter-option2').html(option2PVInverter(mainType, subType));   
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
            if (subTypes[typeIndex] == availableStanchions[index][1]) {
                bFound = true;
            }
        }
        if (bFound == false)
            subTypes.push(availableStanchions[index][1]);
    }

    return subTypes;
}

var option1Stanchion = function(mainType, subType) {
    for (index = 0; index < availableStanchions.length; index++) {
        if ((availableStanchions[index][0] == mainType) 
            && (availableStanchions[index][1] == subType)) {
            return availableStanchions[index][2];
        }
    }

    return "N/A";
}

var option2Stanchion = function(mainType, subType) {
    for (index = 0; index < availableStanchions.length; index++) {
        if ((availableStanchions[index][0] == mainType) 
            && (availableStanchions[index][1] == subType)) {
            return availableStanchions[index][3];
        }
    }

    return "";
}

var updateStanchionSubField = function(mainType, subType = "") {

    if (subType == "") {
        $('#option-stanchion-subtype').find('option').remove();
        subTypes = getStanchionSubTypes(mainType);

        selectedSubType = subTypes[0];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['Stanchion'] != "undefined"
          && typeof preloaded_data['Equipment']['Stanchion']['SubType'] != "undefined" ) {
            selectedSubType = preloaded_data['Equipment']['Stanchion']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            if (subTypes[index] == selectedSubType) {
                $('#option-stanchion-subtype').append(`<option data-value="${subTypes[index]}" selected> ${subTypes[index]}</option>`);
            }
            else {
                $('#option-stanchion-subtype').append(`<option data-value="${subTypes[index]}"> ${subTypes[index]} </option>`);
            }
        }
        subType = subTypes[0];
    }


    $('#option-stanchion-option1').html(option1Stanchion(mainType, subType));
    $('#option-stanchion-option2').html(option2Stanchion(mainType, subType));
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
            if (subTypes[typeIndex] == availableRailSupports[index][1]) {
                bFound = true;
            }
        }
        if (bFound == false)
            subTypes.push(availableRailSupports[index][1]);
    }

    return subTypes;
}

var option1RailSupport = function(mainType, subType) {
    for (index = 0; index < availableRailSupports.length; index++) {
        if ((availableRailSupports[index][0] == mainType) 
            && (availableRailSupports[index][1] == subType)) {
            return availableRailSupports[index][2];
        }
    }

    return "N/A";
}

var option2RailSupport = function(mainType, subType) {
    for (index = 0; index < availableRailSupports.length; index++) {
        if ((availableRailSupports[index][0] == mainType) 
            && (availableRailSupports[index][1] == subType)) {
            return availableRailSupports[index][3];
        }
    }

    return "";
}

var updateRailSupportSubField = function(mainType, subType = "") {
    if (subType == "") {
        $('#option-railsupport-subtype').find('option').remove();
        subTypes = getRailSupportSubTypes(mainType);

        selectedSubType = subTypes[0];
        if ( typeof preloaded_data != "undefined"
          && typeof preloaded_data['Equipment'] != "undefined" 
          && typeof preloaded_data['Equipment']['RailSupportSystem'] != "undefined"
          && typeof preloaded_data['Equipment']['RailSupportSystem']['SubType'] != "undefined" ) {
            selectedSubType = preloaded_data['Equipment']['RailSupportSystem']['SubType'];
        }

        for (index=0; index<subTypes.length; index++) 
        {
            if (subTypes[index] == selectedSubType) {
                $('#option-railsupport-subtype').append(`<option data-value="${subTypes[index]}" selected> ${subTypes[index]}</option>`);
            }
            else {
                $('#option-railsupport-subtype').append(`<option data-value="${subTypes[index]}"> ${subTypes[index]} </option>`);
            }
        }

        subType = subTypes[0];
    }

    $('#option-railsupport-option1').html(option1RailSupport(mainType, subType));
    $('#option-railsupport-option2').html(option2RailSupport(mainType, subType));
}

// load US state into select component
var loadStateOptions = function() {
    var selectedState = 'MA';
    if (typeof preloaded_data['option-state'] !== 'undefined') {
        selectedState = preloaded_data['option-state'];
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

    detectCorrectTownForMA();
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

var detectCorrectTownForMA = function() {
    var city_name = document.getElementById("txt-city").value;
    var option_state = document.getElementById("option-state");        
    var selected = option_state.options[option_state.selectedIndex];
    var state_name = selected.getAttribute('data-value');

    err_message = "No Town name match in MA CMR780.  Correct Town name.";
    good_message = "Good Massachusetts town name";

    if (state_name == 'MA') {
        for (index= 0; index <availableMATowns.length ; index++) {
            if (availableMATowns[index] == city_name) {
                $('#txt-city-comment').html(good_message);
                $('#txt-city-comment').css('color', 'black');
                return;
            }
        }

        $('#txt-city-comment').html(err_message);
        $('#txt-city-comment').css('color', '#FF0000');
        $('#txt-city-comment').css('font-weight', 'bold');
    }
    else {
        $('#txt-city-comment').html("");
        $('#txt-city-comment').css('color', 'black');
        $('#txt-city-comment').css('font-weight', 'normal');
    }
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

var ignorable = ['ac-7-1', 'ac-8-1', 'ac-9-1', 'ac-10-1', 'c-1-1', 'c-2-1', 'c-3-1', 'c-4-1'];

var isEmptyInputBox = function() {

    // check empty input text boxes
    var isEmpty = false;

    // check C1~C3 according to C2 value
    var caseCount = $("#option-number-of-conditions").val();
    for(let i = 1; i <= caseCount; i ++)
    {
        if($(`#inputform-${i} #c-2-1`).val() != "" && parseFloat($(`#inputform-${i} #c-2-1`).val()) != 0 && ($(`#inputform-${i} #c-1-1`).val() == ""))
        {
            isEmpty = true;
            $(`#inputform-${i} #c-1-1`).css('background-color', '#FFC7CE');
        }    
        if($(`#inputform-${i} #c-2-1`).val() != "" && parseFloat($(`#inputform-${i} #c-2-1`).val()) != 0 && ($(`#inputform-${i} #c-3-1`).val() == ""))
        {
            isEmpty = true;
            $(`#inputform-${i} #c-3-1`).css('background-color', '#FFC7CE');
        }
    }

    var empty_textboxes = $('input:text:enabled').filter(function() { return this.value === ""; });
    empty_textboxes.each(function() { 
        // skip note 
        if (typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("i-1-") || ignorable.indexOf($(this).attr('id')) > -1)) {
            return;
        }
        // skip sweet alert
        if(typeof $(this).attr('class') == "string" && $(this).attr('class').includes('swal2-input'))
            return;

        $(this).css('background-color', '#FFC7CE');
        isEmpty = true;
    });

    // check empty date boxes
    if ($('#date-of-field-visit').val() == "") {
        $('#date-of-field-visit').css('background-color', '#FFC7CE');
        isEmpty = true;
    }
    if ($('#date-of-plan-set').val() == "") {
        $('#date-of-plan-set').css('background-color', '#FFC7CE');
        isEmpty = true;
    }

    return isEmpty;
}

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

    alldata['txt-company-name'] = $('#txt-company-name').val();
    alldata['txt-company-number'] = $('#txt-company-number').val();
    alldata['txt-user-name'] = $('#txt-user-name').val();
    alldata['txt-user-email'] = $('#txt-user-email').val();
    alldata['option-user-id'] = $('#option-user-id').val();
    alldata['option-module-option1'] = $('#option-module-option1').html();
    alldata['option-module-option2'] = $('#option-module-option2').html();
    alldata['option-module-quantity'] = $('#option-module-quantity').val();
    alldata['option-inverter-option1'] = $('#option-inverter-option1').html();
    alldata['option-inverter-option2'] = $('#option-inverter-option2').html();
    alldata['option-inverter-quantity'] = $('#option-inverter-quantity').val();
    alldata['option-stanchion-option1'] = $('#option-stanchion-option1').html();
    alldata['option-stanchion-option2'] = $('#option-stanchion-option2').html();
    alldata['option-railsupport-option1'] = $('#option-railsupport-option1').html();
    alldata['option-railsupport-option2'] = $('#option-railsupport-option2').html();
    alldata['caseInputs'] = [];

    for(i = 1; i <= caseCount; i ++ )
    {
        var data = {}
        data['TrussFlag'] = $(`#trussFlagOption-${i}-2`)[0].checked;
        
        $(`#inputform-${i} input:text:enabled`).each(function() { 
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

        data['td-unknown-degree1'] = $(`#inputform-${i} #td-unknown-degree1`).html();
        data['td-calculated-roof-plane-length'] = $(`#inputform-${i} #td-calculated-roof-plane-length`).html();
        data['td-diff-between-measured-and-calculated'] = $(`#inputform-${i} #td-diff-between-measured-and-calculated`).html();
        data['td-sum-of-length-entered'] = $(`#inputform-${i} #td-sum-of-length-entered`).html();
        data['td-checksum-of-segment1'] = $(`#inputform-${i} #td-checksum-of-segment1`).html();
        data['td-total-length-entered'] = $(`#inputform-${i} #td-total-length-entered`).html();
        data['td-checksum-of-segment2'] = $(`#inputform-${i} #td-checksum-of-segment2`).html();

        data['td-diag-1-1'] = $(`#inputform-${i} #td-diag-1-1`).html();
        data['td-diag-1-2'] = $(`#inputform-${i} #td-diag-1-2`).html();
        data['td-diag-1-3'] = $(`#inputform-${i} #td-diag-1-3`).html();
        data['td-diag-1-4'] = $(`#inputform-${i} #td-diag-1-4`).html();
        data['td-diag-1-5'] = $(`#inputform-${i} #td-diag-1-5`).html();
        data['td-diag-1-6'] = $(`#inputform-${i} #td-diag-1-6`).html();

        data['td-diag-2-1'] = $(`#inputform-${i} #td-diag-2-1`).html();
        data['td-diag-2-2'] = $(`#inputform-${i} #td-diag-2-2`).html();
        data['td-diag-2-3'] = $(`#inputform-${i} #td-diag-2-3`).html();
        data['td-diag-2-4'] = $(`#inputform-${i} #td-diag-2-4`).html();
        data['td-diag-2-5'] = $(`#inputform-${i} #td-diag-2-5`).html();
        data['td-diag-2-6'] = $(`#inputform-${i} #td-diag-2-6`).html();

        alldata['caseInputs'].push(data);
    }

    alldata['wind-speed'] = $('#wind-speed').val();
    alldata['wind-speed-override'] = $('#wind-speed-override')[0].checked;
    alldata['ground-snow'] = $('#ground-snow').val();
    alldata['ground-snow-override'] = $('#ground-snow-override')[0].checked;
    alldata['override-unit'] = $('#override-unit').val();

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
    selectedVal = $(`#inputform-${condId} #option-roof-slope`).children("option:selected").val();
    if (selectedVal == "Roof slope (degrees)") {
        globalRoofSlopeDegree[condId] = true;
        $(`#inputform-${condId} #txt-roof-slope-another`).html("Top ridge height above floor plane");
    }
    else {
        globalRoofSlopeDegree[condId] = false;
        $(`#inputform-${condId} #txt-roof-slope-another`).html("Roof slope (degrees)");
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

    var F139 = parseFloat($(`#inputform-${condId} #txt-roof-degree`).val());
    var F161 = parseFloat($(`#inputform-${condId} #txt-length-of-floor-plane`).val());
    
    var W44, Y44, W25, W36, W39, W40;
    if (AB5 == true) {
        W44 = Math.atan(F139 / F161);
        Y44 = radianToDegree(W44);
        
        W25 = parseFloat($(`#inputform-${condId} #td-sum-of-length-entered`).html());
        W36 = parseFloat($(`#inputform-${condId} #td-total-length-entered`).html());
        var W39 = W36 / Math.cos(W44);
        var W40 = W39 - W25;

        $(`#inputform-${condId} #td-unknown-degree1`).html(Y44.toFixed(2));
        $(`#inputform-${condId} #td-calculated-roof-plane-length`).html(W39.toFixed(2));
        $(`#inputform-${condId} #td-diff-between-measured-and-calculated`).html(W40.toFixed(2));

    }
    else {
        var W25 = parseFloat($(`#inputform-${condId} #td-sum-of-length-entered`).html());
        var W36 = parseFloat($(`#inputform-${condId} #td-total-length-entered`).html());
        var W44 = degreeToRadian(F139);
        var W41 = Math.sin(W44) * W25;
        var Y44 = W41;
        var W39 = W36 / Math.cos(W44);
        var W40 = W39 - W25;

        $(`#inputform-${condId} #td-unknown-degree1`).html(Y44.toFixed(2));
        $(`#inputform-${condId} #td-calculated-roof-plane-length`).html(W39.toFixed(2));
        $(`#inputform-${condId} #td-diff-between-measured-and-calculated`).html(W40.toFixed(2));
    }
    
    var option_number_segements1 = document.getElementsByClassName(`${condId}-option-number-segments1`)[0];
    var selected1 = option_number_segements1.options[option_number_segements1.selectedIndex];
    var F148 = selected1.getAttribute('data-value');

    var option_number_segements2 = document.getElementsByClassName(`${condId}-option-number-segments2`)[0];
    var selected2 = option_number_segements2.options[option_number_segements2.selectedIndex];
    var F162 = selected2.getAttribute('data-value');

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

    var O19 = parseFloat($(`#inputform-${condId} #txt-roof-segment1-length`).val());
    if (U19 == true) { PQ19 = [Math.cos(W44) * O19, Math.sin(W44) * O19]; globalRoofPoints[condId].push(PQ19); }

    var O20 = O19 + parseFloat($(`#inputform-${condId} #txt-roof-segment2-length`).val());
    if (U20 == true) { PQ20 = [Math.cos(W44) * O20, Math.sin(W44) * O20]; globalRoofPoints[condId].push(PQ20); }

    var O21 = O20 + parseFloat($(`#inputform-${condId} #txt-roof-segment3-length`).val());
    if (U21 == true) { PQ21 = [Math.cos(W44) * O21, Math.sin(W44) * O21]; globalRoofPoints[condId].push(PQ21); }

    var O22 = O21 + parseFloat($(`#inputform-${condId} #txt-roof-segment4-length`).val());
    if (U22 == true) { PQ22 = [Math.cos(W44) * O22, Math.sin(W44) * O22]; globalRoofPoints[condId].push(PQ22); }

    var O23 = O22 + parseFloat($(`#inputform-${condId} #txt-roof-segment5-length`).val());
    if (U23 == true) { PQ23 = [Math.cos(W44) * O23, Math.sin(W44) * O23]; globalRoofPoints[condId].push(PQ23); }

    var O24 = O23 + parseFloat($(`#inputform-${condId} #txt-roof-segment6-length`).val());
    if (U24 == true) { PQ24 = [Math.cos(W44) * O24, Math.sin(W44) * O24]; globalRoofPoints[condId].push(PQ24); }

    var W45 = 0;
    var PQ30, PQ31, PQ32, PQ33, PQ34, PQ35;

    var O30 = parseFloat($(`#inputform-${condId} #txt-floor-segment1-length`).val());
    if (U30 == true) { PQ30 = [Math.cos(W45)*O30, 0]; globalFloorPoints[condId].push(PQ30); }

    var O31 = O30 + parseFloat($(`#inputform-${condId} #txt-floor-segment2-length`).val());
    if (U31 == true) { PQ31 = [Math.cos(W45)*O31, 0]; globalFloorPoints[condId].push(PQ31); }

    var O32 = O31 + parseFloat($(`#inputform-${condId} #txt-floor-segment3-length`).val());
    if (U32 == true) { PQ32 = [Math.cos(W45)*O32, 0]; globalFloorPoints[condId].push(PQ32); }

    var O33 = O32 + parseFloat($(`#inputform-${condId} #txt-floor-segment4-length`).val());
    if (U33 == true) { PQ33 = [Math.cos(W45)*O33, 0]; globalFloorPoints[condId].push(PQ33); }

    var O34 = O33 + parseFloat($(`#inputform-${condId} #txt-floor-segment5-length`).val());
    if (U34 == true) { PQ34 = [Math.cos(W45)*O34, 0]; globalFloorPoints[condId].push(PQ34); }

    var O35 = O34 + parseFloat($(`#inputform-${condId} #txt-floor-segment6-length`).val());
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
    var roofPlane = parseInt($(`#inputform-${condId} #option-number-segments1`).children("option:selected").val());
    var floorPlane = parseInt($(`#inputform-${condId} #option-number-segments2`).children("option:selected").val());

    for (index = 0; index < roofPlane; index++) {
        $(`#inputform-${condId} #td-roof-segment` + (index+1) +'-caption').html("Segment " + (index + 1) + " Length");
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1)).html((index + 1));
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1)).addClass('w400-bdr').removeClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1) +"-type").addClass('w400-bdr').removeClass('w400-blue-bdr');

    }
    for (index = roofPlane; index < 6; index++) {
        $(`#inputform-${condId} #td-roof-segment` + (index+1) +'-caption').html("");
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1)).html("");
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1)).removeClass('w400-bdr').addClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-truss-roof-segment` + (index+1) +"-type").removeClass('w400-bdr').addClass('w400-blue-bdr');
    }
    for (index = 0; index < floorPlane; index++) {
        $(`#inputform-${condId} #td-floor-segment` + (index+1) +'-caption').html("Segment " + (index + roofPlane + 1) + " Length");
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1)).html((index + roofPlane + 1));
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1)).addClass('w400-bdr').removeClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1) +"-type").addClass('w400-bdr').removeClass('w400-blue-bdr');
    }
    for (index = floorPlane; index < 6; index++) {
        $(`#inputform-${condId} #td-floor-segment` + (index+1) +'-caption').html("");
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1)).html("");
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1)).removeClass('w400-bdr').addClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-truss-floor-segment` + (index+1) +"-type").removeClass('w400-bdr').addClass('w400-blue-bdr');
    }

    globalDiagnoals1[condId] = Math.min(roofPlane, floorPlane);
    globalDiagnoals2[condId] = Math.min(roofPlane - 1, floorPlane);

    // Diagnoals 
    for (index = 0; index < globalDiagnoals1[condId]; index++) {
        if (keepStatus == false) {
            $(`#inputform-${condId} #diag-1-` + (index+1)).prop( "checked", false );
        }
        $(`#inputform-${condId} #td-diag-1-` + (index+1)).html(roofPlane + floorPlane + index+1);
        $(`#inputform-${condId} #td-diag-1-` + (index+1)).removeClass('w400-blue-bdr').addClass('w400-bdr');
        $(`#inputform-${condId} #td-diag-1-` + (index+1) + '-type').removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-1-` + (index+1) + "-type *").attr('disabled', false);
    }
    for (index = globalDiagnoals1[condId]; index < 6; index++) {
        if (keepStatus == false) {
            $(`#inputform-${condId} #diag-1-` + (index+1)).prop( "checked", false );
        }
        $(`#inputform-${condId} #td-diag-1-` + (index+1)).html('');
        $(`#inputform-${condId} #td-diag-1-` + (index+1)).addClass('w400-blue-bdr').removeClass('w400-bdr');
        $(`#inputform-${condId} #td-diag-1-` + (index+1) + '-type').addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-1-` + (index+1) + "-type *").attr('disabled', true);
    }
    for (index = 0; index < globalDiagnoals2[condId]; index++) {
        if (keepStatus == false) {
            $(`#inputform-${condId} #diag-2-` + (index+1)).prop( "checked", false );
        }
        $(`#inputform-${condId} #td-diag-2-` + (index+1)).html(roofPlane + floorPlane + globalDiagnoals1[condId] + index+1);
        $(`#inputform-${condId} #td-diag-2-` + (index+1)).removeClass('w400-blue-bdr').addClass('w400-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + '-type').removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + "-type *").attr('disabled', false);
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + '-reverse').removeClass('w400-blue-bdr').addClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + "-reverse *").attr('disabled', false);
    }
    for (index = globalDiagnoals2[condId]; index < 6; index++) {
        if (keepStatus == false) {
            $(`#inputform-${condId} #diag-2-` + (index+1)).prop( "checked", false );
        }
        $(`#inputform-${condId} #td-diag-2-` + (index+1)).html('');
        $(`#inputform-${condId} #td-diag-2-` + (index+1)).addClass('w400-blue-bdr').removeClass('w400-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + '-type').addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + "-type *").attr('disabled', true);
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + '-reverse').addClass('w400-blue-bdr').removeClass('w400-green-bdr');
        $(`#inputform-${condId} #td-diag-2-` + (index+1) + "-reverse *").attr('disabled', true);
    }

    updateRoofSlopeAnotherField(condId);
}

// -------------  Roof Plane ------------------
var updateNumberSegment1 = function (condId, roofPlane, keepStatus = true) {
    var totalLength = 0;
    roofPlane = parseInt(roofPlane);

    for (index = 0; index < roofPlane; index++) {
        totalLength += parseFloat($(`#inputform-${condId} #txt-roof-segment` + (index + 1) + '-length').val());

        // enable appropricate cells        
        $(`#inputform-${condId} #td-roof-segment` + (index + 1) + "-length").addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-roof-segment` + (index + 1) + "-length *").attr('disabled', false);
    }
    for (index = roofPlane; index < 6; index++) {
        // disable appropricate cells        
        $(`#inputform-${condId} #td-roof-segment` + (index + 1) + "-length").removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-roof-segment` + (index + 1) + "-length *").attr('disabled', true);
    }

    $(`#inputform-${condId} #td-sum-of-length-entered`).html(totalLength.toFixed(2));

    var lengthRoofPlane = parseFloat($(`#inputform-${condId} #txt-length-of-roof-plane`).val());
    if (Math.abs(lengthRoofPlane.toFixed(2) - totalLength.toFixed(2)) < 0.5) {
        $(`#inputform-${condId} #td-checksum-of-segment1`).html("OK");
        $(`#inputform-${condId} #td-checksum-of-segment1`).css('background-color', 'white');
    }
    else if (lengthRoofPlane.toFixed(2) < totalLength.toFixed(2)) {
        $(`#inputform-${condId} #td-checksum-of-segment1`).html("Segments add to greater than total length");
        $(`#inputform-${condId} #td-checksum-of-segment1`).css('background-color', '#FFC7CE');
    }
    else {
        $(`#inputform-${condId} #td-checksum-of-segment1`).html("Segments add to less than total length");
        $(`#inputform-${condId} #td-checksum-of-segment1`).css('background-color', '#FFC7CE');
    }

    updateTrussAndComments(condId, keepStatus);
}

// ---------------- Floor Plane --------------------------
var updateNumberSegment2 = function (condId, floorPlane, keepStatus = true) {
    var totalLength = 0;
    floorPlane = parseInt(floorPlane);

    for (index = 0; index < floorPlane; index++) {
        totalLength += parseFloat($(`#inputform-${condId} #txt-floor-segment` + (index + 1) + '-length').val());

        // enable appropricate cells        
        $(`#inputform-${condId} #td-floor-segment` + (index + 1) + "-length").addClass('w400-yellow-bdr').removeClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-floor-segment` + (index + 1) + "-length *").attr('disabled', false);
    }
    for (index = floorPlane; index < 6; index++) {
        // disable appropricate cells        
        $(`#inputform-${condId} #td-floor-segment` + (index + 1) + "-length").removeClass('w400-yellow-bdr').addClass('w400-blue-bdr');
        $(`#inputform-${condId} #td-floor-segment` + (index + 1) + "-length *").attr('disabled', true);
    }

    $(`#inputform-${condId} #td-total-length-entered`).html(totalLength.toFixed(2));

    var lengthFloorPlane = parseFloat($(`#inputform-${condId} #txt-length-of-floor-plane`).val());
    if (lengthFloorPlane.toFixed(2) == totalLength.toFixed(2)) {
        $(`#inputform-${condId} #td-checksum-of-segment2`).html("OK");
        $(`#inputform-${condId} #td-checksum-of-segment2`).css('background-color', 'white');
    }
    else if (lengthFloorPlane.toFixed(2) < totalLength.toFixed(2)) {
        $(`#inputform-${condId} #td-checksum-of-segment2`).html("Segments add to greater than total length");
        $(`#inputform-${condId} #td-checksum-of-segment2`).css('background-color', '#FFC7CE');
    }
    else {
        $(`#inputform-${condId} #td-checksum-of-segment2`).html("Segments add to less than total length");
        $(`#inputform-${condId} #td-checksum-of-segment2`).css('background-color', '#FFC7CE');
    }

    updateTrussAndComments(condId, keepStatus);
}

var updateRoofMemberType = function(condId, selectedVal) {
    for (index = 0; index<6; index++) {
        $(`#inputform-${condId} #td-truss-roof-segment`+ (index+1) + '-type').html(selectedVal);
    }
}

var updateFloorMemberType = function(condId, selectedVal) {
    for (index = 0; index<6; index++) {
        $(`#inputform-${condId} #td-truss-floor-segment`+ (index+1) + '-type').html(selectedVal);
    }
}

//--------------- Framing Condition Related functions ---------------------
// var saveCurrentMP = function(condId, currentMP) {
//     debugger;
//     var curMPData = [];
//     for (index=1; index<=11; index++) {
//         curMPData['a-' + index + '-1'] = getValue(`#inputform-${condId} #a-` + index + '-1');
//     }
//     for (index=1; index<=4; index++) {
//         curMPData['b-'+ index +'-1'] = getValue(`#inputform-${condId} #b-` + index + '-1');
//     }
//     for (index=1; index<=3; index++) {
//         curMPData['c-'+ index +'-1'] = getValue(`#inputform-${condId} #c-` + index + '-1');
//     }
//     for (index=1; index<=3; index++) {
//         curMPData['d-'+ index +'-1'] = getValue(`#inputform-${condId} #d-` + index + '-1');
//     }
//     for (index=1; index<=2; index++) {
//         curMPData['e-'+ index +'-1'] = getValue(`#inputform-${condId} #e-` + index + '-1');
//     }
//     for (index=1; index<=1; index++) {
//         curMPData['f-'+ index +'-1'] = getValue(`#inputform-${condId} #f-` + index + '-1');
//     }
//     for (index=1; index<=1; index++) {
//         curMPData['g-'+ index +'-1'] = getValue(`#inputform-${condId} #g-` + index + '-1');
//     }
//     for (index=1; index<=8; index++) {
//         if (getValue(`#inputform-${condId} #h-` + index + '-1') == 'TRUE') {
//             curMPData['h-'+ index +'-1'] = 'on';
//         }
//         else {
//             curMPData['h-'+ index +'-1'] = '';
//         }
//     }
//     for (index=1; index<=1; index++) {
//         curMPData['i-'+ index +'-1'] = getValue(`#inputform-${condId} #i-` + index + '-1');;
//     }
// }

// var loadCurrentMP = function(condId, currentMP) {
//     // load current MP value from global variable
// }

// var changeCurrentMP = function(condId, oldMP, newMP) {
//     saveCurrentMP(condId, oldMP);
//     loadCurrentMP(condId, newMP);
// }

// var updateTotalMPCount = function(condId, totalFramingConditions) {
//     debugger;

//     var currentMP = parseInt($(`#inputform-${condId} #a-1-1`).children("option:selected").val());
//     saveCurrentMP(condId, currentMP);

//     $(`#inputform-${condId} #a-1-1`).find('option').remove();
//     for (index=1; index<=totalFramingConditions; index++) 
//     {
//         if (index == currentMP) {
//             $(`#inputform-${condId} #a-1-1`).append(`<option data-value="${index}" selected> ${index}</option>`);
//         }
//         else {
//             $(`#inputform-${condId} #a-1-1`).append(`<option data-value="${index}"> ${index}</option>`);
//         }
//     }

//     loadCurrentMP(condId, currentMP);
// }

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

    var angleRadian = degreeToRadian( parseFloat($(`#inputform-${condId} #a-7-1`).val()) );
    var overhangLength = parseFloat($(`#inputform-${condId} #a-11-1`).val());
    var overhangX = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(angleRadian));

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

var adjustStickDrawingPanel = function( condId ) {
    var topYPoint = 0, topXPoint = 0;

    var angleRadian = degreeToRadian( parseFloat($(`#inputform-${condId} #a-7-1`).val()) );
    var roofHeight;
    //if(stick_right_input[condId] == 'height')
        roofHeight = parseFloat($(`#inputform-${condId} #a-9-1`).val());
    // else if(stick_right_input[condId] == 'diagnol')
    //     roofHeight = parseFloat($(`#inputform-${condId} #a-8-1`).val()) * Math.sin(angleRadian);
    // else if(stick_right_input[condId] == 'length')
    //     roofHeight = parseFloat($(`#inputform-${condId} #a-10-1`).val()) * Math.tan(angleRadian);
    
    var overhangX = parseFloat($(`#inputform-${condId} #a-11-1`).val()) * Math.sin(Math.PI / 2 - angleRadian);
    var overhangY = parseFloat($(`#inputform-${condId} #a-11-1`).val()) * Math.sin(angleRadian );

    var moduleCount = parseInt($(`#inputform-${condId} #f-1-1`).val());
    var moduleGap = parseFloat($(`#inputform-${condId} #g-1-1`).val()) / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;

    var moduleLengthSum = parseFloat($(`#inputform-${condId} #e-1-1`).val());
    var orientation;
    for(let i = 1; i <= moduleCount; i ++)
    {
        orientation = false;
        if($(`#inputform-${condId} #a-6-1`).val() == "Portrait")
            orientation = true;
        if($(`#inputform-${condId} #h-${i}-1`)[0].checked)
            orientation = !orientation;
        
        moduleLengthSum += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
    }

    // Show alert when module length is longer
    if( roofHeight + overhangY < Math.sin(angleRadian) * moduleLengthSum || (angleRadian != 0 && (1.0 / Math.tan(angleRadian)) * (roofHeight + overhangY) + overhangX < Math.cos(angleRadian) * moduleLengthSum))
        $(`#inputform-${condId} #stick-module-alert`).css('display', 'block');
    else
        $(`#inputform-${condId} #stick-module-alert`).css('display', 'none');

    topYPoint = Math.max(roofHeight + overhangY, Math.sin(angleRadian) * moduleLengthSum);
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
    if($(`#inputform-${condId} #a-7-1`).val() == "") // angle should not be empty
        return;
    adjustStickDrawingPanel(condId);
    drawStickBaseLine(condId);

    var label_index = 1;

    // Draw Overhang
    var angle = parseFloat($(`#inputform-${condId} #a-7-1`).val());
    var angleRadian = degreeToRadian(angle);
    
    var overhangLength = parseFloat($(`#inputform-${condId} #a-11-1`).val());
    var uphillDist = parseFloat($(`#inputform-${condId} #e-1-1`).val());

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

    // Draw Roof
    //var roofHeight;
    //if(stick_right_input[condId] == 'height')
        roofHeight = parseFloat($(`#inputform-${condId} #a-9-1`).val());
    //else if(stick_right_input[condId] == 'diagnol')
        //roofHeight = parseFloat($(`#inputform-${condId} #a-8-1`).val()) * Math.sin(angleRadian);
    //else if(stick_right_input[condId] == 'length')
        //roofHeight = parseFloat($(`#inputform-${condId} #a-10-1`).val()) * Math.tan(angleRadian);

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
        stick_ctx[condId].fillText("Attic Floor", roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] / 2, 30);
    else
        stick_ctx[condId].fillText("Attic Floor", stick_canvas_width[condId] / 2, 30);

    // Draw dashed line
    stick_ctx[condId].beginPath();
    stick_ctx[condId].lineWidth = 2;
    stick_ctx[condId].strokeStyle = "#0000FF";
    stick_ctx[condId].setLineDash([25, 25]);
    stick_ctx[condId].moveTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, 0);
    stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - roofHeight * stick_grid_size[condId]);

    stick_ctx[condId].stroke();
    stick_ctx[condId].setLineDash([25, 0]);

    // Draw Knee Wall
    var kneeWallHeight = $(`#inputform-${condId} #c-4-1`).val() == "" ? 0 : parseFloat($(`#inputform-${condId} #c-4-1`).val());

    if( kneeWallHeight <= roofHeight )
    {
        $(`#inputform-${condId} #c-4-warn`).css('display', 'none');
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(kneeWallHeight * (1 / Math.tan(angleRadian)) * stick_grid_size[condId], 0);
        stick_ctx[condId].lineTo(kneeWallHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId], - kneeWallHeight * stick_grid_size[condId]);
        stick_ctx[condId].stroke();
    }
    else
        $(`#inputform-${condId} #c-4-warn`).css('display', 'block');

    // Draw Collar Tie
    var collarTieHeight = $(`#inputform-${condId} #c-2-1`).val() == "" ? 0 : parseFloat($(`#inputform-${condId} #c-2-1`).val());
    if( collarTieHeight <= roofHeight )
    {
        $(`#inputform-${condId} #c-2-warn`).css('display', 'none');
        stick_ctx[condId].beginPath();
        stick_ctx[condId].lineWidth = 2;
        stick_ctx[condId].strokeStyle = "#0000FF";
        stick_ctx[condId].moveTo(angleRadian != 0 ? collarTieHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - collarTieHeight * stick_grid_size[condId]);
        stick_ctx[condId].lineTo(angleRadian != 0 ? roofHeight * (1 / Math.tan(angleRadian))  * stick_grid_size[condId] : 0, - collarTieHeight * stick_grid_size[condId]);
        stick_ctx[condId].stroke();
    }
    else
        $(`#inputform-${condId} #c-2-warn`).css('display', 'block');

    // Draw solar rectangles
    var startModule = overhangLength - uphillDist;
    var moduleDepth = 1.17 / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;
    var moduleGap = parseFloat($(`#inputform-${condId} #g-1-1`).val()) / 12;

    var startPoint = [- Math.sin(Math.PI / 2 - angleRadian) * startModule * stick_grid_size[condId] - stick_grid_size[condId] / 4 * Math.sin(angleRadian), Math.cos(Math.PI / 2 - angleRadian) * startModule * stick_grid_size[condId] - stick_grid_size[condId] / 4];
    stick_ctx[condId].translate(startPoint[0], startPoint[1]);
    stick_ctx[condId].rotate(- angleRadian);
    stick_ctx[condId].beginPath();
    stick_ctx[condId].lineWidth = 2;
    stick_ctx[condId].strokeStyle = "#000000";

    //totalRoofLength = parseFloat($(`#inputform-${condId} #a-9-1`).val()) / Math.sin(angleRadian);
    var moduleCount = parseInt($(`#inputform-${condId} #f-1-1`).val());
    
    let moduleStartX = 0;
    var orientation = false;
    
    var supportStart = parseFloat($(`#inputform-${condId} #e-2-1`).val()) - parseFloat($(`#inputform-${condId} #e-1-1`).val());

    stick_ctx[condId].fillStyle = "#000";
    for(let i = 1; i <= moduleCount; i ++)
    {
        orientation = false;
        if($(`#inputform-${condId} #a-6-1`).val() == "Portrait")
            orientation = true;
        if($(`#inputform-${condId} #h-${i}-1`)[0].checked)
            orientation = !orientation;
        
        stick_ctx[condId].strokeStyle = "#000000";
        stick_ctx[condId].strokeRect(moduleStartX * stick_grid_size[condId], 0, (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)) * stick_grid_size[condId], moduleDepth * stick_grid_size[condId]);
        // Left Support
        stick_ctx[condId].fillRect(moduleStartX * stick_grid_size[condId] + supportStart * stick_grid_size[condId] - 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 + 1 - moduleDepth * stick_grid_size[condId]);
        // Right Support
        stick_ctx[condId].fillRect(moduleStartX * stick_grid_size[condId] + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)) * stick_grid_size[condId] - stick_grid_size[condId] * (1 / 12 + supportStart) + 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 + 1 - moduleDepth * stick_grid_size[condId]);
        moduleStartX += (moduleGap + (orientation ? Math.max(moduleWidth, moduleHeight) : Math.min(moduleWidth, moduleHeight)));
    }
    
    stick_ctx[condId].rotate(angleRadian);
    stick_ctx[condId].translate(- startPoint[0], - startPoint[1]);

    var overhangX = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(angleRadian ));
    stick_ctx[condId].translate(- overhangX, overhangY);
}

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
            success:function(res){
                if(res.length > 0)
                {
                    for(let i = 0; i < res.length; i ++){
                        availablePVModules.push([res[i]['mfr'], res[i]['model'], res[i]['rating'], res[i]['length'], res[i]['width'], res[i]['depth'], res[i]['weight']]);
                    }
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
                        availablePVInverters.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2']]);
                    }
                }
                // ------------------- Second Line ---------------------
                // inverter module section
                $('#option-inverter-type').find('option').remove();
                mainTypes = getPVInvertorTypes();

                // load selected from preloaded_data
                selectedMainType = mainTypes[0];
                if ( typeof preloaded_data != "undefined"
                  && typeof preloaded_data['Equipment'] != "undefined" 
                  && typeof preloaded_data['Equipment']['PVInverter'] != "undefined"
                  && typeof preloaded_data['Equipment']['PVInverter']['Type'] != "undefined" ) {
                    selectedMainType = preloaded_data['Equipment']['PVInverter']['Type'];
                }

                for (index=0; index<mainTypes.length; index++) 
                {
                    if (mainTypes[index] == selectedMainType) {
                        $('#option-inverter-type').append(`<option data-value="${mainTypes[index]}" selected> ${mainTypes[index]}</option>`);
                    }
                    else {
                        $('#option-inverter-type').append(`<option data-value="${mainTypes[index]}"> ${mainTypes[index]} </option>`);
                    }
                }

                // inverter submodule section
                updatePVInvertorSubField(selectedMainType);
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
                        availableStanchions.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2']]);
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
                        availableRailSupports.push([res[i]['module'], res[i]['submodule'], res[i]['option1'], res[i]['option2']]);
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

    // component hander functions
    $("#txt-city").change(function(){
        detectCorrectTownForMA();
    });
    $('#option-state').on('change', function() {
        detectCorrectTownForMA();
    });
    $('#option-user-id').on('change', function() {
        updateUserOption($(this).children("option:selected").attr('data-userid'));
    });
    $('#option-module-type').on('change', function() {
        updatePVSubmoduleField($(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++)
            drawTrussGraph(i);
    });
    $('#option-module-subtype').on('change', function() {
        updatePVSubmoduleField( $('#option-module-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
        for(let i = 1; i <= 10; i ++)
            drawTrussGraph(i);
    });
    $('#option-inverter-type').on('change', function() {
        updatePVInvertorSubField($(this).children("option:selected").val());
    });
    $('#option-inverter-subtype').on('change', function() {
        updatePVInvertorSubField( $('#option-inverter-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
    });
    $('#option-stanchion-type').on('change', function() {
        updateStanchionSubField($(this).children("option:selected").val());
    });
    $('#option-stanchion-subtype').on('change', function() {
        updateStanchionSubField( $('#option-stanchion-type').children("option:selected").val(), 
                                $(this).children("option:selected").val());
    });
    $('#option-railsupport-type').on('change', function() {
        updateRailSupportSubField($(this).children("option:selected").val());
    });
    $('#option-railsupport-subtype').on('change', function() {
        updateRailSupportSubField( $('#option-stanchion-type').children("option:selected").val(), 
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

    var i = 0;
    for(i = 1; i <= 10; i ++)
    {
        $(`#inputform-${i} #option-roof-slope`).on('change', function() {
            updateRoofSlopeAnotherField(window.conditionId);
        });
        $(`#inputform-${i} #txt-roof-degree`).change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            updateRoofSlopeAnotherField(window.conditionId);
        });
        $(`#inputform-${i} #option-number-segments1`).on('change', function() {
            updateNumberSegment1(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#inputform-${i} #txt-length-of-roof-plane, #inputform-${i} #txt-roof-segment1-length, #inputform-${i} #txt-roof-segment2-length, #inputform-${i} #txt-roof-segment3-length, #inputform-${i} #txt-roof-segment4-length, #inputform-${i} #txt-roof-segment5-length, #inputform-${i} #txt-roof-segment6-length`)
        .change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            updateNumberSegment1(window.conditionId, $(`#inputform-${window.conditionId} #option-number-segments1`).children("option:selected").val());
        });
        $(`#inputform-${i} #option-number-segments2`).on('change', function() {
            updateNumberSegment2(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#inputform-${i} #txt-length-of-floor-plane, #inputform-${i} #txt-floor-segment1-length, #inputform-${i} #txt-floor-segment2-length, #inputform-${i} #txt-floor-segment3-length, #inputform-${i} #txt-floor-segment4-length, #inputform-${i} #txt-floor-segment5-length, #inputform-${i} #txt-floor-segment6-length`)
        .change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
            updateNumberSegment2(window.conditionId, $(`#inputform-${window.conditionId} #option-number-segments2`).children("option:selected").val());
        });
        $(`#inputform-${i} #option-roof-member-type`).on('change', function() {
            updateRoofMemberType(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#inputform-${i} #option-floor-member-type`).on('change', function() {
            updateFloorMemberType(window.conditionId, $(this).children("option:selected").val());
        });
        $(`#inputform-${i} #truss-axis`).on('change', function() {
            show_axis[window.conditionId] = !show_axis[window.conditionId];
            drawTrussGraph(window.conditionId);
        });
        $(`#inputform-${i} #stick-axis`).on('change', function() {
            stick_show_axis[window.conditionId] = !stick_show_axis[window.conditionId];
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #a-6-1, #inputform-${i} #g-1-1`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #f-1-1, #inputform-${i} #a-11-1, #inputform-${i} #e-1-1, #inputform-${i} #e-2-1`).on('change', function() {
            drawTrussGraph(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #c-2-1, #inputform-${i} #c-4-1`).on('change', function() {
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #a-7-1`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'angle'); return; }
            roofInputMode(window.conditionId, 'angle');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #a-8-1`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'diagnol'); return; }
            roofInputMode(window.conditionId, 'diagnol');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #a-9-1`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'height'); return; }
            roofInputMode(window.conditionId, 'height');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
        $(`#inputform-${i} #a-10-1`).on('change', function() {
            if(this.value == "") { stick_input_changed[window.conditionId] = stick_input_changed[window.conditionId].filter(e => e != 'length'); return; }
            roofInputMode(window.conditionId, 'length');
            checkRoofInput(window.conditionId);
            drawStickGraph(window.conditionId);
        });
    }
    $(`#h-1-1, #h-2-1, #h-3-1, #h-4-1, #h-5-1, #h-6-1, #h-7-1, #h-8-1, #h-9-1, #h-10-1, #h-11-1, #h-12-1`)
    .click( function() {
        drawStickGraph(window.conditionId);
        drawTrussGraph(window.conditionId);
    });
    $(`#diag-1-1, #diag-1-2, #diag-1-3, #diag-1-4, #diag-1-5, #diag-1-6, #diag-2-1, #diag-2-2, #diag-2-3, #diag-2-4, #diag-2-5, #diag-2-6, #diag-2-reverse-1, #diag-2-reverse-2, #diag-2-reverse-3, #diag-2-reverse-4, #diag-2-reverse-5, #diag-2-reverse-6`)
    .click( function(){
        drawTrussGraph(window.conditionId);
    });

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

    var submitData = function(e, status) {
        e.preventDefault();
        e.stopPropagation(); 

        if (isEmptyInputBox() == true) { 
            swal.fire({ title: "Warning", text: "Please fill blank fields.", icon: "warning", confirmButtonText: `OK` });
            return; 
        }

        var caseCount = $("#option-number-of-conditions").val();
        var data = getData(caseCount);
        var message = '';
        //for(let i = 0; i < parseInt(caseCount); i ++)
        //{
            // call ajax
        $.ajax({
            url:"submitInput",
            type:'post',
            data:{data: data, status: status, caseCount: caseCount},
            success:function(res){
                if (res.status == true) {
                    $("#projectId").val(res.projectId);
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
                        text: "Error happened while processing. Please try again later.",
                        icon: "error",
                        confirmButtonText: `OK` });
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
        
        //}
    }

    $('#rs-save').click(function(e) {
        $('#projectState').val("1");
        submitData(e, 'Saved');
    });
    $('#rs-datacheck').click(function(e){
        $('#projectState').val("2");
        submitData(e, 'Data Check');
    });
    $("#rs-submit").click(function(e){
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
                } 
            });
        }
        else{
            submitData(e, 'Submitted');
        }
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
        console.log(stick_right_input[condId]);
    }

    // Calculate correct values
    function checkRoofInput(condId) {
        var angleRadian = degreeToRadian($(`#inputform-${condId} #a-7-1`).val());
        if(stick_right_input[condId] == 'height' && $(`#inputform-${condId} #a-9-1`).val() != "")
        {
            var height = parseFloat($(`#inputform-${condId} #a-9-1`).val());
            var rightDiagnol = (height / Math.sin(angleRadian)).toFixed(2);
            var rightLength = (height / Math.tan(angleRadian)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-8-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-9-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-10-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "calculated value";

            $(`#inputform-${condId} #a-8-1`).val(rightDiagnol);
            $(`#inputform-${condId} #a-10-1`).val(rightLength);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
        else if(stick_right_input[condId] == 'length' && $(`#inputform-${condId} #a-10-1`).val() != "")
        {
            var length = parseFloat($(`#inputform-${condId} #a-10-1`).val());
            var rightDiagnol = (length / Math.cos(angleRadian)).toFixed(2);
            var rightHeight = (length * Math.tan(angleRadian)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-8-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-9-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-10-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "";
            
            $(`#inputform-${condId} #a-8-1`).val(rightDiagnol);
            $(`#inputform-${condId} #a-9-1`).val(rightHeight);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
        else if(stick_right_input[condId] == 'diagnol' && $(`#inputform-${condId} #a-8-1`).val() != "")
        {
            var diagnol = parseFloat($(`#inputform-${condId} #a-8-1`).val());
            var rightHeight = (diagnol * Math.sin(angleRadian)).toFixed(2);
            var rightWidth = (diagnol * Math.cos(angleRadian)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-8-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-9-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-10-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "calculated value";

            $(`#inputform-${condId} #a-9-1`).val(rightHeight);
            $(`#inputform-${condId} #a-10-1`).val(rightWidth);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
        else if(stick_right_input[condId] == 'diagnolheight' && $(`#inputform-${condId} #a-8-1`).val() != "" && $(`#inputform-${condId} #a-9-1`).val() != "")
        {
            var diagnol = parseFloat($(`#inputform-${condId} #a-8-1`).val());
            var height = parseFloat($(`#inputform-${condId} #a-9-1`).val());
            var rightAngle = Math.asin(height / diagnol);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightLength = (diagnol * Math.cos(rightAngle)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-8-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-9-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-10-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "calculated value";
            
            $(`#inputform-${condId} #a-7-1`).val(rightAngleDegree);
            $(`#inputform-${condId} #a-10-1`).val(rightLength);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
        else if(stick_right_input[condId] == 'diagnollength' && $(`#inputform-${condId} #a-8-1`).val() != "" && $(`#inputform-${condId} #a-10-1`).val() != "")
        {
            var diagnol = parseFloat($(`#inputform-${condId} #a-8-1`).val());
            var length = parseFloat($(`#inputform-${condId} #a-10-1`).val());
            var rightAngle = Math.acos(length / diagnol);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightHeight = (diagnol * Math.sin(rightAngle)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-8-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-9-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-10-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "";
            
            $(`#inputform-${condId} #a-7-1`).val(rightAngleDegree);
            $(`#inputform-${condId} #a-9-1`).val(rightHeight);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
        else if(stick_right_input[condId] == 'heightlength' && $(`#inputform-${condId} #a-9-1`).val() != "" && $(`#inputform-${condId} #a-10-1`).val() != "")
        {
            var height = parseFloat($(`#inputform-${condId} #a-9-1`).val());
            var length = parseFloat($(`#inputform-${condId} #a-10-1`).val());
            var rightAngle = Math.atan(height / length);
            var rightAngleDegree = radianToDegree(rightAngle).toFixed(2);
            var rightDiagnol = (height / Math.sin(rightAngle)).toFixed(2);

            $(`#inputform-${condId} #value-7-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-7-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-8-1`).css('background', '#95b3d7'); $(`#inputform-${condId} #calced-8-1`)[0].innerHTML = "calculated value";
            $(`#inputform-${condId} #value-9-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-9-1`)[0].innerHTML = "";
            $(`#inputform-${condId} #value-10-1`).css('background', '#ffc'); $(`#inputform-${condId} #calced-10-1`)[0].innerHTML = "";

            $(`#inputform-${condId} #a-7-1`).val(rightAngleDegree);
            $(`#inputform-${condId} #a-8-1`).val(rightDiagnol);

            $(`#inputform-${condId} #ac-7-1`).val($(`#inputform-${condId} #a-7-1`).val());
            $(`#inputform-${condId} #ac-8-1`).val($(`#inputform-${condId} #a-8-1`).val());
            $(`#inputform-${condId} #ac-9-1`).val($(`#inputform-${condId} #a-9-1`).val());
            $(`#inputform-${condId} #ac-10-1`).val($(`#inputform-${condId} #a-10-1`).val());
        }
    }

    // initialize function
    var initializeSpreadSheet = function() {
        loadPreloadedData();
        loadStateOptions();
        loadEquipmentSection();

        var i;
        for(i = 1; i <= 10; i ++)
        {
            drawTrussGraph(i);
            drawStickGraph(i);
            //stick_ctx[i].translate(-100, 100);

            keepStatus = true;
            if (preloaded_data.size == 0)
                keepStatus = false;

            updateNumberSegment1(i, $(`#inputform-${i} #option-number-segments1`).children("option:selected").val(), keepStatus);
            updateNumberSegment2(i, $(`#inputform-${i} #option-number-segments2`).children("option:selected").val(), keepStatus);

            checkRoofInput(i, 'height');
            // GetCPU();
        }
    }



initializeSpreadSheet();
});
</script>
