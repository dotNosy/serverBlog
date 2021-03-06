<?php 
  use ServerBlog\Models\User;
  use ServerBlog\Models\BlogPostModel;

  $user = User::getUser();

  if(!empty($_SESSION["blogPost"])){ 
    $post=$_SESSION["blogPost"];
  }
?>

<!-- MENU -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">Mas de  "<?= $_SESSION["autor"] ?>"</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" <?= "href='/post/feed/".$_SESSION['autor']."'"?> >Feed
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link"<?= "href='/post/author/".$_SESSION['autor']."'"?>>Redactados</a>
        </li>
        <?php if (!empty($user->id) && $post->user_id == $user->id): ?>
          <li class="nav-item">
            <a class="nav-link" <?="href='/post/edit/".$post->id."'"?>>Editar</a>
          </li>
        <?php endif; ?>
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
                  <img class="rounded-circle" style="width:70px;heigth:70px;" src="https://st.depositphotos.com/2101611/3925/v/600/depositphotos_39258143-stock-illustration-businessman-avatar-profile-picture.jpg"/>
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
                      <?php if(!empty($answer->avatar)):?>
                        <img class="rounded-circle" style="width:70px;heigth:70px;" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($answer->avatar); ?>" />
                      <?php else:?>
                        <img class="rounded-circle" style="width:70px;heigth:70px;" src="https://st.depositphotos.com/2101611/3925/v/600/depositphotos_39258143-stock-illustration-businessman-avatar-profile-picture.jpg"/>
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
        <h5 class="card-header">Imagenes relacionadas</h5>
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
