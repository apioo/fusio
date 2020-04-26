<!DOCTYPE>
<html>
<head>
  <title>Fusio - Apps</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<br>
<br>
<br>

<div class="container">
  <h3>Fusio - Apps</h3>

  <p>This folder contains apps which help to work with the Fusio API. All apps
  are available at the Fusio <a href="https://www.fusio-project.org/marketplace">marketplace</a>.
  You can install an app either through the Fusio backend or the following CLI
  command: <code>php bin/fusio marketplace:install [name]</code></p>

  <p>The following apps are currently available:</p>

  <ul>
  <?php
  $items = scandir(__DIR__);
  foreach($items as $item) {
    if ($item[0] !== '.' && is_dir(__DIR__ . '/' . $item)) {
      echo '<li><a href="./' . $item . '">' . ucfirst($item) . '</a></li>';
    }
  }
  ?>
  </ul>

  <p>More information about Fusio at <a href="https://www.fusio-project.org/">fusio-project.org</a>.</p>
</div>

</body>
</html>
