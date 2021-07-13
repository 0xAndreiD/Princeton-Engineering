<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Project Files</title>

        <meta name="description" content="Princeton Engineering">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Princeton Engineering">
        <meta property="og:site_name" content="PrincetonEngineering">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <script src="{{ asset('js/sorttable.js') }}"></script>
        <style>
            * {
                padding:0;
                margin:0;
            }

            body {
                color: #333;
                font: 14px Sans-Serif;
                padding: 50px;
                background: #eee;
            }

            h1 {
                text-align: center;
                padding: 20px 0 12px 0;
                margin: 0;
            }
            h2 {
                font-size: 16px;
                text-align: center;
                padding: 0 0 12px 0; 
            }

            #container {
                box-shadow: 0 5px 10px -5px rgba(0,0,0,0.5);
                position: relative;
                background: white; 
            }

            table {
                background-color: #F3F3F3;
                border-collapse: collapse;
                width: 100%;
                margin: 15px 0;
            }

            th {
                background-color: #FE4902;
                color: #FFF;
                cursor: pointer;
                padding: 5px 10px;
            }

            th small {
                font-size: 9px; 
            }

            td, th {
                text-align: left;
            }

            a {
                text-decoration: none;
            }

            td a {
                color: #663300;
                display: block;
                padding: 5px 10px;
            }
            th a {
                padding-left: 0
            }

            td:first-of-type a {
                background: url("{{ asset('img/file.png') }}") no-repeat 10px 50%;
                padding-left: 35px;
            }
            th:first-of-type {
                padding-left: 35px;
            }

            td:not(:first-of-type) a {
                background-image: none !important;
            } 

            tr:nth-of-type(odd) {
                background-color: #E6E6E6;
            }

            tr:hover td {
                background-color:#CACACA;
            }

            tr:hover td a {
                color: #000;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>{{$projecttitle}}</h1>
            <table class="sortable">
                <thead>
                <tr>
                    <th style='width: 70%;'>Filename</th>
                    <th style='width: 10%;'>Folder</th>
                    <th style='width: 10%;'>Size</th>
                    <th style='width: 10%;'>Date Modified</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($reportfiles as $file)
                    <tr class='file'>
                        <td><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['filename']}}</a></td>
                        <td><a href="javascript:openReviewFile('{{$file['link']}}')">Report</a></td>
                        <td sorttable_customkey="{{$file['size']}}"><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['size'] / 1024 . 'KB'}}</a></td>
                        <td sorttable_customkey="{{$file['modifiedDate']}}"><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['modifiedDate']}}</a></td>
                    </tr>
                    @endforeach
                    @foreach($infiles as $file)
                    <tr class='file'>
                        <td><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['filename']}}</a></td>
                        <td><a href="javascript:openReviewFile('{{$file['link']}}')">IN</a></td>
                        <td sorttable_customkey="{{$file['size']}}"><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['size'] / 1024 . 'KB'}}</a></td>
                        <td sorttable_customkey="{{$file['modifiedDate']}}"><a href="javascript:openReviewFile('{{$file['link']}}')">{{$file['modifiedDate']}}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
    <script>
        var popUpWnds = [];

        window.onbeforeunload = function() {
            popUpWnds.forEach(popUp => {
                if (popUp && !popUp.closed)
                    popUp.close();
            });
        };
        window.onhashchange = function() {
            popUpWnds.forEach(popUp => {
                if (popUp && !popUp.closed)
                    popUp.close();
            });
        };

        function openReviewFile(link){
            popUpWnds.push(window.open(link, '_blank'));
        }
    </script>
</html>
