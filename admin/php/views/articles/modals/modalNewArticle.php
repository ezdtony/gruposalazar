<div class="modal fade" id="modalNewArticle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNewArticleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalNewArticleLabel">Registrar nuevo producto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <!-- <h4>Información general</h4> -->
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Código: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_code" type="text" class="form-control obligatory" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mb-4">
                                <label class="form-label">Nombre del articulo: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_name" type="text" class="form-control obligatory" placeholder="Nombre del articulo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Marca: <span class="legend-circle bg-danger"></span></label><br>
                                <select class="form-select js-example-basic-single js-example-responsive" style="width: 75%" id="prod_brand" autocomplete="off">
                                    <option disabled selected value="">Seleccione una marca...</option>
                                    <?php foreach ($getAllBrands as $brand) : ?>
                                        <option value="<?= $brand->id_brands ?>"><?= $brand->brand ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">SKU: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_sku" type="text" class="form-control obligatory" placeholder="SKU">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Código de barras: <span class="legend-circle bg-danger"></span></label>
                                <input type="number" id="prod_barcode" class="form-control obligatory" placeholder="Código de barras">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="mb-3">
                                <label class="form-label">U. Medida: <span class="legend-circle bg-danger"></span></label><br>
                                <select class="form-select js-example-basic-single js-example-responsive" style="width: 75%" id="prod_meassure" autocomplete="off">
                                    <option disabled selected value="">Seleccione una marca...</option>
                                    <?php foreach ($getAllMU as $mu) : ?>
                                        <option value="<?= $mu->id_measurement_units ?>"><?= $mu->type_measure ?> (<?= $mu->measure_symbol ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Precio de compra: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_purchase_price" type="text" class="form-control obligatory" placeholder="Precio de compra">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Precio unitario (Venta al público): <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_price" type="text" class="form-control obligatory" placeholder="Precio unitario">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <br><br>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="prod_bulk">
                                    <label class="form-check-label" for="prod_bulk">Venta a granel</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock inicial: <span class="legend-circle bg-danger"></span></label>
                                <input type="text" id="prod_stock" class="form-control obligatory" placeholder="Stock inicial">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock mínimo: <span class="legend-circle bg-danger"></span></label>
                                <input type="text" id="prod_min_stock" class="form-control obligatory" placeholder="Stock mínimo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock máximo: <span class="legend-circle bg-danger"></span></label>
                                <input type="text" id="prod_max_stock" class="form-control obligatory" placeholder="Stock máximo">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Descripción: <span class="legend-circle bg-danger"></span></label>
                                <textarea class="form-control" placeholder="Descripción" id="prod_description" rows="4"></textarea>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-md-8">
                                <label for="validationValidFileInput1">Imagen:  <span class="legend-circle bg-danger"></span></label>
                                <input type="file" id="prod_image" class="form-control">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveNewProduct">Guardar</button>
            </div>
        </div>
    </div>
</div>