<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fusio - Apps</title>
  <link rel="icon" href="https://www.fusio-project.org/img/fusio_32px.png" type="image/png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body style="background:linear-gradient(#ffffff, #cccccc);">

<div class="container bg-light mb-xl-5 mt-xl-5 shadow-lg" style="max-width:640px;z-index:1024">
  <div class="text-center pt-4">
    <img src="data:image/gif;base64,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">
  </div>
  <div class="mt-5 pb-5 mb-3 border-bottom">
    <p class="lead">This folder contains installed apps which help to work with the Fusio API.
    All apps are available at the Fusio <a href="https://www.fusio-project.org/marketplace">marketplace</a>.
    You can install an app either through the Fusio backend or <abbr title="php bin/fusio marketplace:install [name]">CLI command</abbr>.</p>
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
  </div>
  <p class="text-muted text-end pb-3"><small>powered by <a href="https://www.fusio-project.org/">Fusio</a></small></p>
</div>

</body>
</html>
