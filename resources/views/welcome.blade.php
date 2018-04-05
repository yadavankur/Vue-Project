<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <!--<script  src="https://code.jquery.com/jquery-3.1.1.min.js"  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="  crossorigin="anonymous"></script>-->
        <link rel="stylesheet" href="css/app.css">
        <title>{{ config('app.name') }}</title>
        <script>
          window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'companyName' => config('app.company'),
            'siteName'  => config('app.name'),
            'apiDomain' => config('app.url').'/api',
            'projectName'  => config('app.project_name'),
          ]); ?>
        </script>
    </head>
    <body class="body">
        <div class="app">
            <div id="app"></div>
        </div>
    </body>
    <script src="js/app.js" charset="utf-8"></script>
</html>
