<?php if(!empty($_SESSION["profile"])) { 
    $profile=$_SESSION["profile"];
} ?>


<script>
//kontadore bat erabiliko dugu kontroladorea funtzionatzeko
var clicks = 1

//funtzioei cooldown bat ipintzeko baribleak
var delay = 600;
var lastClick = 0;

var delay2 = 600;
var lastClick2 = 0;

//click egitean resize() egingo du baina berriz klik egitean resizeM() egingo du
  function controlador(){
    if(clicks==1){
      resize(4);
      clicks=0;
    }
    else{
      resizeM(4);
      clicks=1;
    }
  }

// click egitean zoom egiteko
function resize(direction) {
  //cooldown ez bugueatzeko
    if (lastClick >= (Date.now() - delay))
        return;
    lastClick = Date.now();
  //resize-en tamaina ajustatzeko
  var size = 90 * direction;

  var element = document.getElementById('zoom');

  //avatar-aren posizioa hartuko du
  var positionInfo = element.getBoundingClientRect();

  element.style.width = positionInfo.width+size+'px';
  element.style.height = positionInfo.height+size+'px';

  document.body.style.backgroundColor = "#2E2F2F";
  document.getElementById("container").style.backgroundColor = "#2E2F2F";
}

//berriz click egitean zoom-a kenduko du
function resizeM(direction) {
  //cooldown ez bugueatzeko
  if (lastClick2 >= (Date.now() - delay2))
        return;
    lastClick2 = Date.now();
  //resizeM-n tamaina ajustatzeko
  var size = 90 * direction;

  var element = document.getElementById('zoom');
  
  //avatar-aren posizioa hartuko du
  var positionInfo = element.getBoundingClientRect();

  element.style.width = positionInfo.width-size+'px';
  element.style.height = positionInfo.height-size+'px';

  document.body.style.backgroundColor = "white";
  document.getElementById("container").style.backgroundColor = "white";
}
</script>
  
<div class="container" id="container">
    <hr>
  <div class="card bg-light" id="container">
    <article class="card-body mx-auto" max-width="700px" id="container">
      <h4 class="card-title mt-3 text-center">PERFIL</h4>
      <img class="img-thumbnail" id="zoom" onclick="controlador()" width="500px" height="500px" src="<?= (!empty($profile->avatar) ? "data:image/jpg;charset=utf8;base64,".base64_encode($profile->avatar) : "https://st.depositphotos.com/2101611/3925/v/600/depositphotos_39258143-stock-illustration-businessman-avatar-profile-picture.jpg" ); ?>" />
      <form action="/profile/edit" method="POST" enctype="multipart/form-data">
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
        <!-- Imagen -->
        <div class="form-group input-group">
                <label class="input-group-prepend">Insertar imagen</label>
                <div class="input-group-text">
                <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
                  <input name="avatar[]" type="file" class="form-control-file" accept=".png, .jpg, .gif" multiple>
                  <div id="listImgs" class="my-3">
                  </div>
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
