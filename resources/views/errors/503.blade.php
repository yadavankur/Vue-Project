<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Service Unavailable</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 64px;
        }
        .sub-title {
            font-size: 40px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <h3 class="text-center">
            <img src="/images/logo.png" alt="Dowell"/>
        </h3>
        <div class="title">
            {{ json_decode(file_get_contents(storage_path('framework/down')), true)['message'] }}<br>
        </div>
        <div class="sub-title">
            Sorry, the system is in the maintenance mode.<br>
            Be right back soon.<br>
        </div>
    </div>
</div>
</body>
</html>
