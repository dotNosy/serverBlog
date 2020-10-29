<?php 
  use ServerBlog\Models\User;
  use ServerBlog\Models\BlogPostModel;

  $user = User::getUser();
?>

<section id="gallery">
  <div class="container">
    <div class="row">
      <?php if (!empty($_SESSION["error"])):?>
        <?= '<div class="col-12 alert-danger text-center my-3">'. $_SESSION["error"] .'</div>' ?>
      <?php endif;?>

      <?php foreach($_SESSION['list'] as $post): ?>
        <div class="col-lg-4 my-4">
          <?php //var_dump($post) ?>
          <div class="card">
            <img src="https://images.unsplash.com/photo-1477862096227-3a1bb3b08330?ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=60" alt="" class="card-img-top">
            <div class="card-body">
              <!-- TITULO -->
              <h5 class="card-title"><?= $post['title'] ?></h5>

              <!-- TEXTO -->
              <p class="card-text">
                <?= substr($post['text'], 0, (strlen($post['text']) > 100) ? 100 : strlen($post['text'])) ?>
              </p>

              <!-- AUTHOR -->
              <?php if(!empty($post['user_id'])): ?>
                <p class="lead"><a href="#">Por: <?= User::getUsernameById(intval($post['user_id'])) ?></a></p>
              <?php endif;?>

              <!-- FECHA CREACION -->
              <p>Creado el: <?= $post['date'] ?></p>

              <!-- BOTONES -->
              <form <?="action='/post/addFavoritesOrFeed/'"?>  
                method="post">
                <a <?="href='/post/view/".$post["id"]."'"?>  class="btn btn-outline-success btn-sm mx-2">Read More</a>
                <input type="hidden" name='id' <?= "value='".$post['id']."'" ?>>
                <!-- Favoritos -->
                <button 
                  <?php echo BlogPostModel::isInFavorites(intval($post['id']), intval($user->id)) ? "class='btn btn-danger btn-sm mx-2'" : "class='btn btn-outline-danger btn-sm mx-2'";  ?>
                  type="submit" 
                  name="type"
                  value="favoritos"
                  data-toggle="tooltip" 
                  data-placement="top" 
                  <?php echo BlogPostModel::isInFavorites(intval($post['id']), intval($user->id)) ? "title='Remove from Favoritos'" : "title='Add to Favoritos'";  ?>
                  >
                  <i class="far fa-heart"></i>
                </button>
                <!-- Feed -->
                <button 
                  type="submit" 
                  name="type"
                  value="feed"
                  <?php echo BlogPostModel::isInFeed(intval($post['id']), intval($user->id)) ? "class='btn btn-primary btn-sm mx-2'" : "class='btn btn-outline-primary btn-sm mx-2'";  ?> 
                  data-toggle="tooltip" 
                  data-placement="top" 
                  <?php echo BlogPostModel::isInFeed(intval($post['id']), intval($user->id)) ? "title='Remove from Feed'" : "title='Add to Feed'";  ?>>
                  <i class="far fa-plus-square"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach;?>

    </div> <!-- End row -->
  </div>  <!-- End container -->
</section>
