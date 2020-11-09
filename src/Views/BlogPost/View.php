<?php if(!empty($_SESSION["blogPost"]))
{ 
  $post=$_SESSION["blogPost"];
}?>

<?php 
  use ServerBlog\Models\User;
  use ServerBlog\Models\BlogPostModel;

  $user = User::getUser();
?>

<!-- MENU -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Start Bootstrap</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8">

      <!-- Title -->
      <h1 class="mt-4"><?= $post->title ?></h1>

      <!-- Author -->
      <p class="lead">
        by
        <a href="#"><?= $_SESSION["autor"] ?></a>
      </p>

      <hr>

      <!-- Date/Time -->
      <p>Posted on <?= $post->date ?></p>

      <!-- Categorias -->
      <?php foreach($_SESSION["categorias"] as $categoria): ?>
        <a href="/post/categoria/<?=strtolower($categoria->name)?>" class="badge badge-dark"><?= $categoria->name ?></a>
      <?php endforeach;?>

      <hr>

      <!-- Preview Image -->
      <?php foreach($_SESSION["imgs"] as $img): ?>
        <?php if($img->pos == "starting"): ?>
          <img class="img-fluid" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($img->img); ?>" /> 
        <?php endif;?>
      <?php endforeach;?>

      <hr>

      <!-- Post Content -->
      <p class="lead my-5"><?= $post->text ?></p>

      <hr>

      <!-- SILDER IMAGENES -->
      <?php if(!empty($_SESSION["imgs_slider"])): ?>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <?php for ($i=0; $i < count($_SESSION["imgs_slider"]); $i++): ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="<?=$i?>" <?= $i == 0 ? "class='active'": ""?>></li>
              <?php endfor;?>
            </ol>
            <div class="carousel-inner">
              <?php for ($i=0; $i < count($_SESSION["imgs_slider"]); $i++): ?>
                <div class="carousel-item <?= $i == 0 ? "active": ""?>">
                  <img class="d-block w-100" style="widht:100%;height:450px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($_SESSION["imgs_slider"][$i]->img); ?>">
                </div>
              <?php endfor;?>
            </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
        </div>
      <?php endif; ?>
      <!-- Comments Form -->
      <div class="card my-4">
        <div class="card-header">
          <h5 >Leave a Comment:</h5>
        </div>
        <div class="card-body">
        <?php if (!empty($_SESSION["error"])):?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">   
              <?=  $_SESSION["error"] ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif;?>
          <?php if (!empty($_SESSION["msg_comment"])):?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?=  $_SESSION["msg_comment"] ?>
              <?php unset($_SESSION["msg_comment"]) ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif;?>
          <form method = "POST" action="/post/addComment/">
            <div class="form-group">
            <input type="hidden" name="id" <?= (!empty($post->id) ? "value='$post->id'" : '') ?>>
              <textarea class="form-control" rows="3" name = "text"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="comment">Submit</button>
          </form>
        </div>
      </div>

      <!-- Single Comment -->
      <?php foreach($_SESSION["comments"] as $comment): ?>
        <div class="card my-4">
          <div class="card-header">
          <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($comment->avatar); ?>" /> 
          <div style="margin-top:-7%; margin-left:12%">
          <h4 style="text-align:top">
            <?=  User::getUsernameById(intval($comment->user_id))?>
          </h4>
              <!-- ELIMINAR -->
              <?php if(!empty($user) && ($user->id == $comment->user_id || $user->id == $post->user_id)):?>
              <form action="/post/deleteComment" method="POST">
                <input type="hidden" name="id" <?= "value='$comment->id'"?>>
                <button
                  name="comment"
                  type="submit" 
                  class="btn btn-outline-danger btn-sm float-right"
                  data-toggle="tooltip" 
                  data-placement="top"
                  title="Eliminar">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            <?php endif; ?>
            </div>
          </div>
          <div class="card-body">
            <p><?= $comment->text ?></p>
          </div>
          <blockquote class="blockquote text-right">
            <footer class="blockquote-footer mr-3"><?= $comment->date ?></footer>
          </blockquote>
          <hr>
          <!-- Responder -->
          <form class="form-inline col-12" action="/post/addComment/" method="POST">
            <input type="hidden" name="id" <?= (!empty($post->id) ? "value='$post->id'" : '') ?> >
            <input type="hidden" name="id_padre" <?= (!empty($comment->id) ? "value='$comment->id'" : '') ?> >

              <label class="sr-only" for="inlineFormInputGroupUsername2">Username</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">Responde a <?=  User::getUsernameById(intval($comment->user_id))?></div>
                </div>
                <textarea name="text" id="" cols="40" rows="1"></textarea>
              </div>
              <button type="submit" name="comment" class="btn btn-primary mb-2">Go!</button>
          </form>

          <!-- Listar Respuestas -->
          <?php foreach(BlogPostModel::getAnswer(intval($comment->id)) as $answer): ?>
            <div class="card ml-5 my-4">
              <div class="card-header">
              <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($comment->avatar); ?>" /> 
              <div style="margin-top:-7%; margin-left:12%">
                <h4 style="text-align:top;">
                <?=  User::getUsernameById(intval($answer->user_id))?>
                </h4>
                  <!-- ELIMINAR -->
                  <?php if(!empty($user) && ($user->id == $answer->user_id || $user->id == $post->user_id)):?>
                  <form action="/post/deleteComment" method="POST">
                    <input type="hidden" name="id" <?= "value='$answer->id'"?>>
                    <button
                      name="comment"
                      type="submit" 
                      class="btn btn-outline-danger btn-sm float-right"
                      data-toggle="tooltip" 
                      data-placement="top"
                      title="Eliminar">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                <?php endif; ?>
                </div>
              </div>
              <div class="card-body">
                <p><?= $answer->text ?></p>
              </div>
              <blockquote class="blockquote text-right">
                <footer class="blockquote-footer mr-3"><?= $answer->date ?></footer>
              </blockquote>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Sidebar Widgets Column -->
    <div class="col-md-4">

      <!-- Search Widget -->
      <div class="card my-4">
        <h5 class="card-header">Search</h5>
        <div class="card-body">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-append">
              <button class="btn btn-secondary" type="button">Go!</button>
            </span>
          </div>
        </div>
      </div>

      <!-- Categories Widget -->
      <div class="card my-4">
        <h5 class="card-header">Categorias</h5>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <ul class="list-unstyled mb-0">
              <?php foreach($_SESSION["categoriasTodas"] as $categoria): ?>
                <li>
                  <a href="/post/categoria/<?=strtolower($categoria->name)?>"><?=$categoria->name?></a>
                </li>
                <?php if(ceil(count($_SESSION["categoriasTodas"])/2)): ?>
                  </div>
                  <div class="col-lg-6">
                  <ul class="list-unstyled mb-0">
                <?php endif; ?>
              <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- SIDE IMAGENES-->
      <div class="card my-4">
        <h5 class="card-header">Side Widget</h5>
        <div class="card-body">
          <?php foreach($_SESSION["imgs"] as $img): ?>
            <?php if($img->pos == "side"): ?>
              <img class="img-thumbnail my-4" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($img->img); ?>" /> 
            <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>

    </div>
  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 bg-dark">
  <div class="container">
    <p class="m-0 text-center text-white">Copyright &copy; Your Website 2020</p>
  </div>
  <!-- /.container -->
</footer>
