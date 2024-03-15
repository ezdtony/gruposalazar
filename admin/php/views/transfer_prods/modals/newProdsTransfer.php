<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="modalNewProdsTransfer" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title h4" id="myExtraLargeModalLabel">Nuevo traspaso</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center align-items-center g-2">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="subsidiary_og">Sucursal de origen <span class="form-label-secondary"></span></label>
                            <select id="subsidiary_og" class="form-control">
                                <option selected disabled>Seleccione una opción</option>
                                <?php foreach($getAllSubsidiary as $subsidary):?>
                                <option value="<?=$subsidary->id_subsidiary?>"><?=$subsidary->subsidiary_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                        <label class="form-label" for="subsidiary_des">Sucursal de destino <span class="form-label-secondary"></span></label>
                            <select id="subsidiary_des" class="form-control">
                                <option selected disabled>Seleccione una opción</option>
                                <?php foreach($getAllSubsidiary as $subsidary):?>
                                <option value="<?=$subsidary->id_subsidiary?>"><?=$subsidary->subsidiary_name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">Producto: <span class="legend-circle bg-danger"></span></label>
                            <input id="product" type="text" class="form-control" placeholder="Buscar un producto...">
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
            <div class="container">
            <h3 hidden>Total:</h3>
            <h4  hidden id="lblTotalIncome" data-total-income="0">$0</h4>
            </div>
            
            <div class="modal-footer">
            
                <button type="button" class="btn btn-light closeModalNewIncome" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" disabled class="btn btn-primary" id="saveNewTrasnfer">Registrar</button>
            </div>
        </div>
    </div>
</div>