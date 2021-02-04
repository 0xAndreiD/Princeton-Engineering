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
  else if( tabName == "tab_upload" )
    document.getElementById('subPageTitle').innerHTML = 'Multiple Documents Upload';
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
        $(`#label-A-1-${conditionId}`).attr('rowspan', 7);
        $(`#label-B-1-${conditionId}`)[0].style.display = "none";
        $(`#title-B-3-${conditionId}`)[0].style.display = "table-cell";
        var elements = $(`#inputform-${conditionId} .class-truss-hide`);
        for(let i = 0; i < elements.length; i ++)
        {
            elements[i].style.display = 'none';
        }
        $(`#label-A-9-${conditionId}`)[0].innerHTML = 'Rise from Truss Plate to Top Ridge';
        $(`#label-A-10-${conditionId}`)[0].innerHTML = 'Horiz Len from Outside of Truss Plate to Ridge';
        $(`#label-A-11-${conditionId}`)[0].innerHTML = 'Diagonal Overhang Length past Truss Plate';
        $(`#label-B-3-${conditionId}`)[0].innerHTML = 'Truss Spacing - Center to Center';
        $(`#label-B-4-${conditionId}`)[0].innerHTML = 'Truss Material';
        $(`#label-F-1-${conditionId}`)[0].innerHTML = 'Maximum # Modules along Truss';
        document.getElementById(`trussInput-${conditionId}`).style.display = "block";
    }    
    else
    {
        $(`#label-A-1-${conditionId}`).attr('rowspan', 11);
        $(`#label-B-1-${conditionId}`)[0].style.display = "table-cell";
        $(`#title-B-3-${conditionId}`)[0].style.display = "none";
        var elements = $(`#inputform-${conditionId} .class-truss-hide`);
        for(let i = 0; i < elements.length; i ++)
        {
            elements[i].style.display = 'table-row';
        }
        $(`#label-A-9-${conditionId}`)[0].innerHTML = 'Rise from Rafter Plate to Top Ridge';
        $(`#label-A-10-${conditionId}`)[0].innerHTML = 'Horiz Len from Outside of Rafter Plate to Ridge';
        $(`#label-A-11-${conditionId}`)[0].innerHTML = 'Diagonal Overhang Length past Rafter Plate';
        $(`#label-B-3-${conditionId}`)[0].innerHTML = 'Joist Spacing - Center to Center';
        $(`#label-B-4-${conditionId}`)[0].innerHTML = 'Rafter Material';
        $(`#label-F-1-${conditionId}`)[0].innerHTML = 'Maximum # Modules along Rafter';
        document.getElementById(`trussInput-${conditionId}`).style.display = "none";
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

    // Show alert when module length is longer
    if(topXPoint < moduleLengthSum * Math.cos(angleRadian) || topYPoint < moduleLengthSum * Math.sin(angleRadian))
        $(`#truss-module-alert-${condId}`).css('display', 'block');
    else
        $(`#truss-module-alert-${condId}`).css('display', 'none');

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

    var overhangX = Math.floor(overhangLength * grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * grid_size[condId] * Math.sin(angleRadian));
    ctx[condId].translate(- overhangX, overhangY);
}

var preloaded_data = [];

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

var ignorable = ['a-7-', 'a-8-', 'af-8-', 'ai-8-', 'a-9-', 'af-9-', 'ai-9-', 'a-10-', 'af-10-', 'ai-10-', 'ac-7-', 'ac-8-', 'ac-9-', 'ac-10-', 'c-1-', 'c-2-', 'cf-2-', 'ci-2-', 'c-3-', 'cf-3-', 'ci-3-', 'c-4-', 'cf-4-', 'ci-4-', 'calc-algorithm-', 'collarHeights'];

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
    
    var empty_textboxes = $('input:text:enabled').filter(function() { return this.value === ""; });
    empty_textboxes.each(function() { 
        // skip note 
        if (typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("i-1-") || isIgnorable($(this).attr('id')) )) {
            return;
        }
        // skip sweet alert
        if(typeof $(this).attr('class') == "string" && $(this).attr('class').includes('swal2-input'))
            return;
        // skip feet / inch pair input
        if(typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("f-") && $('#' + $(this).attr('id').replace('f-', 'i-')).val() != ""))
            return;
        if(typeof $(this).attr('id') == "string" && ($(this).attr('id').includes("i-") && $('#' + $(this).attr('id').replace('i-', 'f-')).val() != ""))
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
    alldata['wind-exposure'] = $('#wind-exposure').val();
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

    var angleRadian = degreeToRadian( parseFloat($(`#a-7-${condId}`).val()) );
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
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

    var angleRadian = degreeToRadian( parseFloat($(`#a-7-${condId}`).val()) );
    var roofHeight;
    //if(stick_right_input[condId] == 'height')
        roofHeight = parseFloat($(`#a-9-${condId}`).val());
    // else if(stick_right_input[condId] == 'diagnol')
    //     roofHeight = parseFloat($(`#a-8-${condId}`).val()) * Math.sin(angleRadian);
    // else if(stick_right_input[condId] == 'length')
    //     roofHeight = parseFloat($(`#a-10-${condId}`).val()) * Math.tan(angleRadian);
    
    var overhangX = parseFloat($(`#a-11-${condId}`).val()) * Math.sin(Math.PI / 2 - angleRadian);
    var overhangY = parseFloat($(`#a-11-${condId}`).val()) * Math.sin(angleRadian );

    var moduleCount = parseInt($(`#f-1-${condId}`).val());
    var moduleGap = parseFloat($(`#g-1-${condId}`).val()) / 12;
    var moduleWidth = parseFloat($("#pv-module-width").val()) / 12;
    var moduleHeight = parseFloat($("#pv-module-length").val()) / 12;

    var moduleLengthSum = parseFloat($(`#e-1-${condId}`).val());
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

    // Show alert when module length is longer
    if( roofHeight + overhangY < Math.sin(angleRadian) * moduleLengthSum || (angleRadian != 0 && (1.0 / Math.tan(angleRadian)) * (roofHeight + overhangY) + overhangX < Math.cos(angleRadian) * moduleLengthSum))
        $(`#stick-module-alert-${condId}`).css('display', 'block');
    else
        $(`#stick-module-alert-${condId}`).css('display', 'none');

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
    adjustStickDrawingPanel(condId);
    drawStickBaseLine(condId);

    var label_index = 1;

    // Draw Overhang
    var angle = parseFloat($(`#a-7-${condId}`).val());
    var angleRadian = degreeToRadian(angle);
    
    var overhangLength = parseFloat($(`#a-11-${condId}`).val());
    var uphillDist = parseFloat($(`#e-1-${condId}`).val());

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
    var startModule = overhangLength - uphillDist;
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
            stick_ctx[condId].fillRect(supportStart * stick_grid_size[condId] - 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + 4 + supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
            // Right Support
            stick_ctx[condId].fillRect(curModuleWidth * stick_grid_size[condId] - stick_grid_size[condId] * (1 / 12 + supportStart) + 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + curModuleWidth * Math.tan(moduleTilt) * stick_grid_size[condId] + 1 - supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
            stick_ctx[condId].rotate(moduleTilt);
            stick_ctx[condId].translate(- moduleStartX * stick_grid_size[condId], 0);
        }
        else{
            stick_ctx[condId].translate(moduleStartX * stick_grid_size[condId] + curModuleWidth * stick_grid_size[condId], 0);
            stick_ctx[condId].rotate(- moduleTilt);
            stick_ctx[condId].strokeRect(0, 0, - curModuleWidth * stick_grid_size[condId], moduleDepth * stick_grid_size[condId]);
            // Left Support
            stick_ctx[condId].fillRect(- curModuleWidth * stick_grid_size[condId] + supportStart * stick_grid_size[condId] - 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + 1 - curModuleWidth * Math.tan(moduleTilt) * stick_grid_size[condId] + supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
            // Right Support
            stick_ctx[condId].fillRect(- stick_grid_size[condId] * (1 / 12 + supportStart) + 1, moduleDepth * stick_grid_size[condId], stick_grid_size[condId] / 12, stick_grid_size[condId] / 4 / Math.cos(moduleTilt) - moduleDepth * stick_grid_size[condId] + 4 - supportStart * Math.tan(moduleTilt) * stick_grid_size[condId]);
            stick_ctx[condId].rotate(moduleTilt);
            stick_ctx[condId].translate(- moduleStartX * stick_grid_size[condId] - curModuleWidth * stick_grid_size[condId], 0);
        }

        moduleStartX += (moduleGap + curModuleWidth);
    }
    
    stick_ctx[condId].rotate(angleRadian);
    stick_ctx[condId].translate(- startPoint[0], - startPoint[1]);

    var overhangX = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(Math.PI / 2 - angleRadian ));
    var overhangY = Math.floor(overhangLength * stick_grid_size[condId] * Math.sin(angleRadian ));
    stick_ctx[condId].translate(- overhangX, overhangY);
}

window.totalCount = 0; // file uploads count
window.finishedCount = 0; // file uploads count
window.uploadError = false; // file uploads count

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
                            $('#OCPDRating').html(res.data.OCPDRating);
                            $('#RecommendOCPD').html(res.data.RecommendOCPD);
                            $('#MinCu').html(res.data.MinCu);
                            
                            var collarHeights = res.data.collarHeights;
                            var haveChanges = false;
                            if(collarHeights){
                                for(let i = 1; i <= $('#option-number-of-conditions').val(); i ++){
                                    if(collarHeights.length >= 5 * i)
                                    {
                                        let tabCollar = collarHeights.slice(5 * (i - 1), 5 * i);
                                        if(parseFloat(tabCollar) != 0)
                                        {
                                            haveChanges = true;
                                            $(`#collartie-height-${i}`).html(parseFloat(tabCollar).toFixed(2));
                                            $(`#collartie-title-${i}`).html(i + ': ' + $(`#a-5-${i}`).val());
                                            $(`#collartie-${i}`).css('display', "table-row");
                                            $(`#collartie-warning-${i}`).css('display', 'block');
                                            $(`#collartie-warning-${i}`).html('Framing modification required.  Add collar tie / knee wall at ' + parseFloat(tabCollar).toFixed(2) + ' ft.');
                                        }
                                    }
                                }
                            }
                            if(haveChanges){
                                $('#collartie-header').css('display', "table-row");
                                $('#collartie-headers').css('display', "table-row");
                                $('#requiredNotes').css('color', 'red');
                                $('#requiredNotes').html(' *************** Roof Framing Changes are Required *************** ');
                            } else {
                                $('#requiredNotes').css('color', 'black');
                                $('#requiredNotes').html(' *************** No Roof Framing Changes are Required *************** ');
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
        $(`#option-roof-slope-${i}`).on('change', function() {
            updateRoofSlopeAnotherField(window.conditionId);
        });
        $(`#txt-roof-degree-${i}`).change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(2));
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
            $("#filetree").jstree('create_node', '#IN', {"text": data.result.filename, "id": data.result.id, "type": "infile", "path": data.result.path}, 'last');
            setTotalProgress(true);
        }
        else{
            data.context.addClass('error');
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

    var setTotalProgress = function(status){
        window.finishedCount ++;
        var progress = (window.finishedCount * 100 / window.totalCount).toFixed(2);
        $("#progressBar-value").css("width", progress + "%");
        $("#uploadPercent").html(progress + "%");
        if(status == false)
            window.uploadError = true;
        if(window.finishedCount >= window.totalCount){
            if(window.uploadError)
                swal.fire({ title: "Warning", text: "Upload Failed.", icon: "warning", confirmButtonText: `OK` });
            else
                swal.fire({ title: "Success", text: "Upload Done.", icon: "success", confirmButtonText: `OK` });
            window.finishedCount = 0;
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
        }
        return hasWarnings;
    }

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
        if( !checkWarnings() ){
            $('#projectState').val("1");
            submitData(e, 'Saved');
        } else{
            swal.fire({ title: "Error",
                    text: "Warning - Please fix the warnings before submit.",
                    icon: "error",
                    confirmButtonText: `OK` });
        }
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
    $('#rs-initialize').click(function(e){
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
                    }
                    else
                        targetTabId = parseInt(result.value);
                    $(`#trussFlagOption-${targetTabId}-1`).prop('checked', $(`#trussFlagOption-${curTabId}-1`)[0].checked);
                    $(`#trussFlagOption-${targetTabId}-2`).prop('checked', $(`#trussFlagOption-${curTabId}-2`)[0].checked);
                    fcChangeType(targetTabId, $(`#trussFlagOption-${curTabId}-2`)[0].checked);
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
            }
        }))
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

                                $(`#b-1-${i + 1}`).val(caseData['RafterDataInput']['B1']);
                                $(`#b-2-${i + 1}`).val(caseData['RafterDataInput']['B2']);
                                $(`#b-3-${i + 1}`).val(caseData['RafterDataInput']['B3']);
                                $(`#b-4-${i + 1}`).val(caseData['RafterDataInput']['B4']);

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

                                $(`#d-1-${i + 1}`).val(caseData['RoofDeckSurface']['D1']);
                                $(`#d-2-${i + 1}`).val(caseData['RoofDeckSurface']['D2']);
                                $(`#d-3-${i + 1}`).val(caseData['RoofDeckSurface']['D3']);

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
                                maxModuleNumChange(i + 1);
                                $(`#g-1-${i + 1}`).val(caseData['NSGap']['G1']);
                                if(caseData['NSGap']['G2']) $(`#g-2-${i + 1}`).val(caseData['NSGap']['G2']);
                                
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

                            $(`#wind-speed`).val(preloaded_data['Wind']);
                            $(`#wind-speed-override`).prop('checked', preloaded_data['WindCheckbox']);
                            $(`#ground-snow`).val(preloaded_data['Snow']);
                            $(`#ground-snow-override`).prop('checked', preloaded_data['SnowCheckbox']);
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
        else
            resolve(true);

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
    });
}

    // initialize function
    var initializeSpreadSheet = async function() {
        await loadPreloadedData();
        loadStateOptions();
        loadEquipmentSection();
        await loadDataCheck();

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

const download = async (url, name) => {
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

function downloadFile(filename){
    var selectedIds = $("#filetree").jstree("get_checked", null, true);
    let filenames = [];
    for(let i = 0; i < selectedIds.length; i ++){
        var node = $('#filetree').jstree(true).get_node(selectedIds[i]);
        console.log(node);
        if(node.type == "infile" || node.type == "outfile"){
            filenames.push(node.path);
        }
    }
    // if(filenames.length > 0){
    //     $.ajax({
    //         url:"getTemporaryLinks",
    //         type:'post',
    //         data:{filenames: filenames, projectId: $('#projectId').val()},
    //         success:async function(res){
    //             if(res.success){
    //                 let i = 0;
    //                 for(let filename in res.links){
    //                     await delay(i * 1000);
    //                     download(res.links[filename], filename);
    //                     i ++;
    //                 }
    //             } else{
    //                 swal.fire({ title: "Failed!", text: res.message, icon: "error", confirmButtonText: `OK` });
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             res = JSON.parse(xhr.responseText);
    //             message = res.message;
    //             swal.fire({ title: "Error",
    //                 text: message == "" ? "Error happened while processing. Please try again later." : message,
    //                 icon: "error",
    //                 confirmButtonText: `OK` });
    //         }
    //     });
    // } else {
    //     swal.fire({ title: "Warning", text: 'Please select files', icon: "warning", confirmButtonText: `OK` });
    // }
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
</script>
