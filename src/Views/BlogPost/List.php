<?php use ServerBlog\Models\User ?>
<!-- <?php var_dump($_SESSION); ?> -->
<section id="gallery">
  <div class="container">
    <div class="row">
      <?php if (!empty($_SESSION["error"])):?>
        <?= '<div class="col-12 alert-danger text-center my-3">'. $_SESSION["error"] .'</div>' ?>
      <?php endif;?>

      <?php foreach($_SESSION['list'] as $post): ?>
        <div class="col-lg-4 my-4">
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
              <a href="" class="btn btn-outline-success btn-sm">Read More</a>
              <a href="" class="btn btn-outline-danger btn-sm"><i class="far fa-heart"></i></a>
            </div>
          </div>
        </div>
      <?php endforeach;?>

    </div> <!-- End row -->
  </div>  <!-- End container -->
</section>
