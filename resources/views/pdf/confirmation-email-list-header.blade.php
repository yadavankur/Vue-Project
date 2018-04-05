<!DOCTYPE html>
<html lang='en-US'>
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style type="text/css">
            .header{
                /*position: fixed;*/
                top:0;
                width:100%;
            }
            .container {
                width: 100%;
            }
            .supervisor {
                display: inline-block;
                vertical-align: middle;
                width:33%;
            }
            .row-title {
                display: inline-block;
                vertical-align: middle;
            }
            .row-content {
                display: inline-block;
                vertical-align: middle;
            }
            hr {
                margin-top: 0px;
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="container">
                <br>
                <h2>Weekly Production & Service Confirmations </h2>
            </div>
            <br>
            <div class="container ">
                <div class="row-title"><h4>Date Range:</h4></div> <div class="row-content">{{ $content['dateRangeFrom']->format('d/m/Y') }} - {{ $content['dateRangeTo']->format('d/m/Y') }}</div>
            </div>
            <div class="container ">
                <div class="row-title"><h4>Rep:</h4></div> <div class="row-content">{{ $content['repName'] }}</div>
            </div>
            <div class="container ">
                <div class="row-title"><h4>Customer:</h4></div> <div class="row-content">{{ $content['customerName'] }}</div>
            </div>
            <div class="container">
                <div class="supervisor">
                    <div class="row-title "><h4>Supervisor:</h4></div> <div class="row-content">{{ $content['supervisorName'] }}</div>
                </div>
                <div class="supervisor">
                    <div class="row-title "><h4>Mobile No.:</h4></div> <div class="row-content">{{ $content['supervisorMobile'] }}</div>
                </div>
                <div class="supervisor">
                    <div class="row-title "><h4>Email:</h4></div> <div class="row-content">{{ $content['supervisorEmail'] }}</div>
                </div>
            </div>
            <hr size=1>
        </div>
    </body>
</html>
