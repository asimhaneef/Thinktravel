<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="resources/css/app.css" />
 <link rel="stylesheet" href="resources/css/js/app.js" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div id="app">
    <router-view></router-view>
        <h1 class="text-3xl font-bold underline">
            Hello world!
        </h1>
  </div>
</body>
</html>