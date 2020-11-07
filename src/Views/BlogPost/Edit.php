<?php 
  if(!empty($_SESSION["blogPost"]))
  { 
    $post = $_SESSION["blogPost"];
  }
?>

<div class="row">
    <div class="col-md-6 p-5">
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

              <?php if(!empty($_SESSION['categorias'])): ?>
                <?php foreach($_SESSION['categorias'] as $key => $value): ?>
                  <?php 
                    $nombreCategoria = $_SESSION['categorias'][$key]->name;
                    $idCategoria = $_SESSION['categorias'][$key]->id;
                  ?>
                  <option value='<?= $idCategoria ?>' <?= in_array(intval($idCategoria), $_SESSION['categoriasAnteriores']) ? 'selected' : ''?>>  <?= $nombreCategoria ?></option>
                <?php endforeach; ?>
              <?php endif; ?>
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
          <div class="col-12 my-5 mx-2">
            <div class="row">
              <div class="col-7 text-center">
              <div class="col-3">
                    <button
                        id="<?=$img->id?>"
                        class="col-12 btn btn-danger btn-sm deleteImg my-2 float-left"
                        data-toggle="tooltip" 
                        data-placement="top"
                        title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button
                        id="<?=$img->id?>"
                        value="portada"
                        class="<?=$img->id?> col-12 btn <?= $img->pos == "portada" ? 'btn-success' : 'btn-outline-success'?> btn-sm my-2 float-left btnpos"
                        data-toggle="tooltip" 
                        data-placement="top"
                        title="Establecer como portada">
                        <i class="far fa-image"></i>
                    </button>
              </div>
                <img class="img-thumbnail col-9"   style="width:200px;height:175px" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($img->img); ?>" />
              </div>
              <div class="col-5">
              <button
                id="<?=$img->id?>"
                class="<?=$img->id?> btnpos btn <?= $img->pos == "side" ? 'btn-primary' : 'btn-outline-primary'?> col-2 float-left float-right h-100 mr-3"
                value="side">
              L
              a
              d
              o
              </button>
                <button 
                  id="<?=$img->id?>"
                  class="<?=$img->id?> btnpos btn <?= $img->pos == "starting" ? 'btn-success' : 'btn-outline-success'?> col-8"
                  value="starting">
                   Starting
                </button>
                <button
                  id="<?=$img->id?>"
                  class="<?=$img->id?> btnpos offset-md-1 btn <?= $img->pos == "inline" ? 'btn-danger' : 'btn-outline-danger'?> col-6 float-left my-3"
                  value="inline"> Inline
                </button>
                <button 
                  id="<?=$img->id?>"
                  class="<?=$img->id?> btnpos btn  <?= $img->pos == "ending" ? 'btn-dark' : 'btn-outline-dark'?> col-8 float-left" 
                  value="ending"> Ending
                </button>
              </div>
            </div>
          </div>
        <?php endforeach;?>
    </div>
</div>