<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Princeton Engineering</title>

        <meta name="description" content="Princeton Engineering">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="Princeton Engineering">
        <meta property="og:site_name" content="PrincetonEngineering">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <style>
            body {
                padding: 0;
                margin: 0;
                font-family: "Oxygen", sans-serif;
            }
            
            .error-wall {
                width: 100%;
                height: 100%;
                position: fixed;
                text-align: center;
            }
            .error-wall.load-error {
                background-color: #f3785e;
            }
            .error-wall.matinence {
                background-color: #a473b1;
            }
            .error-wall.missing-page {
                background-color: #00bbc6;
            }
            .error-wall .error-container {
                display: block;
                width: 100%;
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                -webkit-transform: translate(-50%, -50%);
                -moz-transform: translate(-50%, -50%);
            }
            .error-wall .error-container h1 {
                color: #fff;
                font-size: 80px;
                margin: 0;
            }
            @media (max-width: 850px) {
                .error-wall .error-container h1 {
                font-size: 65px;
                }
            }
            .error-wall .error-container h3 {
                color: #464444;
                font-size: 34px;
                margin: 0;
            }
            @media (max-width: 850px) {
                .error-wall .error-container h3 {
                font-size: 25px;
                }
            }
            .error-wall .error-container h4 {
                margin: 0;
                color: #fff;
                font-size: 40px;
            }
            @media (max-width: 850px) {
                .error-wall .error-container h4 {
                font-size: 35px;
                }
            }
            .error-wall .error-container p {
                font-size: 15px;
            }
            .error-wall .error-container p:first-of-type {
                color: #464444;
                font-weight: lighter;
            }
            .error-wall .error-container p:nth-of-type(2) {
                color: #464444;
                font-weight: bold;
            }
            .error-wall .error-container p.type-white {
                color: #fff;
            }
            @media (max-width: 850px) {
                .error-wall .error-container p {
                font-size: 12px;
                }
            }
            @media (max-width: 390px) {
                .error-wall .error-container p {
                font-size: 10px;
                }
            }
        </style>
    </head>
    <body>
    <div class="error-wall load-error">
        <div class="error-container">
            <h1>Sorry...</h1>
            @if($reason)
            <h3>{{ $reason }}</h3>
            @endif
            <p>If you cannot solve the problem, we reccomend you call<br> Super Admin at (908) 507-5500.<br> Or email at RPantel@Princeton-Engineering.com</p>
        </div>
    </div>
    </body>
</html>
