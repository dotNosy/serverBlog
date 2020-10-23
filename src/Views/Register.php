<div class="container">
    <hr>
  <div class="card bg-light">
      <?php if (!empty($_SESSION["error"])):?>
        <?= '<div class="col-12 alert-danger text-center">'. $_SESSION["error"] .'</div>' ?>
      <?php endif;?>
    <article class="card-body mx-auto" style="max-width: 400px">
      <h4 class="card-title mt-3 text-center">Create Account</h4>
      <p class="text-center">Get started with your free account</p>
      <p>
        <a href="" class="btn btn-block btn-twitter">
          <i class="fab fa-twitter"></i>   Login via Twitter</a
        >
        <a href="" class="btn btn-block btn-facebook">
          <i class="fab fa-facebook-f"></i>   Login via facebook</a
        >
      </p>
      <p class="divider-text">
        <span class="bg-light">OR</span>
      </p>
      <form method="POST" action="/login/register">
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
          </div>
          <input
            name="username"
            class="form-control"
            placeholder="NickName"
            type="text"
          />
        </div>
        <!-- form-group end.// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
          </div>
          <input
            name="password"
            class="form-control"
            placeholder="Create password"
            type="password"
          />
        </div>
        <!-- form-group// -->
        <div class="form-group input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
          </div>
          <input
            name="rpassword"
            class="form-control"
            placeholder="Repeat password"
            type="password"
          />
        </div>
        <!-- form-group// -->
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block" name="register">
            Create Account
          </button>
        </div>
        <!-- form-group// -->
        <p class="text-center">Have an account? <a href="/login">Log In</a></p>
      </form>
    </article>
  </div>
  <!-- card.// -->
</div>
<!--container end.//-->
</article>
