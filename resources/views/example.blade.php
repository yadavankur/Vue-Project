<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chatroom</title>

        <link rel="stylesheet" href="css/app.css">
        <script>
          window.Laravel = <?php echo json_encode([
              'csrfToken' => csrf_token(),
          ]); ?>
        </script>
    </head>
    <body>
        <div id="app">
            <h1>AppMain</h1>
            <appmain></appmain>
        </div>
        <script src="js/app.js" charset="utf-8"></script>
    </body>
</html>