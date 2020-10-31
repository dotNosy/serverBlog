<?php if(!empty($_SESSION["blogPost"]))
{ 
    $post=json_decode($_SESSION["blogPost"]);
} ?>
<div class="container">
	<div class="row">
      <div class="col-md-6 col-md-offset-3">
          <form class="form-horizontal" action="/post/edit" method="POST">
          <fieldset>
            <legend class="text-center">Nuevo post</legend>
            <input type="hidden" name="id" <?="value='$post->id'"?>>

            <!-- titulo input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="titulo">Título</label>
              <div class="col-md-9">
                <input id="titulo" name="titulo" type="text" placeholder="Título" class="form-control" <?="value='$post->title'"?>>
              </div>
            </div>
    
            <!-- Message body -->
            <div class="form-group">
              <label class="col-md-3 control-label" for="mensaje">Tu mensaje</label>
              <div class="col-md-9">
                <textarea class="form-control" id="mensaje" name="mensaje" placeholder="Por favor mete tu mensaje aqui..." rows="5"><?=$post->text?></textarea>
              </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-radio">
                    <input type="radio" id="visible" name="visibleRadio" class="custom-control-input" <?= ($post->visible) ? "checked=checked" : "" ?> value=1>
                    <label class="custom-control-label" for="visible">Público</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="noVisible" name="visibleRadio" class="custom-control-input" <?= (!$post->visible) ? "checked=checked" : "" ?> value=0>
                    <label class="custom-control-label" for="noVisible">Privado</label>
                </div>
            </div>
    
            <!-- Form actions -->
            <div class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" name="edit" class="btn btn-primary btn-lg">Editar post</button>
              </div>
            </div>
          </fieldset>
          </form>
      </div>
	</div>
</div>