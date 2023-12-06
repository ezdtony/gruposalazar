<div class="modal fade" id="modalEditArticle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditArticleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalEditArticleLabel">Editar producto</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <!-- <h4>Información general</h4> -->
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Código:</label>
                                <input id="edit_prod_code" data-allow-empty="0" type="text" data-column-name="product_short_name" class="form-control obligatory updateProduct" placeholder="Código">
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="mb-4">
                                <label class="form-label">Nombre del articulo:</label>
                                <input data-column-name="product_name" data-allow-empty="0" id="edit_prod_name" type="text" class="form-control obligatory updateProduct" placeholder="Nombre del articulo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Marca:</label><br>
                                <select data-column-name="id_brands" class="form-select js-example-basic-single js-example-responsive slctUpdateProduct" style="width: 75%" data-allow-empty="0" id="edit_prod_brand" autocomplete="off">
                                    <option disabled selected value="">Seleccione una marca...</option>
                                    <?php foreach ($getAllBrands as $brand) : ?>
                                        <option value="<?= $brand->id_brands ?>"><?= $brand->brand ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">SKU:</label>
                                <input data-column-name="sku" data-allow-empty="0" id="edit_prod_sku" type="text" class="form-control obligatory updateProduct" placeholder="SKU">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Código de barras:</label>
                                <input data-column-name="product_barcode" type="number" data-allow-empty="1" id="edit_prod_barcode" class="form-control obligatory updateProductBarcode" placeholder="Código de barras">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="mb-3">
                                <label class="form-label">U. Medida:</label><br>
                                <select data-column-name="id_measurement_units" class="form-select js-example-basic-single js-example-responsive slctUpdateProduct" style="width: 75%" data-allow-empty="0" id="edit_prod_meassure" autocomplete="off">
                                    <option disabled selected value="">Seleccione una marca...</option>
                                    <?php foreach ($getAllMU as $mu) : ?>
                                        <option value="<?= $mu->id_measurement_units ?>"><?= $mu->type_measure ?> (<?= $mu->measure_symbol ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Precio de compra:</label>
                                <input data-column-name="purchase_price" data-allow-empty="0" id="edit_prod_purchase_price" type="text" class="form-control obligatory updateProductPrice" placeholder="Precio de compra">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <label class="form-label">Precio unitario (Venta al público):</label>
                                <input data-column-name="price" data-allow-empty="0" id="edit_prod_price" type="text" class="form-control obligatory updateProductPrice" placeholder="Precio unitario">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-4">
                                <br><br>
                                <div class="form-check form-switch">
                                    <input data-column-name="bulk_sell" class="form-check-input chckUpdateProduct" type="checkbox" role="switch" data-allow-empty="1" id="edit_prod_bulk">
                                    <label class="form-check-label" for="prod_bulk">Venta a granel</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock inicial:</label>
                                <input data-column-name="stock" type="text" data-allow-empty="0" id="edit_prod_stock" class="form-control obligatory updateProduct" placeholder="Stock inicial">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock mínimo:</label>
                                <input data-column-name="min_stock" type="text" data-allow-empty="0" id="edit_prod_min_stock" class="form-control obligatory updateProduct" placeholder="Stock mínimo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Stock máximo:</label>
                                <input data-column-name="ideal_stock" type="text" data-allow-empty="0" id="edit_prod_max_stock" class="form-control obligatory updateProduct" placeholder="Stock máximo">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Descripción:</label>
                                <textarea data-column-name="description" class="form-control updateProduct" placeholder="Descripción" data-allow-empty="0" id="edit_prod_description" rows="4"></textarea>

                            </div>
                        </div>
                        <div class="col-12">
                            <div class="col-md-8">
                                <label for="validationValidFileInput1">Imagen:</label>
                                <input type="file" id="edit_prod_image" class="form-control">
                                <h6>Elegir otra imagen</h6>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary closeModalEditProd" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary" id="saveNewProduct">Guardar</button> -->
            </div>
        </div>
    </div>
</div>