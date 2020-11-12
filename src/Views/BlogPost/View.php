<?php if(!empty($_SESSION["blogPost"]))
{ 
  $post=$_SESSION["blogPost"];
  $defaultImage = "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";
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
        <a <?= "href='/post/feed/".$_SESSION["autor"] ."'"?>><?= $_SESSION["autor"] ?></a>
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
      
      <!-- LOADER -->
      <div class="text-center mt-5">
        <div id="spinner" class="spinner-grow text-danger" style="display:none;width: 7rem; height: 7rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>    
      </div>
      
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
            <div class="form-group">
              <input type="hidden" name="id" <?= (!empty($post->id) ? "value='$post->id'" : '') ?>>
              <textarea class="form-control" rows="3" name = "text"></textarea>
              <button type="submit" class="btn btn-primary mt-4" name="comment">Submit</button>
            </div>
        </div>
      </div>

        <!-- Single Comment -->
        <div id="comments">
          <?php foreach($_SESSION["comments"] as $comment): ?>
            <div class="card my-4">
              <div class="card-header">
                <?php if(!empty($comment->avatar)):?>
                  <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($comment->avatar); ?>" />
                <?php else:?>
                  <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?= $defaultImage?>"/>
                <?php endif;?>
                <div style="margin-top:-7%; margin-left:12%">
                  <h4 style="text-align:top">
                    <?=  User::getUsernameById(intval($comment->user_id))?>
                  </h4>
                  <!-- ELIMINAR -->
                  <?php if(!empty($user) && ($user->id == $comment->user_id || $user->id == $post->user_id)):?>
                    <button
                      id="<?= $comment->id?>"
                      name="eliminar"
                      class="btn btn-outline-danger btn-sm float-right"
                      data-toggle="tooltip" 
                      data-placement="top"
                      title="Eliminar">
                      <i class="fas fa-trash-alt"></i>
                    </button>
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
              <div class="col-12">
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Responde a <?=  User::getUsernameById(intval($comment->user_id))?></div>
                    </div>
                      <input type="hidden" name="id" <?= "value='$post->id'" ?> >
                      <input type="hidden" name="id_padre" <?=  "value='$comment->id'"?>>
                      <textarea name="text" id="" cols="40" rows="1"></textarea>
                      <button type="submit" name="comment" class="btn btn-primary mb-2 ml-2">Go!</button>
                  </div>
              </div>
              <div id="<?=$comment->id?>-response">
                  <!-- Listar Respuestas -->
                  <?php foreach(BlogPostModel::getAnswer(intval($comment->id)) as $answer): ?>
                    <div class="card ml-5 my-4">
                      <div class="card-header">
                      <?php if(!empty($comment->avatar)):?>
                        <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($comment->avatar); ?>" />
                      <?php else:?>
                        <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?= $defaultImage?>"/>
                      <?php endif;?>
                      <div style="margin-top:-7%; margin-left:12%">
                          <h4 style="text-align:top;">
                          <?=  User::getUsernameById(intval($answer->user_id))?>
                          </h4>
                          <!-- ELIMINAR -->
                          <?php if(!empty($user) && ($user->id == $answer->user_id || $user->id == $post->user_id)):?>
                            <button
                              id="<?= $answer->id?>"
                              name="eliminar"
                              class="btn btn-outline-danger btn-sm float-right"
                              data-toggle="tooltip" 
                              data-placement="top"
                              title="Eliminar">
                              <i class="fas fa-trash-alt"></i>
                            </button>
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
            </div>
          <?php endforeach; ?>
        </div>
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
