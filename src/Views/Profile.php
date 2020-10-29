<div class="container">
    <hr>
  <div class="card bg-light">
    <article class="card-body mx-auto" style="max-width: 400px">
      <h4 class="card-title mt-3 text-center">PERFIL</h4>
      <form action="/profile/updateProfile" method="POST">
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
          />
        </div>
        <!-- form-group end.// -->
        <!-- form-group// -->
        <div class="form-group">
          <button type="submit" name="update" class="btn btn-primary btn-block">
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
