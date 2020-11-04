<?php 
  if(!empty($_SESSION["blogPost"]))
  { 
    $post=json_decode($_SESSION["blogPost"]);
  }
  if(!empty($_SESSION["categorias"]))
  {
    $categorias=json_decode($_SESSION["categorias"]);
  }
  if(!empty($_SESSION["categoriasPost"]))
  {
    $categoriasPost=json_decode($_SESSION["categoriasPost"]);
    $categoriasAnteriores=array();

    foreach ($categoriasPost as $key => $value) {
      array_push($categoriasAnteriores,intval($categoriasPost[$key]->category_id));
    }
  }
?>
<div class="container">
	<div class="row">
      <div class="col-md-6">
          <form class="form-horizontal" action="/post/edit" method="POST" enctype="multipart/form-data">
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
            
            <!-- Selector categoria multiple -->
            <div class="form-group">
              <label for="categorias">Categoria:</label>
              <select name="categorias[]" multiple class="form-control" id="categorias">
                <?php
                  if(!empty($categorias))
                  {
                    foreach ($categorias as $key => $value) {
                      $nombreCategoria = $categorias[$key]->name;
                      $idCategoria = $categorias[$key]->id;
                      echo "<option value='$idCategoria'";
                      if(in_array(intval($categorias[$key]->id), $categoriasAnteriores))
                      {
                        echo " selected";
                      }
                      echo ">$nombreCategoria</option>";
                    }
                  }
                ?>
              </select>
            </div>

            <!-- Imagen -->
            <div class="form-group">
                <label class="col-md-4 control-label">Insertar imagen</label>
                <div class="col-md-9">
                <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
                <input name="imagenes[]" type="file" class="form-control-file" accept=".png, .jpg, .gif" multiple> 
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
      <div class="col-md-6">
        <!-- Preview Image -->
        <?php foreach($_SESSION["imgs"] as $img): ?>
          <form class="form-horizontal" action="/post/deleteImg" method="POST">
            <img class="img-thumbnail" width="200px" height="200px" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($img->img); ?>" />
            <button
              name="deleteImg"
              type="submit" 
              class="btn btn-danger btn-sm"
              data-toggle="tooltip" 
              data-placement="top"
              title="Eliminar">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        <?php endforeach;?>
      </div>
	</div>
</div>