<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/main.css">
    <title>URL Shortener</title>
  </head>
  <body class="text-center">

    <form action='/' method='POST' class="url-form">
      <h1 class="h3 mb-3 font-weight-normal">URL Shortener</h1>
	  
      <input type="text" name="inputURL" class="form-control" placeholder="Full URL" required autofocus />
      <input type="text" name="inputShort" class="form-control" placeholder="Short name (optional)" />
      
      <input type="submit" class="btn btn-lg btn-primary btn-block" name="btnCreate" value="Create short URL" />
    </form>

    <script src="/js/jquery-3.3.1.slim.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>