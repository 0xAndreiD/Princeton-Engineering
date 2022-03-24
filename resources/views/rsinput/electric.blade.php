<div class="row">
<table id="inputform-electric" cellspacing="0" cellpadding="0" style="border-spacing:0;" >    
    <colgroup>
        <col width="50">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="100">
        <col width="50">
    </colgroup>
    <tbody>
        <tr class="h13">
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
            <td><div style="overflow:hidden"></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-bdr" colspan="5"><b>AC / DC Stringing Table</b></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr">Inv #</td>
            <td class="iw400-bdr">String #</td>
            <td class="iw400-bdr">Modules / String</td>
            <td class="iw400-bdr">Strings / MPPT</td>
            <td class="iw400-bdr">String Length</td>
        </tr>
        <tr id="R1">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-success" style="padding: 0px 5px; height: 22px; margin-left: 10px;" onclick="addStrTable()">
                    <i class="fa fa-plus" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R2" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(2)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R3" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(3)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R4" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(4)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R5" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(5)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R6" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(6)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R7" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(7)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R8" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(8)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R9" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(9)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="R10" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="String" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="ModStr" tabindex="11">
                    <option value="0" selected="">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="StrMPPT" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="StrLength text-center" tabindex="11" value="0">
            </td>
            <td class="text-left">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px; margin-left: 10px;" onclick="removeStrTable(10)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-bdr" colspan="7"><b>AC Wire Table</b></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="iw400-bdr">Inverter #</td>
            <td class="iw400-bdr">Inverter Type</td>
            <td class="iw400-bdr">Wire Length (1 way)</td>
            <td class="iw400-bdr">Min Wire Size</td>
            <td class="iw400-bdr">Material</td>
            <td class="iw400-bdr">Insulation Rating</td>
            <td class="iw400-bdr">Circuits / Conduit</td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr id="AC1">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-success" style="padding: 0px 5px; height: 22px;" onclick="addACTable()">
                    <i class="fa fa-plus" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC2" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(2)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC3" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(3)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC4" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(4)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC5" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(5)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC6" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(6)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC7" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(7)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC8" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(8)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC9" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(9)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr id="AC10" style="display: none;">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InvType" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2" style="display: none;">2</option>
                    <option value="3" style="display: none;">3</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <input type="text" class="WireLength text-center" tabindex="11" value="0">
            </td>
            <td class="w400-yellow-bdr">
                <select class="MinWireSize" tabindex="11">
                    <option value="#14">#14</option>
                    <option value="#12">#12</option>
                    <option value="#10" selected="">#10</option>
                    <option value="#8">#8</option>
                    <option value="#6">#6</option>
                    <option value="#4">#4</option>
                    <option value="#3">#3</option>
                    <option value="#2">#2</option>
                    <option value="#1">#1</option>
                    <option value="1/0">1/0</option>
                    <option value="2/0">2/0</option>
                    <option value="3/0">3/0</option>
                    <option value="350 MCM">350 MCM</option>
                    <option value="400 MCM">400 MCM</option>
                    <option value="500 MCM">500 MCM</option>
                    <option value="600 MCM">600 MCM</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Material" tabindex="11">
                    <option value="Cu" selected="">Cu</option>
                    <option value="AL">AL</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="InsulRating" tabindex="11">
                    <option value="60">60</option>
                    <option value="75" selected="">75</option>
                    <option value="90">90</option>
                </select>
            </td>
            <td class="w400-yellow-bdr">
                <select class="Circuits" tabindex="11">
                    <option value="1" selected="">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </td>
            <td class="text-center">
                <span class="btn btn-danger" style="padding: 0px 6px; height: 22px;" onclick="removeACTable(10)">
                    <i class="fa fa-times" style="font-size: 12px; top: -2px;"></i>
                </span>
            </td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11" id="type-interconnection">
                    <option value="0">Load Side</option>
                    <option value="1">Line Side</option>
                </select>
            <td class="iw400-left-bdr" colspan="4">Type of Interconnection</td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
            <td><div style="overflow:hidden"></div></td>
        </tr>
        <tr class="h13">
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-bdr" colspan="5"><b>Bus Bar / OCPD Amperages</b></td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11" id="bus-bar-rating">
                    <option value="30">30</option>
                    <option value="60">60</option>
                    <option value="100">100</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="200" selected="">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            <td class="iw400-left-bdr" colspan="4">Existing Bus Bar Rating</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11" id="main-breaker-rating">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                    <option value="70">70</option>
                    <option value="80">80</option>
                    <option value="90">90</option>
                    <option value="100">100</option>
                    <option value="110">110</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="175">175</option>
                    <option value="200" selected="">200</option>
                    <option value="225">225</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            <td class="iw400-left-bdr" colspan="4">Existing Main Breaker Rating</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select tabindex="11" id="PropLoadCenter">
                    <option value="" selected=""></option>
                    <option value="30">30</option>
                    <option value="60">60</option>
                    <option value="100">100</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            </td>
            <td class="iw400-left-bdr" colspan="4">Proposed Replacement Load Center Rating</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select tabindex="11" id="PropLCBreaker">
                    <option value="" selected=""></option>
                    <option value="30">30</option>
                    <option value="60">60</option>
                    <option value="100">100</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            </td>
            <td class="iw400-left-bdr" colspan="4">Proposed Replacement LC Main Breaker Rating</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11" id="downgraded-breaker-rating">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                    <option value="70">70</option>
                    <option value="80">80</option>
                    <option value="90">90</option>
                    <option value="100">100</option>
                    <option value="110">110</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="175">175</option>
                    <option value="200" selected="">200</option>
                    <option value="225">225</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            <td class="iw400-left-bdr" colspan="4">Downgraded Breaker Rating</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-bdr text-center" id="PV-breaker-recommended">0</td>
            <td class="iw400-left-bdr" colspan="4">PV Breaker Recommended</td>
        </tr>
        <tr>
            <td><div style="overflow:hidden"></div></td>
            <td class="w400-yellow-bdr">
                <select class="Inv" tabindex="11" id="pv-breaker-selected">
                    <option value="10" selected="">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="60">60</option>
                    <option value="70">70</option>
                    <option value="80">80</option>
                    <option value="90">90</option>
                    <option value="100">100</option>
                    <option value="110">110</option>
                    <option value="125">125</option>
                    <option value="150">150</option>
                    <option value="175">175</option>
                    <option value="200">200</option>
                    <option value="225">225</option>
                    <option value="250">250</option>
                    <option value="300">300</option>
                    <option value="350">350</option>
                    <option value="400">400</option>
                </select>
            </td>
            <td class="iw400-left-bdr" colspan="4">PV Breaker Selected</td>
        </tr>
    </tbody>
</table>
</div>
