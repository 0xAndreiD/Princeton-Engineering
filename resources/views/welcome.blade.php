@extends('layouts.app')

@section('content')
    <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html lang="en" style="background-color: rgb(245, 245, 245)">

    <head>
        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <title>Princeton Engineering and Solar</title>

        <TITLE>Engineering Project Design | Princeton Engineering</TITLE>

        <META HTTP-EQUIV="Content-Type" content="text/html; charset=UTF-8">
        <META HTTP-EQUIV="Cache-Control" content="max-age=1800">
        <META NAME="ROBOTS" CONTENT="INDEX,FOLLOW">
        <meta name="DESCRIPTION"
            content="Princeton Engineering and Solar provides PV solar engineering for commercial and utility installations.">
        <meta name="KEYWORDS" content="Princeton PV photovoltaic utility solar engineering design">

        <meta name="revisit-after" content="30 days">
        <meta name="rating" content="general">
        <link href="https://www.princeton-engineering.com/redbank.css" rel="stylesheet" type="text/css">
        <!-- Place this tag in the <head> of your document-->
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
                padding-top: 10px;
                text-align: center;
            }

            p {
              line-height: 1.1;
            }

            #centered {
                width: 1072px;
                text-align: left;
                border: 50px;
                padding: 0;
                margin: 0 auto;
            }


            table.roundedCorners {
                border: 1px solid DarkOrange;
                border-radius: 13px;
                border-spacing: 0;
            }

            table.roundedCorners td,
            table.roundedCorners th {
                border-bottom: 1px solid DarkOrange;
                padding: 10px;
            }

            table.roundedCorners tr:last-child>td {
                border-bottom: none;
            }

            .link-button-wrapper {
                width: 200px;
                height: 60px;
                box-shadow: inset 0px 1px 0px 0px #ffffff;
                border-radius: 8px;
                background-color: #fced0f;
                box-shadow: 0px 2px 4px gray;
                display: block;
                border: 1px solid #094BC0;
            }

            .link-button-wrapper>a {
                display: inline-table;
                cursor: pointer;
                text-decoration: none;
                height: 100%;
                width: 100%;
            }

            .link-button-wrapper>a>h1 {
                margin: 0 auto;
                display: table-cell;
                vertical-align: middle;
                color: #f7f8f8;
                font-size: 18px;
                font-family: cabinregular;
                text-align: center;
            }

            .divborder {
                align="left": ;
                border: 2px solid black;
                box-sizing: border-box;
                background-color: #fff;
                border-radius: 20px;
                padding-top: 1px;
                padding-right: 1px;
                padding-bottom: 4px;
                padding-left: 2px;
            }
        </style>




    </head>

    <body style="color: rgb(0,0,0); background-color: rgb(245, 245, 245);" leftmargin="0" topmargin="0" alink="#ee0000"
        link="#000000" marginheight="0" marginwidth="0" vlink="#6489ae">
        <div id="centered">
            <font style="font-family: arial,verdana;">
                <font size="3">
                    <font>
                        <font size="6">
                            <font>
                                <font size="3"><br>
                                </font>
                            </font>
                        </font>
                    </font>
                </font>
            </font>
            <!-- Logo and tag line area  -->
            <table style="text-align: left; width: 1071px; height: 180px;" border="0" cellpadding="3" cellspacing="2">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <img alt="" src="https://www.princeton-engineering.com/images/logo.jpg" style="width: 140px; height: 120px;">
                        </td>
                        <td style="text-align: center; vertical-align: top;">
                            <div style="text-align: center;">
                                <font size="6">
                                    <b><span style="font-family: arial,verdana;"> Princeton Engineering and Solar</span></b>
                                </font>
                                <br>
                                <!--   <font><font size="6"><b><span style="font-family: arial,verdana;"></span></b></font></font>  -->
                            </div>
                            <font size="6">
                                <big style="">
                                    <span style="font-family: arial,verdana;">
                                        <small>
                                            <small> 
                                                <span style="font-style: italic;">Solar, Structural, Electrical and Site Engineering</span>
                                            </small>
                                        </small>
                                    </span>
                                </big>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <a style="font-family: arial,verdana;" class="tertiary"
                                id="sublink2" href="https://www.princeton-engineering.com/index.html" target="_self"></a>
                            <table style="text-align: left; width: 152px; height: 170px;" border="0" cellpadding="2" cellspacing="2">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top;"><br></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><a
                                                href="https://www.princeton-engineering.com/index.html"> <span
                                                    style="font-family: helvetica,arial,sans-serif;">Home</span></a><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><a
                                                href="https://www.princeton-engineering.com/solar.html"><span
                                                    style="font-family: helvetica,arial,sans-serif;">Solar</span></a> </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"> <a
                                                href="https://www.princeton-engineering.com/electricalmechanical.html">
                                                <span style="font-family: helvetica,arial,sans-serif;">Electrical<br>
                                                </span></a>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="https://www.princeton-engineering.com/structural.html">
                                                <span style="font-family: helvetica,arial,sans-serif;">Structural</span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"> 
                                            <a href="https://www.princeton-engineering.com/siteengineering.html"> 
                                                <span style="font-family: helvetica,arial,sans-serif;">Civil Site<br></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="https://www.princeton-engineering.com/windandseismic.html"> <span
                                                    style="font-family: helvetica,arial,sans-serif;">Wind<br>
                                                </span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><br></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="https://www.princeton-engineering.com/framinganalysis.html"> <span
                                                    style="font-family: helvetica,arial,sans-serif;">iRoof&#8482;<br>
                                                </span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><br></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <a href="https://www.princeton-engineering.com/contact.html"> <span
                                                    style="font-family: helvetica,arial,sans-serif;">Contact Us</span></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- End Logo and tag line area  -->

                            <a style="font-family: arial,verdana;" class="tertiary" id="sublink9"
                                href="https://www.princeton-engineering.com/contact.html" target="_self"></a> 
                            <a style="font-family: arial,verdana;" class="tertiary" id="sublink9"
                                href="https://www.princeton-engineering.com/directions.html" target="_self"></a>
                        </td>
                        <td style="vertical-align: top;">
                            <br>
                            <hr style="width: 100%; height: 2px;">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 06.jpg" alt=""
                                                style="width: 300px; height: 150px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 05.jpg" alt=""
                                                style="width: 300px; height: 150px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 04.jpg" alt=""
                                                style="width: 300px; height: 150px;">
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 03.jpg" alt=""
                                                style="width: 100%; height: 150px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 02.jpg" alt=""
                                                style="width: 100%; height: 150px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="https://www.princeton.engineering/iRoof/login"
                                            title="Registered User Log In">
                                            <img src="https://www.princeton-engineering.com/images/house 01.jpg" alt=""
                                                style="width: 100%; height: 150px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width:33%">
                                        <div align="center">
                                            <font style="font-family: arial,verdana;">
                                                <font size="6">
                                                    <div style="padding:5px;display:table;">
                                                        <div class="link-button-wrapper">
                                                            <a href="https://www.princeton.engineering/iRoof/login"
                                                                title="Registered User Log In">
                                                                <h1>
                                                                    <font style="font-family: arial,verdana;">
                                                                        <font color="#000000">
                                                                            <font size="5">
                                                                                Register to<br>
                                                                                use
                                                                                <font style="font-family: arial,verdana;">
                                                                                    <font size="5"><strong>iRooFA</strong></font>
                                                                                    <font size="3"> <sup>TM</sup></font>
                                                                                </font>
                                                                            </font>
                                                                        </font>
                                                                    </font>
                                                                </h1>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </font>
                                            </font>
                                        </div>
                                    </td>
                                    <td style="width:33%">
                                        <br>
                                        <div align="center">

                                            <!-- iRoof with TM logo  -->

                                            <font style="font-family: arial,verdana;">
                                                <font size="7"><strong>iRooF</strong></font>
                                                <font size="6"> <sup>TM</sup></font>
                                            </font>
                                        </div>
                                    </td>
                                    <td style="width:33%">
                                        <div align="center">
                                            <font style="font-family: arial,verdana;">
                                                <font size="6">
                                                    <div style="padding:5px;display:table;">
                                                        <div class="link-button-wrapper">
                                                            <a href="https://www.princeton.engineering/iRoof/login"
                                                                title="New User Registration">
                                                                <h1>
                                                                    <font style="font-family: arial,verdana;">
                                                                        <font color="#000000">
                                                                            <font size="5">Login to <br>
                                                                                <font style="font-family: arial,verdana;">
                                                                                    <font size="5"><strong>iRooF</strong></font>
                                                                                    <font size="3"> <sup>TM</sup></font>
                                                                                </font>
                                                                            </font>
                                                                        </font>
                                                                    </font>
                                                                </h1>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </font>
                                            </font>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left;">
                                <p align="center">
                                    <div style="text-align: left;">
                                        <p align="center">
                                            <font style="font-family: arial,verdana;">
                                                <font size="5">
                                                    <strong><font size="6">I</font>nstant  <font size="6">R</font>oof  <font size="6">F</font>raming  <font size="6">A</font>nalysis</strong>
                                                </font>
                                                <font size="3">
                                                    <br>
                                                    <br>
                                                </font>
                                                <font size="5">
                                                    <b><em> Roof Framing Analysis for Residential Rooftop Solar Installations</em></b> 
                                                </font>
                                            </font>
                                            <br>
                                            <font size="4" style="font-family: arial,verdana;"></font>
                                        </p>
                                        <div align="center">
                                            <p>
                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><em>Stick Frame, Truss, IBC / CBC 5% and Seismic Analyses</em></font>
                                                </font>
                                            </p>
                                            <div class="divborder">
                                                <font style="font-family: arial,verdana;" font="" size="4">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%"><strong>Automated</strong> Residential Roof Structural  reports. Easy to use online data input.</td>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%"><p>Tens of  thousands of products in our database</p></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="3%">
                                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="100%">&nbsp;•<br><br><br></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td width="100%">&nbsp;•</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <td width="47%">
                                                                        <table border="0" cellspacing="0" cellpadding="0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td width="100%"><strong>3 to 5 hour</strong> turnaround time for sealed  report and plan set. Upload your plans and photos - we review / sign / seal.</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td width="100%"><strong>Absolute lowest fees </strong>in the Residential  PV industry.</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td width="3%">&nbsp;•<br></td>
                                                                    <td width="47%">AutoCADtm  integration. Automatically insert: project data, owner and location information,  key maps, structural notes, inverters, electrical calculation tables, wire and  breaker sizes, and product cut sheets and more.</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%"><strong>PE Licenses in 34 US states</strong>.</td>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%">Automatic  BOM's. Post Installation Affidavits and select state construction permits.</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%">Electrical  calculations including wire and breaker sizing.</td>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%"><strong>API integration</strong> with CRM for automated  job creation.</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%">Wind / Snow  / building code verification.&gt;</td>
                                                                <td width="3%">&nbsp;•<br></td>
                                                                <td width="47%"><strong>Patent Pending</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </font>
                                            </div>
                                            <div align="left">
                                                <br><br>
                                                <font size="4" style="font-family: arial,verdana;"></font>
                                                <div align="left">
                                                    <p>
                                                        <font size="4" style="font-family: arial,verdana;">Use the online

                                                        <!-- iRoof with TM logo  -->

                                                            <font style="font-family: arial,verdana;">
                                                                <font size="5"><strong>iRooFA</strong></font>
                                                                <font size="3"> <sup>TM</sup></font>
                                                            </font>
                                                            data form to enter field data and locate the proposed array on the roof structure.
                                                            <br>
                                                            <br>
                                                            Even use your smartphone to submit projects from the field!
                                                            <br>
                                                            <br>
                                                            <!-- iRoof with TM logo  -->
                                                            <font style="font-family: arial,verdana;">
                                                                <font size="5"><strong>iRooFA</strong></font>
                                                                <font size="3"> <sup>TM</sup></font>
                                                            </font>

                                                            <font size="4" style="font-family: arial,verdana;"> gives you <font size="5">
                                                                    <strong>INSTANT:</strong></font>
                                                            </font>

                                                            <font size="4" style="font-family: arial,verdana;">

                                                            <ul>
                                                                <li>
                                                                    Error checking on your data input
                                                                </li>
                                                                <li>
                                                                    <!-- iRoof with TM logo  -->

                                                                    <font style="font-family: arial,verdana;">
                                                                        <font size="5"><strong>iRooFA</strong></font>
                                                                        <font size="3"> <sup>TM</sup></font>
                                                                    </font>
                                                                    structural engineering report
                                                                </li>
                                                                <li>
                                                                    Professional Engineer's State Seal AND e-Signature on your
                                                                    <!-- iRoof with TM logo  -->

                                                                    <font style="font-family: arial,verdana;">
                                                                        <font size="5"><strong>iRooFA</strong></font>
                                                                        <font size="3"> <sup>TM</sup></font>
                                                                    </font>
                                                                    report
                                                                </li>
                                                            </ul>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                takes care of input data error checking and prepares a detailed structural
                                                report for submission to AHJ's across the US and Canada.

                                                <br>
                                                <br>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                INSTANTLY tells you if the roof can support a PV solar installation.

                                                <br>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                INSTANTLY identifies problems and tells you how to strengthen a weak roof

                                                <br>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                INSTANTLY specifies minimum racking lag bolt requirements

                                                <br>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                INSTANTLY creates a complete roof structural analysis report

                                                <br>

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>

                                                INSTANTLY adds a Professional Engineer's State Seal and e-Signature on your

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font> report

                                                <br>
                                                <br>

                                                The

                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font>



                                                report can be sealed by our Professional Engineers or your Licensed Design
                                                Professional after their appropriate review. <br>
                                                <br>


                                                <!-- iRoof with TM logo  -->

                                                <font style="font-family: arial,verdana;">
                                                    <font size="5"><strong>iRooFA</strong></font>
                                                    <font size="3"> <sup>TM</sup></font>
                                                </font> is used by:
                                                <ul>
                                                    <li>
                                                        Solar Contractors
                                                    </li>
                                                    <li>
                                                        Licensed Engineers
                                                    </li>
                                                    <li>
                                                        Registered Architects
                                                    </li>

                                                </ul>
                                    </p>


                                    <!-- iRoof with TM logo  -->

                                    <font style="font-family: arial,verdana;">
                                        <font size="5"><strong>iRooFA</strong></font>
                                        <font size="3"> <sup>TM</sup></font>
                                    </font>

                                    analyzes:

                                    <br>

                                    </font>

                                    <ul>
                                        <li>
                                            <font size="4" style="font-family: arial,verdana;">Standard Framing</font>
                                        </li>

                                        <li>
                                            <font size="4" style="font-family: arial,verdana;"> Truss Framing</font>
                                        </li>
                                    </ul>


                                    <!-- iRoof with TM logo  -->

                                    <font style="font-family: arial,verdana;">
                                        <font size="5"><strong>iRooFA</strong></font>
                                        <font size="3"> <sup>TM</sup></font>
                                        <font size="4">
                                            's large, customizable, database of equipment and building materials includes:


                                            <br>

                                        </font>

                                    </font>
                                    <ul>
                                        <li>
                                            <font size="4" style="font-family: arial,verdana;"> PV Solar Modules</font>
                                        </li>

                                        <li>
                                            <font size="4" style="font-family: arial,verdana;">Solar Rack Mounts</font>
                                        </li>

                                        <li>
                                            <font size="4" style="font-family: arial,verdana;"> Roofing Materials</font>
                                        </li>

                                        <li>
                                            <font size="4" style="font-family: arial,verdana;"> Framing Lumber Species
                                            </font>
                                        </li>

                                    </ul>
                                    </font>


                                    <font size="4" style="font-family: arial,verdana;">
                                        <font style="font-family: arial,verdana;">
                                            <font size="5"><strong>iRooFA</strong></font>
                                            <font size="3"> <sup>TM</sup></font>
                                            <font size="4"> reports include: <br>
                                            </font>
                                        </font>
                                    </font>

                                    <font size="4" style="font-family: arial,verdana;">
                                        <ul>

                                            <li>Your logo and company address </li>

                                            <li>Project Key Map </li>
                                            <li>Wind / Snow calculations</li>
                                            <li>
                                                Graphics
                                            </li>
                                            <li>Finite Element Strutural Analysis</li>


                                        </ul>
                                    </font>
                                    </font>
                                </div>






                            </div>
                            <p>
                                <font size="4" style="font-family: arial,verdana;">


                                    <b>
                                        <font size="5">Princeton Engineering</font>
                                    </b> also offers electrical and structural plan set review / signing / sealing in 23
                                    states: AZ, CA, CO, CT, DC, DE, FL, HI, IL, MA, MD, MI, MN, NC, NH, NJ, NV, NY, OH, PA,
                                    RI, VA and WI.

                                    <br><br>

                                    All engineering and review work is performed by our personnel in our offices in the <b>
                                        <font size="5">USA.</font>
                                    </b>


                                </font>
                                <br>

                            </p>
                            <img src="https://www.princeton-engineering.com/images/map.jpg" alt="" style="width: 100%" />
                            <p><br></p>
                            
                            <table style="width: 100%">
                                <tr>
                                    <td style="width:33%">
                                    </td>
                                    <td style="width:33%">
                                        <font size="4" style="font-family: arial,verdana;">
                                            <font style="font-family: arial,verdana;">
                                                <font size="6">

                                                    <div align="center">
                                                        <div style="padding:5px;display:table;">
                                                            <div class="link-button-wrapper">
                                                                <a
                                                                    href="https://www.princeton-engineering.com/framinganalysis.html">
                                                                    <h1>
                                                                        <font style="font-family: arial,verdana;">
                                                                            <font color="#000000">
                                                                                <font size="5">
                                                                                    What does <br>iRooFA cost?
                                                                                </font>
                                                                            </font>
                                                                        </font>
                                                                    </h1>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </font>
                                            </font>
                                        </font>
                                    </td>


                                    <td style="width:33%">
                                    </td>
                                </tr>
                            </table>






                            </font>
                            </p>


















                            <hr style="width: 100%; height: 2px;"><br>
                            <big>
                                <font style="font-family: arial,verdana;">
                                    <font size="3"><big><span style=""></span> </big></font>
                                </font>
                            </big>




                        </td>




                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><br>
                        </td>


                        <td style="vertical-align: top; text-align: center;">
                            <font style="font-family: arial,verdana;">
                                <font size="3">
                                    <font>
                                        <font size="6"><b>Princeton
                                                Engineering and Solar</b></font>
                                    </font>
                                </font>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td height="96" style="vertical-align: top;"><br>
                        </td>
                        <td style="vertical-align: top;">
                            <table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="2">
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top; text-align: center;">
                                            <table style="width: 100%; text-align: left;" border="0" cellpadding="2"
                                                cellspacing="2">
                                                <tbody>
                                                    <tr>
                                                        <td height="76" style="vertical-align: top; text-align: center;">
                                                            <font style="font-family: arial,verdana;">
                                                                <font size="3">
                                                                    <font size="6">
                                                                        <font size="3"><br>
                                                                        </font>
                                                                    </font>
                                                                </font>
                                                            </font>
                                                            <font style="font-family: arial,verdana;">
                                                                <font size="3">
                                                                    <font size="6">
                                                                        <font size="3"></font>
                                                                    </font>
                                                                </font>
                                                            </font>
                                                        </td>
                                                        <td style="vertical-align: top; text-align: center;">
                                                            <p>
                                                                <font style="font-family: arial,verdana;">
                                                                    <font size="3">35091
                                                                        Paxson Road<br>
                                                                        Round Hill, VA&nbsp; 20141<br>
                                                                        Tel:&nbsp; 540.313.5317<br>
                                                                        Fax: 877.455.5641
                                                                    </font>
                                                                </font>
                                                            </p>
                                                        </td>
                                                        <td style="vertical-align: top; text-align: center;">
                                                            <font style="font-family: arial,verdana;">
                                                                <font size="3">
                                                                    <font size="6">
                                                                        <font size="3"><br>
                                                                        </font>
                                                                    </font>
                                                                </font>
                                                            </font>
                                                            <font style="font-family: arial,verdana;">
                                                                <font size="3">
                                                                    <font size="6">
                                                                        <font size="3"></font>
                                                                    </font>
                                                                </font>
                                                            </font>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; text-align: center;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top;"><br>
                        </td>
                        <td style="vertical-align: top; text-align: center;">
                            <font style="font-family: arial,verdana;">
                                <font size="3">For more information, contact us at: <a
                                        href="mailto:Info@PrincetonEngineering.com">Info@PrincetonEngineering.com</a></font>
                            </font>
                        </td>
                    </tr>


                </tbody>
            </table>
            <font style="font-family: arial,verdana;">
                <font size="3">
                    <font>
                        <font size="6">
                            <font>
                                <font size="3"><br>
                                </font>
                            </font>
                        </font>
                    </font>
                </font>
            </font><br>
        </div>
    </body>

    </html>
@endsection
