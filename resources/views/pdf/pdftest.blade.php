<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style type="text/css">
        /*@page {*/
        /*}*/
        /*header {*/
            /*position: fixed;*/
            /*left: 0px;*/
            /*right: 0px;*/
            /*height: 182px;*/
            /*width: 100%;*/
        /*}*/
        /*footer {*/
            /*position: fixed;*/
            /*bottom: -60px;*/
            /*left: 0px;*/
            /*right: 0px;*/
            /*height: 50px;*/
        /*}*/
        /*p {*/
            /*page-break-after: always;*/
        /*}*/
        /*p:last-child {*/
            /*page-break-after: never;*/
        /*}*/

        thead:before, thead:after { display: none; } tbody:before, tbody:after { display: none; }
        .content{
            /*margin-top: 185px;*/
            width: 100%;
        }
        .container {
            width: 100%;
        }
        .page {
            page-break-after: always;
            page-break-inside: avoid;
        }
        .page:last-child{
            page-break-after: auto;
        }
        .header{
            /*position: fixed;*/
            top:0;
            width:100%;
        }
        .supervisor {
            display: inline-block;
            vertical-align: middle;
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

    @foreach ($pages as $page)

        <div class="page">
            {{--<header>--}}
                {{--<div class="header">--}}
                    {{--<div class="col-md-offset-0">--}}
                        {{--<h2 class="container-fluid col-md-12">Weekly Production & Service Confirmations </h2>--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<br>--}}
                    {{--<div class="container ">--}}
                        {{--<div class="row-title"><h4>Date Range:</h4></div> <div class="row-content">{{ $content['dateRangeFrom'] }} - {{ $content['dateRangeTo'] }}</div>--}}
                    {{--</div>--}}
                    {{--<div class="container ">--}}
                        {{--<div class="row-title"><h4>Customer:</h4></div> <div class="row-content">{{ $content['repName'] }}</div>--}}
                    {{--</div>--}}
                    {{--<div class="container">--}}
                        {{--<div class="supervisor">--}}
                            {{--<div class="row-title "><h4>Supervisor:</h4></div> <div class="row-content">{{ $content['customerName'] }}</div>--}}
                        {{--</div>--}}
                        {{--<div class="supervisor">--}}
                            {{--<div class="row-title "><h4>Mobile No.:</h4></div> <div class="row-content">{{ $content['supervisorMobile'] }}</div>--}}
                        {{--</div>--}}
                        {{--<div class="supervisor">--}}
                            {{--<div class="row-title "><h4>Email:</h4></div> <div class="row-content">{{ $content['supervisorEmail'] }}</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<hr size=1>--}}
                {{--</div>--}}
            {{--</header>--}}
            <div class="content">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>S/O #</th>
                        <th>Delivery Address</th>
                        <th>Delivery Date</th>
                        <th>Site Measure</th>
                        <th>Site Glaze</th>
                        <th>Install</th>
                        <th>General Service</th>
                        <th>Rectification</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->quote_id }}</td>
                            <td>{{ $item->order_id }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</body>
</html>

