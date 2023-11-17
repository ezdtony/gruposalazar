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
                                <label class="form-label" for="prod_brand">Marca <span class="form-label-secondary"></span></label>
                                <select id="prod_brand" class="form-control">
                                    <option>Choose an option</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
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
                                <input type="text" id="prod_barcode" class="form-control obligatory" placeholder="Código de barras">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label" for="prod_meassure">U. Medida: <span class="form-label-secondary"></span></label>
                                <select id="prod_meassure" class="form-control">
                                    <option>Choose an option</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="mb-4">
                                <label class="form-label">Precio unitario: <span class="legend-circle bg-danger"></span></label>
                                <input id="prod_price" type="text" class="form-control obligatory" placeholder="Precio unitario">
                            </div>
                        </div>
                        <div class="col-4">
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
                                <input type="text" id="prod_man_stock" class="form-control obligatory" placeholder="Stock máximo">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label">Descripción: <span class="legend-circle bg-danger"></span></label>
                                <textarea class="form-control" placeholder="Descripción" id="prod_description" rows="4"></textarea>

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