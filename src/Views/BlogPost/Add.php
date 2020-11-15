<div class="container">
	<div class="row">
      <div class="col-12">
        <div class="well well-sm">
          <form class="form-horizontal" action="/post/add" method="POST" enctype="multipart/form-data">
            <fieldset>
              <legend class="text-center my-4">Nuevo post</legend>

              <!-- titulo input-->
              <div class="form-group">
                <label class="col-md-3 control-label" for="titulo">Título</label>
                <div class="col-md-9">
                  <input required id="titulo" name="titulo" type="text" placeholder="Título" class="form-control" <?= !empty($_SESSION["post"]["titulo"]) ? "value=".$_SESSION["post"]["titulo"] : ""  ?> >
                </div>
              </div>

              <!-- Message body -->
              <div class="form-group">
                <label class="col-md-3 control-label" for="mensaje">Tu mensaje</label>
                <div class="col-md-9">
                  <textarea required class="form-control" id="mensaje" name="mensaje" placeholder="Por favor mete tu mensaje aqui..." rows="5"  ><?= !empty($_SESSION["post"]["mensaje"]) ? $_SESSION["post"]["mensaje"] : ""  ?></textarea>
                </div>
              </div>

              <!-- Radio buttons visibilidad -->
              <div class="form-group">
                <div class="col-md-9">
                  <div class="custom-control custom-radio">
                      <input type="radio" id="visible" name="visibleRadio" class="custom-control-input" <?= empty($_SESSION["post"]["visible"]) || $_SESSION["post"]["visible"]!=0 ? "checked=checked" : ""  ?> value=1>
                      <label class="custom-control-label" for="visible">Público</label>
                  </div>
                  <div class="custom-control custom-radio">
                      <input type="radio" id="noVisible" name="visibleRadio" class="custom-control-input" <?= !empty($_SESSION["post"]["visible"]) && $_SESSION["post"]["visible"]==0 ? "checked=checked" : ""  ?> value=0>
                      <label class="custom-control-label" for="noVisible">Privado</label>
                  </div>
                </div>
              </div>

              <!-- Selector categoria multiple -->
              <div class="form-group">
                <label class="col-md-3 control-label" for="categorias">Categoria:</label>
                  <div class="col-md-9">
                  <select name="categorias[]" multiple class="form-control" id="categorias">

                    <?php if(!empty($_SESSION['categorias'])): ?>
                      <?php foreach($_SESSION['categorias'] as $key => $value): ?>
                        <?php 
                          $nombreCategoria = $_SESSION['categorias'][$key]->name;
                          $idCategoria = $_SESSION['categorias'][$key]->id;
                        ?>
                        <option value='<?= $idCategoria ?>' <?= !empty($_SESSION["post"]["categoriasAnteriores"]) && in_array(intval($idCategoria), $_SESSION["post"]["categoriasAnteriores"]) ? 'selected' : ''?>>  <?= $nombreCategoria ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Imagen -->
              <div class="form-group">
                <label class="col-md-4 control-label">Insertar imagen</label>
                <div class="col-md-9">
                <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
                  <input name="imagenes[]" type="file" class="form-control-file" accept=".png, .jpg, .gif" multiple>
                  <div id="listImgs" class="my-3">
                  </div>
                </div>
              </div>

              <!-- Form actions -->
              <div class="form-group">
                <div class="col-md-12 text-center">
                  <button type="submit" name="add" class="btn btn-primary btn-lg">Crear post</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
	</div>
</div>