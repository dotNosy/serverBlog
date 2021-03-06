<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Jquery Alerts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="/css/sidebar.css">

    <!-- CSS backend -->
    <?php if (!empty($_SESSION['css'])): ?>
      <?php foreach ($_SESSION['css'] as $css): ?>
        <?= '<link rel="stylesheet" href="/css/'. $css .'">' ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <title><?= $_SESSION['titulo'] ?> </title>
  </head>
  <body>
  <!-- Bootstrap row -->
  <div class="row" id="body-row">
      <!-- Sidebar -->
      <div id="sidebar-container" class="sidebar-expanded d-none d-md-block"><!-- d-* hiddens the Sidebar in smaller devices. Its itens can be kept on the Navbar 'Menu' -->
          <!-- Bootstrap List Group -->
          <ul class="list-group">
              <!-- Separator with title -->
              <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                  <small>MAIN MENU</small>
              </li>
              
              <?php
                use ServerBlog\Models\User;
                $user = User::getUser();
              ?>
              
              <?php if(empty($user)): ?>
                <!-- LOGIN -->
                <a href="/login/" class="bg-dark list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-start align-items-center">
                      <span class="fa fa-sign-in-alt fa-fw mr-3"></span>
                      <span class="menu-collapsed">Login</span>
                  </div>
                </a>     
              <?php endif; ?>
              <?php if(!empty($user)): ?>
                <!-- PROFILE -->
                <a href="/profile/" class="bg-dark list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-start align-items-center">
                      <span class="fa fa-user fa-fw mr-3"></span>
                      <span class="menu-collapsed">Perfil</span>
                  </div>
                </a>
                <!-- NOTIFICACIONES -->
                <a class="bg-dark list-group-item list-group-item-action showNotifications" data-toggle="modal" data-target="#staticBackdrop">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa fa-bell fa-fw mr-3"></span>
                        <span class="menu-collapsed">Notificaciones<span id="notCount" class="badge badge-pill <?= ServerBlog\Models\Profile::getCountNotifications($user->id) > 0 ? 'badge-danger' : 'badge-primary'?> ml-2"><?= ServerBlog\Models\Profile::getCountNotifications($user->id)?></span></span>
                    </div>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Notificaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="false">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="notificaciones-content">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button id="borrarTodos" type="button" class="btn btn-danger">Borrar todos</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Separator with title -->
              <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                  <small>POSTS</small>
              </li>
              <!-- /END Separator -->

              <!-- TODOS LOS POSTS -->
              <a href="/post/all" class="bg-dark list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-start align-items-center">
                      <span class="fa fa-newspaper fa-fw mr-3"></span>
                      <span class="menu-collapsed">Todos</span>
                  </div>
              </a>
              <?php if(!empty($user)): ?>
               <!-- REDACTADOS -->
               <a href="/post/list" class="bg-dark list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fas fa-book fa-fw mr-3"></span>
                        <span class="menu-collapsed">Redactados</span>
                    </div>
                </a>
                <!-- FEED -->
                <a href="/post/feed" class="bg-dark list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa fa-id-badge fa-fw mr-3"></span>
                        <span class="menu-collapsed">Feed</span>
                    </div>
                </a>
                <!-- FAVORITOS -->
                <a href="/post/favoritos" class="bg-dark list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa fa-heart fa-fw mr-3"></span>
                        <span class="menu-collapsed">Favoritos</span>
                    </div>
                </a>
                <!-- ADD POST -->
                <a href="/post/add" class="bg-dark list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa fa-plus-square fa-fw mr-3"></span>
                        <span class="menu-collapsed">Add</span>
                    </div>
                </a>
              <?php endif; ?>
              <!-- Separator without title -->
              <li class="list-group-item sidebar-separator menu-collapsed"></li>            
              <!-- /END Separator -->
              <?php if (!empty($user)): ?>
                <!-- LOGOUT -->
                <a href="/login/logout" class="bg-dark list-group-item list-group-item-action">
                  <div class="d-flex w-100 justify-content-start align-items-center">
                      <span class="fa fa-user-slash fa-fw mr-3"></span>
                      <span class="menu-collapsed">Logout</span>
                  </div>
                </a>     
              <?php endif; ?>
              <a href="#" data-toggle="sidebar-colapse" class="bg-dark list-group-item list-group-item-action d-flex align-items-center">
                  <div class="d-flex w-100 justify-content-start align-items-center">
                      <span id="collapse-icon" class="fa fa-2x mr-3"></span>
                      <span id="collapse-text" class="menu-collapsed">Collapse</span>
                  </div>
              </a>
          </ul><!-- List Group END-->
      </div><!-- sidebar-container END -->

      <!-- MAIN -->
      <div class="col">
        <?php require_once($_SESSION['page']) ?> 
      </div><!-- Main Col END -->
      
  </div><!-- body-row END -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/347a72fb43.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Custom JS -->
    <script src="/js/sidebar.js"></script>
    <script src="/js/Notifications.js"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

      //script dinamico
      <?php if (!empty($_SESSION["script"])) echo $_SESSION["script"]; ?>
    </script>

    <!-- JS dinamicos -->
    <?php if (!empty($_SESSION['js'])): ?>
      <?php foreach ($_SESSION['js'] as $js): ?>
        <?= "<script src='/js/$js'></script>" ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>
</html>