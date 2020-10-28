<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/347a72fb43.js" crossorigin="anonymous"></script>

    <!-- CSS backend -->
    <?php if (isset($_SESSION['css'])): ?>
      <?php foreach ($_SESSION['css'] as $css): ?>
        <?= '<link rel="stylesheet" href="/css/'. $css .'">' ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <title><?= $_SESSION['titulo'] ?> </title>
  </head>
  <body>

    <?php require_once($_SESSION['page']) ?> 

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>

    <?php if (isset($_SESSION['js'])): ?>
      <?php foreach ($_SESSION['js'] as $js): ?>
        <?= '<link rel="stylesheet" href="/css/'. $css .'">' ?>
      <?php endforeach; ?>
      <?= "<script src='/js/$js'></script>" ?>
    <?php endif; ?>
  </body>
</html>