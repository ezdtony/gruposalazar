<div class="modal fade" id="addTags" tabindex="-1" aria-labelledby="addTagsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="addTagsLabel">Etiquetas de Articulo</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Etiqueta<span class="legend-circle bg-danger"></span></label><br>
                <select class="form-select js-example-basic-single" id="selectTag" autocomplete="off">
                    <option disabled selected value="">Seleccione una etiquetas...</option>
                    <?php foreach ($getAllTags as $tags) : ?>
                        <option value="<?= $tags->id_tags ?>"><?= $tags->tag_name ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <br>
                
                <div class="tagsProd">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>