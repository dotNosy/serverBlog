<?php if(!empty($_SESSION["profile"]))
{ 
    $profile=json_decode($_SESSION["profile"]);
} ?>
<div class="container">
    <hr>
  <div class="card bg-light">
    <article class="card-body mx-auto" style="max-width: 400px">
      <h4 class="card-title mt-3 text-center">PERFIL</h4>
      <form action="/profile/edit" method="POST">
      <!--  action="/profile" method="POST" -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
          </div>
          <input
            name="name"
            class="form-control"
            placeholder="Nombre"
            type="text"
            <?="value='$profile->name'"?>
          />
        </div>
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
          </div>
          <input
            name="surname"
            class="form-control"
            placeholder="Apellido"
            type="text"
            <?="value='$profile->surname'"?>
          />
        </div>
        <!-- form-group// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">
              <i class="fa fa-envelope"></i>
            </span>
          </div>
          <input
            name="email"
            class="form-control"
            placeholder="Email"
            type="email"
            <?="value='$profile->email'"?>
          />
        </div>
        <!-- form-group// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">
              <i class="fa fa-user"></i>
            </span>
          </div>
          <input
            name="date"
            class="form-control"
            placeholder="Fecha de nacimiento"
            type="date"
            <?="value='$profile->birth_date'"?>
          />
        </div>
        <div class="form-group">
                <label class="">Insertar imagen</label>
                <div class=">
                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                  <input name="imagen" type="file" class="form-control-file">
                </div>
              </div>
        <div class="form-group">
          <button type="submit" name="update" class="btn btn-primary btn-block">
            Modificar datos
          </button>
        </div>
      </form>
      <form action="/profile/editPassword" method="POST">
        <div>
        Cambiar contraseña:
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">
              <i class="fa fa-user"></i>
            </span>
          </div>
          <input
            name="password"
            class="form-control"
            placeholder="Contraseña"
            type="password"
          />
        </div>
        <div>
        Repite contraseña:
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">
              <i class="fa fa-user"></i>
            </span>
          </div>
          <input
            name="repeatPassword"
            class="form-control"
            placeholder="Repite Contraseña"
            type="password"
          />
        </div>
        <div class="form-group">
          <button type="submit" name="cambiarContraseña" class="btn btn-primary btn-block">
            Resetear Contraseña
          </button>
        </div>
        <?php if (!empty($_SESSION["errorContraseña"])):?>
                                    <?= '<div class="col-12 alert-danger text-center my-3">'. $_SESSION["errorContraseña"] .'</div>' ?>
        <?php endif;?>
      </form>
        
        <!-- form-group end.// -->
        <!-- form-group// -->
        <!-- form-group// -->
    </article>
  </div>
  <!-- card.// -->
</div>
<!--container end.//-->
</article>
