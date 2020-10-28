<div class="container">
    <hr>
  <div class="card bg-light">
    <article class="card-body mx-auto" style="max-width: 400px">
      <h4 class="card-title mt-3 text-center">PERFIL</h4>
      <form action="/profile" method="POST">
      <!--  action="/profile" method="POST" -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
          </div>
          <input
            name=""
            class="form-control"
            placeholder="Nombre"
            type="text"
          />
        </div>
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
          </div>
          <input
            name=""
            class="form-control"
            placeholder="Apellido"
            type="text"
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
            name=""
            class="form-control"
            placeholder="Email"
            type="email"
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
            name=""
            class="form-control"
            placeholder="Fecha de nacimiento"
            type="date"
          />
        </div>
        <!-- form-group end.// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
          </div>
          <input
            class="form-control"
            placeholder="Contraseña"
            type="password"
          />
        </div>
        <!-- form-group end.// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
          </div>
          <input
            class="form-control"
            placeholder="Confirmar contraseña"
            type="password"
          />
        </div>
        <!-- form-group// -->
        <div class="form-group">
          <button type="submit" name="insertar" class="btn btn-primary btn-block">
            Insertar datos
          </button>
          <button type="submit" name="modificar" class="btn btn-primary btn-block">
            Modificar datos
          </button>
        </div>
        <!-- form-group// -->
      </form>
    </article>
  </div>
  <!-- card.// -->
</div>
<!--container end.//-->
</article>
