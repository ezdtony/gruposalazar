<style>
    .liProductSearch li:hover {
        background-color: #e5f8f9 !important;
        border-color: #befafd;
        cursor: pointer;
    }

    .modal-body {
        height: 600px;
        width: 100%;
        overflow-y: auto;
    }
</style>
<div class="modal fade" id="modalNewIncome" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" width="700px" aria-labelledby="modalNewIncomeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalNewIncomeLabel">Nueva órden de entrada</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- <h4>Información general</h4> -->
                    <div class="col-6">
                        <label class="form-label">Sucursal<span class="legend-circle bg-danger"></span></label><br>
                        <select class="form-select js-example-basic-single" id="selectSubsidiary" autocomplete="off">
                            <option disabled selected value="">Seleccione una sucursal...</option>
                            <?php foreach ($getAllSubsidiary as $subsidiary) : ?>
                                <option value="<?= $subsidiary->id_subsidiary ?>"><?= $subsidiary->subsidiary_name ?> | <?= $subsidiary->subsidiary_prefix ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Producto: <span class="legend-circle bg-danger"></span></label>
                            <input id="product" type="text" class="form-control" placeholder="Buscar un producto..." disabled>
                        </div>
                        <div id="showResultsProds" style="display:none; height:200px; overflow: auto;"></div>
                    </div>
                </div>
                <br>
                <br>
                <div class="table-responsive">
                    <table>
                        <table class="table" id="tableProductsIncome">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">CÓDIGO PROD.</th>
                                    <th scope="col">PRODUCTO</th>
                                    <th scope="col">MARCA</th>
                                    <th scope="col">PRECIO COMPRA</th>
                                    <th scope="col">PRECIO VENTA</th>
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">QUITAR</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light closeModalNewIncome" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" disabled class="btn btn-primary" id="saveNewIncome">Guardar</button>
            </div>
        </div>
    </div>
</div>